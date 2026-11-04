<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'], true)) {
        session(['locale' => $locale]);
    }

    return redirect()->back(fallback: route('filament.admin.pages.dashboard'));
})->name('switch-language');
