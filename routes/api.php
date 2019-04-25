<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('v1/email/simple/{id}', 'EmailDataController@getSimpleEmailData');

Route::get('v1/email/{id}', 'EmailDataController@getEmailData');

Route::post('/ads/receipt', 'EmailController@adsReceipt');
Route::post('/ads/reminder/copy', 'EmailController@copyReminder');
Route::post('/ads/reminder/payment', 'EmailController@paymentReminder');

Route::post('/subscribe/bug', 'SubscriberController@subscribeUserBug');