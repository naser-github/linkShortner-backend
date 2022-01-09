<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', 'App\Http\Controllers\LoginController@login');

//Route::group(['middleware' => 'auth:sanctum'], function () {
    //All secure URL's
//});


//Route::middleware('auth:sanctum')->get('/user', function () {
//    Route::get('/'), [Controller::class, 'search']
//});
