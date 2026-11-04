<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->hasSession()
            ? $request->session()->get('locale', config('app.locale', 'id'))
            : Session::get('locale', config('app.locale', 'id'));

        if (is_string($locale) && in_array($locale, ['id', 'en'], true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
