<?php

/**
 * Finished lesson 70
 */

 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('home',                                                      'HomeController@index')->name('home');


/*
|--------------------------------------------------------------------------
| Threads
|--------------------------------------------------------------------------
*/

Route::get('threads',                                                   'ThreadsController@index');
Route::get('threads/create',                                            'ThreadsController@create');
Route::get('threads/{channel:slug}',                                    'ThreadsController@index');
Route::get('threads/{channel:slug}/{thread}',                           'ThreadsController@show');
Route::delete('threads/{channel:slug}/{thread}',                        'ThreadsController@destroy');
Route::post('threads',                                                  'ThreadsController@store');


/*
|--------------------------------------------------------------------------
| Replies
|--------------------------------------------------------------------------
*/

Route::post('threads/{channel}/{thread}/replies',                       'RepliesController@store');
Route::delete('replies/{reply}',                                        'RepliesController@destroy');
Route::patch('replies/{reply}',                                         'RepliesController@update');
Route::get('threads/{channel}/{thread}/replies',                        'RepliesController@index');


/*
|--------------------------------------------------------------------------
| Favorites
|--------------------------------------------------------------------------
*/

Route::post('replies/{reply}/favorites',                                'FavoritesController@store');
Route::delete('replies/{reply}/favorites',                              'FavoritesController@destroy');


/*
|--------------------------------------------------------------------------
| Thread subscriptions
|--------------------------------------------------------------------------
*/

Route::post('threads/{channel}/{thread}/subscriptions',                 'ThreadSubscriptionsController@store');
Route::delete('threads/{channel}/{thread}/subscriptions',               'ThreadSubscriptionsController@destroy');


/*
|--------------------------------------------------------------------------
| User notifications
|--------------------------------------------------------------------------
*/

Route::get('profiles/{user}/notifications/',                            'UserNotificationsController@index');
Route::delete('profiles/{user:name}/notifications/{notification}',      'UserNotificationsController@destroy');


/*
|--------------------------------------------------------------------------
| Profiles
|--------------------------------------------------------------------------
*/

Route::get('profiles/{user:name}',                                      'ProfilesController@show')->name('profile');
Route::delete('profiles/{user:name}/notifications/{notification}',      'UserNotificationsController@destroy');


/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/

Route::get('api/users',                                                 'Api\UsersController@index');


/*
|--------------------------------------------------------------------------
| User avatars
|--------------------------------------------------------------------------
*/

Route::post('api/users/{user}/avatar',                                   'Api\UserAvatarController@store');