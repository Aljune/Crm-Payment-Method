<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payment', function () {
    return view('payment');
})->name('payment.form');


Route::post('payment', [PaymentController::class, 'paypal'])->name('paypal');
Route::get('success', [PaymentController::class, 'success'])->name('success');
Route::get('cancel', [PaymentController::class, 'cancel'])->name('cancel');
