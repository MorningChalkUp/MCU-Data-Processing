<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CampaignMonitor;
use Carbon\Carbon;
use DB;

class EngagementController extends Controller
{
  public function getUser() {
    $return = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_lists();

    foreach ($return->response as $list) {
      if ($list->Name == 'The Morning Chalk Up') {
        $list_id = $list->ListID;
      }
    }

    $emails = DB::connection('mysql')->table('cu_people')->select('email', 'pid')->get();

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
              if (new Carbon('-14 days') <= $action->Date) {
                $active = true;
                break(2);
              }
            }

          }

          // Only check the past 15 emails
          ++$count; if ($count > 15) break;
        
        }

      }

      DB::connection('mysql')->table('cu_people')->where('pid', $email->pid)->update(['is_engaged' => $active]);

      // dd([$active, $email]);

    }

    dd('done');

  }
}
