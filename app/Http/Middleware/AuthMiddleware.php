<?php namespace App\Http\Middleware;

use Closure;

class AuthMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('api_key');

        if (is_null($apiKey) || config('app.api_key') != $apiKey)
        {
            return view('no-authenticated');
        }

        return $next($request);
    }

}

