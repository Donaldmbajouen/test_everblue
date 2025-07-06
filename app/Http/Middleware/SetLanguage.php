<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $localeLanguage = Session::get('locale');

        if ($localeLanguage) {
            App::setLocale($localeLanguage);
            return $next($request);
        }

        App::setLocale(getSetting() ? getSetting()->default_language : 'en');
        return $next($request);
    }
}
