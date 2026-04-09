<?php

use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    return response((string) view('sitemap'), 200, [
        'Content-Type' => 'application/xml'
    ]);
});


