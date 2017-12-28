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

Route::get('/', 'IndexController@index');

Route::get('/admin', 'AdminController@index');

/*
 * Admin Section
 */
Route::get('/admin/users', 'AdminController@show');
Route::post('/admin/users', 'AdminController@store');

Route::get('/admin/users/create', 'AdminController@create');


Route::get('/admin/assets', 'AssetController@index');
Route::get('/admin/asset/create', 'AssetController@create');