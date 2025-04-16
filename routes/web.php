<?php

use Illuminate\Support\Facades\Route;

Route::get('test', 'App\Http\Controllers\FetchAPIController@Test')->name('test');
Route::get('fetch', 'App\Http\Controllers\FetchAPIController@Fetch')->name('fetch');
