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

use App\Card;

Route::get('/', function () {

    $cards = Card::inRandomOrder()->limit(9)->get();
    return view('front', compact('cards'));
});


Route::group([
    'middleware' => 'roles',
    'role' => 'User'
],function() {
    Route::resource('stats', 'StatsController');
    Route::get('collection/search', 'UserCardController@search');
    Route::get('collection/{id}/search', 'UserCardController@searchAsGuest');
    Route::resource('propositions', 'CardPropositionController');
    Route::resource('collection', 'UserCardController');
    Route::get('users/search', 'UserController@search');
    Route::get('/users/{id}/search', 'UserCardController@search');
    Route::resource('users','UserController');



  });

Route::group([
    'middleware' => 'roles',
    'role' => 'Administrator'
],function() {

    Route::get('/cards/create/{id}', 'CardController@createFromProposition')->name('createFromProposition');
    Route::get('cards/search', 'CardController@search');
    Route::resource('cards', 'CardController');
});

Auth::routes();

Route::redirect('/home', '/collection');
