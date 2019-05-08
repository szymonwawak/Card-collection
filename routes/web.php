<?php

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


Route::group([
    'middleware' => 'roles',
    'role' => 'User'
],function() {

    Route::get('/propose',
        [ 'uses' =>'CardPropositionController@index',
            'as' => 'propose.index']);

    Route::post('/propose',
        [ 'uses' =>'CardPropositionController@store',
            'as' => 'propose.create']);
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
