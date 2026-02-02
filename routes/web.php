<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

use App\Http\Controllers\EventController;

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/submit', [EventController::class, 'create'])->name('events.create');
Route::post('/events/submit', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

use Illuminate\Support\Facades\Mail;

Route::get('/_mailtest', function () {
    Mail::raw('Teste Mailtrap', function ($message) {
        $message->to('ljadise@gmail.com')->subject('Teste Mailtrap');
    });

    return response('sent', 200);
});
