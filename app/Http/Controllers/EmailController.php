<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;

class EmailController extends Controller
{
  public function adsReceipt(Request $request) {
    if(!isset($request->type)) {
      $type = 'Morning Chalk Up Sponsored Link';
    } else {
      $type = $request->type;
    }
    $data = array(
      'name' => $request->user['name'],
      'email' => $request->user['email'],
      'type' => $type,
      'order' => $request->transaction,
      'items' => $request->items,
      'total' => $request->total,
      'balance' => $request->total - $request->paid,
      'date' => Carbon::today()->toFormattedDateString(),
    );

    Mail::send('emails.receipt', array('data' => $data), function($message) use ($data) {
      $message->from('ads@morningchalkup.com', 'Morning Chalk Up Ads');
      $message->to($data['email'], $data['name']);
      $message->subject("Your {$data['type']} -- Order {$data['order']}");
    });

    if(!isset($request->send_admin) || $request->send_admin) {
      Mail::send('emails.receipt', array('data' => $data), function($message) use ($data) {
        $message->from('info@morningchalkup.com', 'Morning Chalk Up');
        $message->to(['ads@morningchalkup.com', 'mat@morningchalkup.com']);
        $message->subject("New {$data['type']} -- {$data['order']}");
      });

    }

    return 1;
  }

  public function copyReminder(Request $request) {
    $data = array(
      'name' => $request->user['name'],
      'email' => $request->user['email'],
      'week_id' => $request->week_id,
      'ad_date' => (new Carbon($request->ad_date))->format('F jS, Y'),
      'ad_deadline' => (new Carbon($request->ad_date))->subDays(5)->format('l, F jS, Y'),
      'date' => Carbon::today()->toFormattedDateString(),
    );

    Mail::send('emails.copyReminder', array('data' => $data), function($message) use ($data) {
      $message->from('ads@morningchalkup.com', 'Morning Chalk Up Ads');
      $message->to($data['email'], $data['name']);
      $message->subject("Remember to Write Your Ads for Morning Chalk Up");
    });
    return 1;
  }

  public function linkInfo(Request $request) {
    $data = array(
      'name' => $request->user['name'],
      'email' => $request->user['email'],
      'ad_date' => (new Carbon($request->ad_date))->format('l, F jS'),
    );

    Mail::send('emails.link', array('data' => $data), function($message) use ($data) {
      $message->from('info@morningchalkup.com', 'Morning Chalk Up');
      $message->to($data['email'], $data['name']);
      $message->cc('ads@morningchalkup.com');
      $message->replyTo('ads@morningchalkup.com');
      $message->subject("Sponsored Link -- {$data['ad_date']}");
    });
    return 1;

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
      $message->from('ads@morningchalkup.com', 'Morning Chalk Up Ads');
      $message->to($data['email'], $data['name']);
      $message->subject("Please complete your payment");
    });
    return 1;

  }

  public function test(Request $request) {
    // $url = $request->getSchemeAndHttpHost() . '/api/ads/receipt';
    // $url = 'http://data.morningchalkup.com/api/ads/reminder/copy';
    $url = 'http://data.morningchalkup.com/api/ads/reminder/link';
    $data = array(
      'user' => array(
        'email' => 'eric@ericsherred.com',
        'name' => 'Eric Sherred'
      ),
      'week_id' => 457,
      'ad_date' => "01/07/2019",
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
