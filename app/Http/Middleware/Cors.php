<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, User-Token, Accept');

        return $response;   
        // $headers = [
        //     'Access-Control-Allow-Origin'      => '*',
        //     'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
        //     'Access-Control-Allow-Credentials' => 'true',
        //     'Access-Control-Max-Age'           => '86400',
        //     'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, User-Token, Accept'
        // ];

        // $response = $next($request);
        // foreach($headers as $key => $value)
        // {
        //     $response->header($key, $value);
        // }

        // return "something went wrong";
        // return $response;
    }
}
 