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

Route::get('/ads/test', 'EmailController@test');

Route::get('/c', 'AffiliateController@checkEmail');

Route::get('affiliates', 'AffiliateController@getHQList');
Route::get('affiliates/top', 'AffiliateController@topCountries');

Route::get('cm', 'EngagementController@getUser');

Route::get('/email', 'EmailReportController@campaignList');

Route::get('/email/all', 'EmailReportController@allStats');
Route::get('/email/ave', 'EmailReportController@recentAve');
Route::get('/email/ads', 'EmailReportController@updateAdsData');
Route::get('/email/ads/all', 'EmailReportController@updateAllAdsData');

Route::get('/hash/emails', 'HashController@hashEmails');

Route::get('update', 'CMController@updateAds');

Route::get('/run/spider', 'CMController@updateDB');

Route::get('/games/open/data', 'AthleteController@getData');
Route::get('/games/open/update', 'AthleteController@updateRegion');

Route::get('/games/agoq', 'AthleteController@getDataAGOQ');

Route::get('/games/r/e1', 'AthleteController@regionE1');
Route::get('/games/r/o', 'AthleteController@rOverall');

Route::get('/games/athlete/{id}', 'AthleteController@athleteProfile');

// Variable Based Routes
Route::get('/email/{id}', 'EmailReportController@emailStats');
