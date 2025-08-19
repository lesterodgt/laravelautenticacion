<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'password.expiry'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/mail-test', function () {
    Mail::raw('Prueba SMTP sin auth ni TLS', function ($m) {
        $m->to('test@lester.com')->subject('Test SMTP');
    });
    return 'Correo enviado';
});



Route::middleware(['auth','role:admin', 'password.expiry'])->group(function () {
    session()->flash('status',"Bienvenido admin");
    Route::get(
        '/admin', fn () => view('dashboard')
    )->name('dashboard');
});

Route::middleware(['auth','role:admin,editor'])->group(function () {
    Route::get('/panel', fn () => 'Panel para admin o editor');
});
