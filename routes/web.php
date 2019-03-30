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

Route::resource('contacts', 'ContactController');
Route::get('contacts-datatable', 'ContactController@contactsDatatable')->name('datatable_contacts');
Route::post('check-email', 'ContactController@checkEmail')->name('check.email');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
