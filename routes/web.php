<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use App\Mail\EventCreated;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';

// Event URLs
Route::get('/', [EventController::class, 'index'])->name('index');
Route::get('/myEvents/{user_id}', [EventController::class, 'myEvents'])->name('myEvents');
Route::resource('/event', EventController::class);

Route::resource('/category', CategoryController::class)->middleware('is_Admin');


// Stripe URLs
Route::post('/stripe/buyTicket', [StripeController::class, 'buyTicket'])->name('stripe.buyTicket')->middleware('auth');
Route::get('/stripe/success/{event}', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel/{event}', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::post('/stripe/webhook', [StripeController::class, 'webhook'])->name('stripe.webhook');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





