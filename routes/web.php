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

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::resource('matters','MatterController');
Route::resource('todos','TodoController');
Route::resource('steps','StepController');
Route::resource('users','UserController');
Route::resource('products','ProductController');
Route::patch('products/urlupdate/{id}','ProductController@updateUrl')->name('products.updateUrl');
Route::resource('comments','CommentController');
// Routingをした

