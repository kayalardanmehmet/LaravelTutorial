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

Route::group(['middleware' => 'checklogin'], function () {

    Route::get('/', function () {
        $posts = \App\Post::orderBy('id', 'desc')->get();
        return view('home', ['posts' => $posts]);
    });

    //posts istekleri
    Route::get('/posts', 'PostController@index');
    Route::get('/posts/new', 'PostController@getNew');
    Route::post('/posts/new', 'PostController@postNew');
    Route::get('/posts/{id}', 'PostController@view');

    //answera ait istekler
    Route::post('/answers/new/{id}', 'PostController@postAnswer');

    //vote işlemleri için gereken istek
    Route::get('/vote/{id}/{type}/{vote}', 'PostController@vote');

});

Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/logout', 'UserController@logout');

Route::post('/register', 'UserController@doRegister');
Route::post('/login', 'UserController@doLogin');
