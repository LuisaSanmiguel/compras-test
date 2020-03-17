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

//  Route::get('/{any}', 'SpaController@index')->where('except', '/,'login','register');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/products', function(){
	return view('products');
});

Route::get('/purchases', function(){
	return view('purchases');
});


Route::get('/suppliers', function(){
	return view('suppliers');
});
// Route::resource('/purchaseDetails', 'purchaseDetailController');

