<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetRequestLocale
{
    private const SUPPORTED_LOCALES = ['id', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $requestedLocale = strtolower((string) $request->header('Accept-Language', config('app.locale', 'en')));
        $normalizedLocale = explode(',', $requestedLocale)[0];
        $normalizedLocale = explode('-', $normalizedLocale)[0];

        if (!in_array($normalizedLocale, self::SUPPORTED_LOCALES, true)) {
            $normalizedLocale = config('app.fallback_locale', 'en');
        }

        App::setLocale($normalizedLocale);

        return $next($request);
    }
}
