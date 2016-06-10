<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Upload;

use App;
use Config;
use File;
use Storage;

class UploadController extends Controller
{
    /**
     *  Fetch an upload by alias + protect alias - public endpoint
     */
    public function get(Request $request, $alias, $protectAlias)
    {
        // Attempt to find upload with matching alias AND protect alias
        $upload = Upload::where('alias', $alias)
            ->where('is_deleted', false)
            ->where('protect_alias', $protectAlias)
            ->first();

        // Check if no match was found
        if (!$upload)
            return App::abort(404);

        // Upload found, fetch associated user
        $user = $upload->user;

        if (!$user)
            return App::abort(500);

        // Check if the referenced upload file exists
        if (!Storage::disk('local')->exists($upload->file_location))
            return App::abort(404);

        // Increment views on the upload
        $upload->views++;
        $upload->save();

        // Send file, with some extra headers
        $path = storage_path() . '/app/uploads/' . $upload->file_location;
        return response()->download($path, $upload->file_name);
    }


    /**
     *  Accept file upload from a puush client
     */
    public function upload(Request $request)
    {
        // Validate incoming request
        $this->validate($request, [
            'c' => [ 'required', 'min:32', 'max:32' ], // File hash
            'f' => [ 'required' ]
        ]);

        // Check a file was supplied
        if (!$request->hasFile('f') || !$request->file('f')->isValid())
            return App::abort(422);

        // Fetch user
        $user = $request->get('user');

        // Fetch uploaded file
        $file = $request->file('f');

        // Construct temporary storage path names
        $tempPath = storage_path() . '/app/uploads/' . $user->email . '/';
        $tempName = $user->id . '_' . uniqid() . '.tmp';
        $tempFile = $tempPath . '/' . $tempName;
        
        // Attempt to move file to a temporary path
        if (!$file->move($tempPath, $tempName))
            return App::abort(500);

        // Calculate MD5 hash of the file, compare it to the client-supplied hash
        $md5sum = strtolower(md5_file($tempFile));

        if ($md5sum != strtolower($request->input('c')))
            return App::abort(500);

        // Attempt to create file stream
        if (!($handle = fopen($tempFile, 'r+')))
            return App::abort(500);

        $newName = $md5sum . '.' . $file->getClientOriginalExtension();

        // Move file to permanent storage
        if (!Storage::disk('local')->put($user->email . '/' . $newName, $handle))
            return App::abort(500);

        // Remove temporary file
        if (!Storage::disk('local')->delete($user->email . '/' . $tempName))
            return App::abort(500);

        // Construct new file upload
        $newFile = [
            /**
             *  Upload information
             */
            'user_id' => $user->id,
            'alias' => substr(str_shuffle(md5(time())), 0, 4),
            'protect_alias' => substr(str_shuffle(md5(time())), 0, 6),
            'ip_address' => $request->ip(),
            'views' => 0,
            'is_deleted' => false,


            /**
             *  File information
             */
            'file_name' => $file->getClientOriginalName(),
            'file_location' => $user->email . '/' . $newName,
            'file_size' => $file->getClientSize(),
            'file_hash' => $md5sum,
            'mime_type' => $file->getClientMimeType()
        ];

        // Create upload
        $upload = Upload::create($newFile);
        $upload->save();

        // Construct response
        $response = [
            '0', // Status indicator
            url($newFile['alias'] . '/' . $newFile['protect_alias']), // Public URL
            $upload->id, // Upload database ID
            $newFile['file_size'] // File size in bytes
        ];

        // Send response
        return implode(', ', $response);
    }
}
