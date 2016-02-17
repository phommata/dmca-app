<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * The Home Page
 */

Route::get('/', 'PagesController@home');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

/**
 * Authentication
 */
Route::group(['middleware' => ['web']], function () {
    Route::auth();

    /**
     * Notices
     */
    Route::get('notices/create/confirm', 'NoticesController@confirm');
    Route::resource('notices', 'NoticesController');


});
