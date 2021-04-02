<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/sokker-login', 'Auth\LoginController@sokker_login')->name('sokker_login');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/insert-player', 'PlayerUpdateController@show_insert')->name('insert_player');
Route::get('/ranking', 'PlayerUpdateController@show_ranking')->name('show_ranking');

Route::post('/send-data', 'PlayerUpdateController@store')->name('store_player');
Route::post('/send-id', 'PlayerUpdateController@store_by_id')->name('store_player_by_id');
Route::post('/send-all', 'PlayerUpdateController@update_all')->name('update_all');


Route::delete('/delete-player/{id}', 'PlayerUpdateController@destroy')->name('delete_player');
Route::get('/show-player/{sokker_id}', 'PlayerUpdateController@show_player')->name('show_player');
Route::get('/reference-table', 'PlayerUpdateController@reference_table')->name('reference_table');
Route::delete('/delete-all/{sokker_id}', 'PlayerUpdateController@delete_all')->name('delete_all');

Route::get('/form', 'DownloadController@show_form')->name('show_form');
Route::post('/download-data', 'DownloadController@download_data')->name('download_data');

Route::post('/download-players', 'DownloadController@download_players')->name('download_players');

Route::resource('editions', 'EditionController');
