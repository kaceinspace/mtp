<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user has language preference in session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // Check if user is authenticated and has language preference
        elseif (auth()->check() && auth()->user()->language) {
            $locale = auth()->user()->language;
            Session::put('locale', $locale);
        }
        // Default to browser language or fallback
        else {
            $locale = $request->getPreferredLanguage(['en', 'id']) ?? config('app.locale');
        }

        // Validate locale
        if (!in_array($locale, ['en', 'id'])) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
