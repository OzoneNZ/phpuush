<?php

namespace App\Http\Middleware;

use App\Models\User;

use App;
use Closure;
use Request;

class ClientCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check the incoming request has the 'z' key
        if (!$request->has('z'))
            return App::abort(403);

        // Check if the 'z' key is what we expect...
        if ($request->get('z') != 'poop')
            return App::abort(403);

        // Check for the 'k' API key UNLESS the auth page is being hit
        if (Request::is('api/auth'))
            return $next($request);

        // Check if an API key was supplied
        if (!$request->has('k'))
            return App::abort(403);

        // Check the API key is valid and the user is enabled
        $user = User::where('api_key', $request->get('k'))
            ->where('enabled', true)
            ->first();

        // See if a user match was found
        if (!$user)
            return App::abort(403);

        // Share user information with all controllers
        $request->attributes->add([ 'user' => $user ]);

        // Proceed with request
        return $next($request);
    }
}
