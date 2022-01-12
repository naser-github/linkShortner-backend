<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', 'App\Http\Controllers\LoginController@login');

//tags
Route::resource('/tags', 'App\Http\Controllers\TagController')->names([
    'index' => 'tags.index',
    'create' => 'tags.create',
    'store' => 'tags.store',
    'show' => 'tags.show',
    'edit' => 'tags.edit',
    'update' => 'tags.update',
]);

//Route::group(['middleware' => 'auth:sanctum'], function () {
//All secure URL's
//});


//Route::middleware('auth:sanctum')->get('/user', function () {
//    Route::get('/'), [Controller::class, 'search']
//});
