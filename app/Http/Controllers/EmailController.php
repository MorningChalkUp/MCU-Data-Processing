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

    if(!isset($request->send_admin) || $request->send_admin) {
      Mail::send('emails.receipt', array('data' => $data), function($message) use ($data) {
        $message->from('info@mail.morningchalkup.com', 'Morning Chalk Up');
        $message->to('partners@morningchalkup.com', 'Morning Chalk Up Partners');
        $message->subject("New Sponsorship Order -- {$data['order']}");
      });
    }

    return 1;
  }

  public function copyReminder(Request $request) {
    $data = array(
      'name' => $request->user['name'],
      'email' => $request->user['email'],
      'week_id' => $request->week_id,
      'ad_date' => Carbon::createFromTimeString()->format('F Y jS'),
      'ad_deadline' => Carbon::createFromTimeString()->sub('5 days')->format('l F Y j'),
      'date' => Carbon::today()->toFormattedDateString(),
    );

    Mail::send('emails.copyReminder', array('data' => $data), function($message) use ($data) {
      $message->from('info@mail.morningchalkup.com', 'Morning Chalk Up');
      $message->to($data['email'], $data['name']);
      $message->subject("Remember to Write Your Ads for Morning Chalk Up");
    });
  }

  public function paymentReminder(Request $request) {
    $data = array(
      'name' => $request->user['name'],
      'email' => $request->user['email'],
      'order_id' => $request->order_id,
      'ad_date' => $request->ad_date,
      'date' => Carbon::today()->toFormattedDateString(),
    );

    Mail::send('emails.paymentReminder', array('data' => $data), function($message) use ($data) {
      $message->from('info@mail.morningchalkup.com', 'Morning Chalk Up');
      $message->to($data['email'], $data['name']);
      $message->subject("Please complete your payment");
    });
  }

  public function test(Request $request) {
    // $url = $request->getSchemeAndHttpHost() . '/api/ads/receipt';
    $url = 'http://data.morningchalkup.com/api/ads/reminder/copy';
    $data = array(
      'user' => array(
        'email' => 'eric@morningchalkup.com',
        'name' => 'Eric Sherred'
      ),
      'week_id' => 457,
      'ad_date' => "01/02/2019",
      'order_id' => 450,
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
