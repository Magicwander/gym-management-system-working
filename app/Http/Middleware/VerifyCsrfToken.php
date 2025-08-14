<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'login',
        '/login',
        'login-test',
        '/login-test',
        'trainer/*',
        '/trainer/*',
        'test-delete-workout/*',
        '/test-delete-workout/*',
    ];
    
    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        // Temporarily disable all CSRF verification for testing
        return true;
        
        // Original logic (commented out for testing)
        // return parent::inExceptArray($request);
    }
}