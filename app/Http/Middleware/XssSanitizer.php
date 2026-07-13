<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssSanitizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        // Recursively walk the input data and strip HTML tags from strings
        array_walk_recursive($input, function (&$val) {
            if (is_string($val)) {
                $val = strip_tags($val);
            }
        });

        // Merge back the sanitized input into the request
        $request->merge($input);

        return $next($request);
    }
}
