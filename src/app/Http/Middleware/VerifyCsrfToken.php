<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Create a new "XSRF-TOKEN" cookie that contains the CSRF token.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $config
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function newCookie($request, $config)
    {
        return new Cookie(
            strtoupper(config('app.env')) . '-XSRF-TOKEN',
            $request->session()->token(),
            $this->availableAt(60 * $config['lifetime']),
            $config['path'],
            $config['domain'],
            $config['secure'],
            false,
            false,
            $config['same_site'] ?? null
        );
    }
}
