<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CampaignMonitor;

class EmailReportController extends Controller
{
    public function campaignList() {
      $return = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_campaigns();

      return view('pages.campaign-list')->with('campaigns', $return->response);
    }

    public function emailStats($id) {
      $url = 'http://data.morningchalkup.com/api/email/'.$id;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      $result = json_decode(curl_exec($ch), true);
      curl_close($ch);

      $data = $result['response'];
      
      $data['id'] = $id;
      $data['open_rate'] = number_format($data['opens']/$data['recipients']*100, 2);
      $data['click_rate'] = number_format($data['clicks_unique']/$data['opens']*100, 2);
      $data['recipients'] = number_format($data['recipients']);
      $data['opens'] = number_format($data['opens']);
      $data['clicks_unique'] = number_format($data['clicks_unique']);
      $data['clicks_total'] = number_format($data['clicks_total']);

      return view('pages.email')->with('data', $data);
    }

    public function allStats() {
      $campaigns = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_campaigns();

      foreach ($campaigns->response as $item) {
        if ( strtotime($item->SentDate) >= strtotime('-30 days')) {
          $campaign = CampaignMonitor::campaigns($item->CampaignID)->get_summary();
          $clicks = CampaignMonitor::campaigns($item->CampaignID)->get_clicks();
          $data['name'] = $item->Name;
          $data['id'] = $item->CampaignID;
          $data['sendDate'] = $item->SentDate;
          $data['recipiants'] = number_format($campaign->response->Recipients);
          $data['opens'] = number_format($campaign->response->UniqueOpened);
          $data['open_rate'] = number_format($campaign->response->UniqueOpened/$campaign->response->Recipients*100, 2);
          $data['clicks'] = number_format($campaign->response->Clicks);
          $data['click_rate'] = number_format($campaign->response->Clicks/$campaign->response->UniqueOpened*100, 2);
          $data['total_clicks'] = number_format($clicks->response->TotalNumberOfRecords);

          $all[] = $data;
        }
      }

      return view('pages.all_email')->with('campaigns', $all);
    }

    public function recentAve() {
      $campaigns = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_campaigns();

      $data['recipiants'] = 0;
      $data['opens'] = 0;
      $data['clicks'] = 0;
      $data['total_clicks'] = 0;

      foreach ($campaigns->response as $item) {
        if ( strtotime($item->SentDate) >= strtotime('january 1, 2018')) {
          $campaign = CampaignMonitor::campaigns($item->CampaignID)->get_summary();
          $clicks = CampaignMonitor::campaigns($item->CampaignID)->get_clicks();
          $data['recipiants'] += $campaign->response->Recipients;
          $data['opens'] += $campaign->response->UniqueOpened;
          $data['clicks'] += $campaign->response->Clicks;
          $data['total_clicks'] += $clicks->response->TotalNumberOfRecords;
        }
      }

      $data['open_rate'] = number_format($data['opens']/$data['recipiants']*100, 2);
      $data['click_rate'] = number_format($data['clicks']/$data['opens']*100, 2);

      $data['recipiants'] = number_format($data['recipiants']);
      $data['opens'] = number_format($data['opens']);
      $data['clicks'] = number_format($data['clicks']);
      $data['total_clicks'] = number_format($data['total_clicks']);

      return view('pages.email-ave')->with('data', $data);
    }
}
