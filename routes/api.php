<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    //SHORT URL
    Route::group(['prefix' => '/'], function () {
        Route::post('/shorten-url', 'App\Http\Controllers\LinkShortenerController@shortlink')->name('short_link');

        Route::get('/my-url', 'App\Http\Controllers\LinkShortenerController@myURL')->name('mylink');
        Route::get('/url-details/{id}', 'App\Http\Controllers\LinkShortenerController@urlDetails')->name('urlDetails');
        //filterData
        Route::post('/filterData', 'App\Http\Controllers\LinkShortenerController@filterData')->name('filterData');

        Route::post('/url-tag-edit-modal', 'App\Http\Controllers\LinkShortenerController@editModal')->name('urlTagEditModal');

        Route::patch('/update-url/{id}', 'App\Http\Controllers\LinkShortenerController@updateUrl')->name('updateUrl');
    });

    //dashboard
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@dashboard')->name('dashboard');
});

Route::post('/login', 'App\Http\Controllers\LoginController@login');
