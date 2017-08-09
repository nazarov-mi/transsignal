<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::get('/', function () {
	return view('welcome');
});

/**
 * Routes for resource user
 */
Route::post('user/login', 'UsersController@login');
Route::get('user/logout', 'UsersController@logout');
Route::get('user/check', 'UsersController@check');

Route::get('user', 'UsersController@all');
Route::get('user/{id}', 'UsersController@get');
Route::post('user', 'UsersController@add');
Route::put('user/{id}', 'UsersController@put');
Route::delete('user/{id}', 'UsersController@remove');

/**
 * Routes for resource category
 */
Route::get('category', 'CategoriesController@all');
Route::get('category/{id}', 'CategoriesController@get');
Route::post('category', 'CategoriesController@add');
Route::put('category/{id}', 'CategoriesController@put');
Route::delete('category/{id}', 'CategoriesController@remove');
Route::get('category/{id}/pictures', 'CategoriesController@pictureAll');
Route::post('category/{id}/pictures', 'CategoriesController@pictureAdd');
Route::get('category/{id}/products', 'CategoriesController@productAll');

/**
 * Routes for resource product
 */
Route::get('product', 'ProductsController@all');
Route::get('product/{id}', 'ProductsController@get');
Route::post('product', 'ProductsController@add');
Route::put('product/{id}', 'ProductsController@put');
Route::delete('product/{id}', 'ProductsController@remove');
Route::get('product/{id}/pictures', 'ProductsController@pictureAll');
Route::post('product/{id}/pictures', 'ProductsController@pictureAdd');
Route::get('product/{id}/categories', 'ProductsController@categoryAll');

/**
 * Routes for resource picture
 */
Route::get('picture', 'PicturesController@all');
Route::get('picture/{id}', 'PicturesController@get');
Route::post('picture', 'PicturesController@add');
Route::put('picture/{id}', 'PicturesController@put');
Route::delete('picture/{id}', 'PicturesController@remove');
