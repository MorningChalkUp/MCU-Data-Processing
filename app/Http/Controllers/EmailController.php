<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
  public function adsReceipt(Request $request) {

    $data = array(
      'name' => $request->user['name'],
      'order' => $request->transaction,
      'cart' => $request->cart
    );

    Mail::send('emails.receipt', array('data' => $data), function($message) use ($request) {
      $message->from('info@mail.morningchalkup.com', 'Morning Chalk Up');
      $message->to($request->user['email'], request->user['name']);
      $message->subject('Thank you for your order');
    });

    return 1;
  }

  public function test() {
    $url = 'http://mcu-data.test/api/ads/receipt';
    $data = array('email' => 'eric@morningchalkup.com');

    $query = http_build_query($data);

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($data));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $query);

    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    var_dump($result);
  }
}
