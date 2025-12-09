<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch(Request $request)
    {
        $locale = $request->input('locale');

        // Validate locale
        if (!in_array($locale, ['en', 'id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid locale'
            ], 400);
        }

        // Store in session
        Session::put('locale', $locale);

        // Update user preference if authenticated
        if (auth()->check()) {
            auth()->user()->update(['language' => $locale]);
        }

        // Set application locale
        App::setLocale($locale);

        return response()->json([
            'success' => true,
            'message' => __('messages.language_changed'),
            'locale' => $locale
        ]);
    }

    /**
     * Get current locale
     */
    public function current()
    {
        return response()->json([
            'locale' => App::getLocale(),
            'available' => ['en', 'id']
        ]);
    }
}
