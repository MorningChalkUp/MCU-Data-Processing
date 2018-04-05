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
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

Route::get('/', function () {
    return view('pages.welcome');
});

Route::get('guzzle', function() {
  $client = new Client();

  // $resp = new Request('GET', 'https://games.crossfit.com/competitions/api/v1/competitions/open/2018/leaderboards?division=1&region=0&scaled=0&sort=0&occupation=0&page=1');

  $resp = $client->get('https://games.crossfit.com/competitions/api/v1/competitions/open/2018/leaderboards?division=1&region=0&scaled=0&sort=0&occupation=0&page=1');

  dd($resp);
});

Route::get('/email', 'EmailReportController@campaignList');

Route::get('/email/all', 'EmailReportController@allStats');
Route::get('/email/ave', 'EmailReportController@recentAve');
Route::get('/email/ads', 'EmailReportController@updateAdsData');

Route::get('/hash/emails', 'HashController@hashEmails');

Route::get('update', 'CMController@updateAds');

Route::get('/run/spider', 'CMController@updateDB');

Route::get('/open/data', 'AthleteController@getData');

// Variable Based Routes
Route::get('/email/{id}', 'EmailReportController@emailStats');
