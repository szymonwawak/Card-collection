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

    Route::resource('propositions', 'CardPropositionController');
    Route::resource('cards', 'CardController');
  });

Route::group([
    'middleware' => 'roles',
    'role' => 'Administrator'
],function() {

    Route::get('/cards/create/{id}', 'CardController@createFromProposition')->name('createFromProposition');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
