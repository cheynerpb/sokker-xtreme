<?php

use Illuminate\Support\Facades\Route;
use App\ContestEdition;

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

Route::get('json', function(){
    try {
        dd(ContestEdition::with('players')->get()->toArray());
        $var = file_put_contents('players.json', ContestEdition::where('active', true)->with('players')->get()->toJson());
        \Illuminate\Support\Facades\Storage::put('players.json', $var);
    }catch (Throwable $e) {
        dd($e->getMessage());
    }
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/update-xtreme', function(){
    Artisan::call('update:xtreme');
});

Route::group(['as' => 'system.', 'prefix' => 'system'], function () {
    Route::get('login', 'Auth\SystemUserAuthController@showLoginForm')->name('login.form');

    Route::post('login', 'Auth\SystemUserAuthController@login')->name('login.post');

    Route::get('logout', 'Auth\SystemUserAuthController@logout')->name('logout');

    Route::get('register', 'Auth\SystemUserAuthController@showRegistrationForm')->name('register.form');

    Route::post('register', 'Auth\SystemUserAuthController@register')->name('register.save');
});

Route::group([
    'middleware' => 'system.logged'
], function () {
    Route::post('/sokker-login', 'Auth\LoginController@sokker_login')->name('sokker_login');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/insert-player', 'PlayerUpdateController@show_insert')->name('insert_player');

    Route::post('/send-data', 'PlayerUpdateController@store')->name('store_player');
    Route::post('/send-id', 'PlayerUpdateController@storeByIDApi')->name('store_player_by_id');
    Route::post('/send-all', 'PlayerUpdateController@update_all')->name('update_all');

    Route::post('/send-sokkercuba', 'PlayerUpdateController@update_sokkercuba')->name('update_sokkercuba');

    Route::delete('/delete-player/{id}', 'PlayerUpdateController@destroy')->name('delete_player');
    Route::delete('/delete-all/{sokker_id}', 'PlayerUpdateController@delete_all')->name('delete_all');


    Route::post('/change-active/{sokker_id}', 'PlayerUpdateController@change_active')->name('change_active');
    Route::post('/active-user/{id}', 'SystemUserController@active_user')->name('active_user');


    Route::get('/form', 'DownloadController@show_form')->name('show_form');
    Route::post('/download-data', 'DownloadController@download_data')->name('download_data');

    Route::post('/download-players', 'DownloadController@download_players')->name('download_players');

    Route::resource('editions', 'EditionController');
    Route::resource('system_users', 'SystemUserController');

    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

});

Route::get('/ranking/{id?}', 'PlayerUpdateController@show_ranking')->name('show_ranking');

Route::get('/show-player/{sokker_id}', 'PlayerUpdateController@show_player')->name('show_player');
Route::get('/reference-table', 'PlayerUpdateController@reference_table')->name('reference_table');


