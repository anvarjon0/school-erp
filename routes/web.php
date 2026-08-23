<?php

use Illuminate\Support\Facades\Route;

// For API routes, they are handled in routes/api.php

// For all other web routes, return the Vue SPA index.html
Route::get('{any}', function () {
    $path = public_path('index.html');
    if (file_exists($path)) {
        return file_get_contents($path);
    }
    return 'Frontend is building. Please wait...';
})->where('any', '.*');
