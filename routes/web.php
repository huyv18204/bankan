<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/board/{group_id}', [App\Http\Controllers\HomeController::class, 'index'])->name('board')->middleware("check.permissions");
Route::get('/board', [App\Http\Controllers\HomeController::class, 'myBoard'])->name('myBoard');
Route::post('/add-member', [App\Http\Controllers\HomeController::class, 'addMember'])->name('addMember');
Route::post('/add-task', [App\Http\Controllers\HomeController::class, 'addTask'])->name('addTask');
Route::post('/edit-task', [App\Http\Controllers\HomeController::class, 'editTask'])->name('editTask');
Route::post('/update-status', [App\Http\Controllers\HomeController::class, 'taskStatus'])->name('taskStatus');
Route::post('/delete-task', [App\Http\Controllers\HomeController::class, 'deleteTask'])->name('deleteTask');
