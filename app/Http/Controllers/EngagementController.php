<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CampaignMonitor;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;

class EngagementController extends Controller
{
  public function getUser() {
    $return = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_lists();

    foreach ($return->response as $list) {
      if ($list->Name == 'The Morning Chalk Up') {
        $list_id = $list->ListID;
      }
    }

    $emails = DB::connection('mysql')->table('cu_yahoo')->select('email')->get();

      // dd($emails);

    foreach ($emails as $email) {
      $user = CampaignMonitor::subscribers($list_id)->get_history($email->email);

      $active = false;
      
      // if not recent subscriber (last 3 weeks-ish)
      if (is_array($user->response) && count($user->response) >= 15) {

        $count = 0;

        foreach ($user->response as $history) {
        
          if ($history->Actions != []) {

            foreach ($history->Actions as $action) {
              if (new Carbon('-60 days') <= $action->Date) {
                $active = true;
                break(2);
              }
            }

          }

          // Only check the past 15 emails
          ++$count; if ($count > 60) break;
        
        }

      }

      DB::connection('mysql')->table('cu_yahoo')->where('email', $email->email)->update(['is_engaged' => $active]);

      // dd([$active, $email]);

    }

    dd('done');

  }

  public function getEmails() {
    $return = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_lists();

    $emails = array();

    Storage::delete('engagement.csv');
    Storage::disk('local')->append('engagement.csv', 'email,active date');


    foreach ($return->response as $list) {
      if ($list->Name == 'The Morning Chalk Up') {
        $list_id = $list->ListID;
      }
    }
    $page = 1;

    do {
      $r = CampaignMonitor::lists($list_id)->get_active_subscribers(date('Y-m-d', strtotime('-5 years')), $page, 10, 'email', 'asc');

      foreach($r->response->Results as $user) {
        $history = CampaignMonitor::subscribers($list_id)->get_history($user->EmailAddress);
        $emails[$user->EmailAddress] = $user->Date;
        foreach($history->response as $email) {
          if(isset($email->Actions[0]) && strtotime($emails[$user->EmailAddress]) < strtotime($email->Actions[0]->Date)) {
            $emails[$user->EmailAddress] = $email->Actions[0]->Date;
          }
        }
        Storage::disk('local')->append('engagement.csv', $user->EmailAddress . ',' . $emails[$user->EmailAddress]);
      }
      ++$page;
    } while($page <= $r->response->NumberOfPages && $page < 2);

    dd($emails);
  }
}
