<?php

use App\Http\Controllers\MealController;
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


Route::get('/', [MealController::class, 'index'])
   ->name('root');

Route::get('/dashboard', function () {
      return view('dashboard');
  })->middleware(['auth'])->name('dashboard');

Route::resource('meals', MealController::class)
    ->only(['store', 'create', 'update', 'destory', 'edit'])
    ->middleware('auth');

Route::resource('meals', MealController::class)
    ->only(['show', 'index']);

require __DIR__.'/auth.php';
