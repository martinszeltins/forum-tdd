<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home',                                             'HomeController@index')->name('home');

Route::get('threads',                                           'ThreadsController@index');
Route::get('threads/create',                                    'ThreadsController@create');
Route::get('threads/{channel:slug}',                            'ThreadsController@index');
Route::get('threads/{channel:slug}/{thread}',                   'ThreadsController@show');
Route::delete('threads/{channel:slug}/{thread}',                'ThreadsController@destroy');
Route::post('threads',                                          'ThreadsController@store');
Route::post('threads/{channel}/{thread}/replies',               'RepliesController@store');
Route::delete('replies/{reply}',                                'RepliesController@destroy');
Route::post('replies/{reply}/favorites',                        'FavoritesController@store');
Route::delete('replies/{reply}/favorites',                      'FavoritesController@destroy');
Route::patch('replies/{reply}',                                 'RepliesController@update');
Route::get('/threads/{channel}/{thread}/replies',               'RepliesController@index');
Route::post('/threads/{channel}/{thread}/subscriptions',        'ThreadSubscriptionsController@store');
Route::delete('/threads/{channel}/{thread}/subscriptions',      'ThreadSubscriptionsController@destroy');

Route::get('/profiles/{user:name}',                             'ProfilesController@show')->name('profile');