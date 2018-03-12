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
    return view('pages.welcome');
});

Route::get('/email', 'EmailReportController@campaignList');

Route::get('/email/all', 'EmailReportController@allStats');
Route::get('/email/ave', 'EmailReportController@recentAve');

Route::get('/email/{id}', 'EmailReportController@emailStats');
