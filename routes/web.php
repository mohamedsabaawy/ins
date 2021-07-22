<?php

use Illuminate\Support\Facades\Route;

define('AVATAR','ph.png');
define('PAGINATE' ,50);

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

Route::get('/', 'FrontEnd\PostController@index')->name('welcome')->middleware('auth:client');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// client route
Route::group(['namespace'=>'FrontEnd'],function (){
   Route::get('register','ClientController@registerForm')->name('client.register.form')->middleware('guest:client') ;
   Route::post('register','ClientController@register')->name('client.register') ;
   Route::get('login','ClientController@loginForm')->name('client.login.form')->middleware('guest:client') ;
   Route::post('login','ClientController@login')->name('client.login') ;
   Route::post('logout','ClientController@logout')->name('client.logout') ;

   //--------------------------------------------------------------//

   Route::group(['middleware' =>'auth:client'],function (){
       Route::post('edit/{id}','ClientController@edit')->name('client.edit') ;
       Route::get('/edit',function (){
           return view('frontend.client.edit');
       })->name('client.edit.form') ;
       Route::get('search','ClientController@search')->name('client.search');
       Route::get('follow','ClientController@follow')->name('client.follow');
       Route::get('profile/{client}','ClientController@profile')->name('client.profile');
   });
});


//----------------post route--------------------//
Route::group(['namespace'=>'FrontEnd','middleware'=>'auth:client'],function (){
    Route::resource('post','PostController');
    Route::get('like','PostController@like')->name('post.like');
    Route::get('comment','PostController@comment')->name('post.comment');
    Route::get('share/{id}','PostController@share')->name('post.share');
});
