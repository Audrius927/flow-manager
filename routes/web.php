<?php

use App\Http\Controllers\PrivateFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

Route::middleware(['auth'])
    ->get('/files/private/{path}', PrivateFileController::class)
    ->where('path', '.*')
    ->name('files.private.show');
