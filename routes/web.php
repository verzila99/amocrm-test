<?php

use App\Http\Controllers\LeadsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/leads', [LeadsController::class, 'store'])->name('store-leads');
Route::get('/leads', [LeadsController::class, 'index'])->name('index-leads');