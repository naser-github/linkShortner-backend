<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', 'App\Http\Controllers\LoginController@login');

//SHORT URL
Route::group(['prefix' => '/url'], function () {

    Route::post('/shorten-url', 'App\Http\Controllers\Operator\LinkShortenerController@shortlink')->name('short_link');

    Route::get('/my-url', 'App\Http\Controllers\Operator\LinkShortenerController@myURL')->name('mylink');
    Route::get('/show-details/{id}', 'App\Http\Controllers\Operator\LinkShortenerController@showDetails')->name('showDetails');
    //filterData
    Route::post('/filterData', 'App\Http\Controllers\Operator\LinkShortenerController@filterData')->name('filterData');

    Route::post('/url-tag-edit-modal', 'App\Http\Controllers\Operator\LinkShortenerController@editModal')->name('urlTagEditModal');

    Route::patch('/update-url/{id}', 'App\Http\Controllers\Operator\LinkShortenerController@updateUrl')->name('updateUrl');
});

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
