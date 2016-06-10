<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\User;

use Auth;

class AuthController extends Controller
{
    /**
     *  Authenticate a session from a puush client
     */
    public function authenticate(Request $request)
    {
        // Validate the request first
        $this->validate($request, [
            'e' => [ 'required', 'email' ], // Email address
            'p' => [ 'min: 1' ], // Password
            'k' => [ 'min:64', 'max:64' ] // API key (SHA256 hash)
        ]);

        // Check if no API key AND no password were supplied
        if (!$request->has('p') && !$request->has('k'))
            return App::abort(403);

        // Fetch user associated with the supplied email address
        $user = User::where('email', $request->input('e'))->first();

        // Check a user was found
        if (!$user)
            return App::abort(403);

        $authenticated = false;

        // Proceed with password authentication if one was supplied
        if ($request->has('p')) {
            if (!Auth::validate([ 'email' => $request->input('e'), 'password' => $request->input('p') ]))
                return App::abort(403);
            else
                $authenticated = true;
        }
        // Proceed with API key authentication if that was supplied instead
        elseif ($request->has('k')) {
            if ($user->api_key != $request->input('k'))
                return App::abort(403);
            else
                $authenticated = true;
        }

        // Check if the user successfully authenticated
        if (!$authenticated)
            return App::abort(403);
        else {
            // Construct response
            $response = [
                '1', // Status indicator
                $user->api_key, // API key
                '',
                $user->getTotalUploadedBytes()
            ];

            // Return response
            return implode(', ', $response);
        }
    }
}
