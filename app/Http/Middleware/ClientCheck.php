<?php

namespace App\Http\Middleware;

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
        if (!Request::is('api/auth') && !$request->has('k'))
            return App::abort(403);

        // Proceed with request
        return $next($request);
    }
}
