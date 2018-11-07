<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;

class EmailController extends Controller
{
  public function adsReceipt(Request $request) {

    $data = array(
      'name' => $request->user['name'],
      'email' => $request->user['email'],
      'order' => $request->transaction,
      'items' => $request->items,
      'total' => $request->total,
      'balance' => $request->total - $request->paid,
      'date' => Carbon::today()->toFormattedDateString(),
    );

    Mail::send('emails.receipt', array('data' => $data), function($message) use ($data) {
      $message->from('info@mail.morningchalkup.com', 'Morning Chalk Up');
      $message->to($data['email'], $data['name']);
      $message->subject("Your Morning Chalk Up Sponsorship -- Order {$data['order']}");
    });

    Mail::send('emails.receipt', array('data' => $data), function($message) use ($data) {
      $message->from('info@mail.morningchalkup.com', 'Morning Chalk Up');
      $message->to('partners@morningchalkup.com', 'Morning Chalk Up Partners');
      $message->subject("New Sponsorship Order -- {$data['order']}");
    });

    return 1;
  }

  public function test(Request $request) {
    // $url = $request->getSchemeAndHttpHost() . '/api/ads/receipt';
    $url = 'http://data.morningchalkup.com/api/ads/receipt';
    $data = array(
      'user' => array(
        'email' => 'eric@morningchalkup.com',
        'name' => 'Eric Sherred'
      ),
      'transaction' => 123,
      'total' => 5250,
      'paid' => 1050,
      // 'paid' => 5250,
      'items' => array(
        array(
          'id' => 123,
          'start' => '10/2/18',
          'end' => '10/5/18',
          'facebook' => 'true',
          'ab' => 'true',
          'wewrite' => 'false',
          'cost' => 2600,
        ),
        array(
          'id' => 125,
          'start' => '11/2/18',
          'end' => '11/5/18',
          'facebook' => 'false',
          'ab' => 'false',
          'wewrite' => 'true',
          'cost' => 2350,
        ),
      ),
    );

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
