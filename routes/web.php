<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\taskcontroller;
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

Route::get('/', function () {
    return view('welcome');
});
Route :: middleware(['checklogin'])->group(function(){
Route::resource('user', usercontroller::class);
Route::resource('task', taskcontroller::class);
});
Route::get('login',[usercontroller::class,'login']);
Route::post('DoLogin',[usercontroller::class,'DoLogin']);
Route::get('logout',[usercontroller::class,'logout']);
