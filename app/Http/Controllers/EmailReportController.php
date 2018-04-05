<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CampaignMonitor;
use DB;

class EmailReportController extends Controller
{
    public function campaignList() {
      $return = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_campaigns();

      return view('pages.campaign-list')->with('campaigns', $return->response);
    }

    public function emailStats($id) {
      $url = 'http://data.morningchalkup.com/api/v1/email/'.$id;

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

    public function updateAdsData() {

      $campaigns = DB::connection('analytics')->table('wp_postmeta')
        ->where('meta_key', 'campaign_id')->get();

      foreach ($campaigns as $campaign) {
        $post_date = DB::connection('analytics')
          ->table('wp_posts')
          ->select('post_date')
          ->where('ID', $campaign->post_id)
          ->get();

        if ( strtotime($post_date[0]->post_date) >= strtotime('-7 days')) {
          $data[$campaign->post_id]['campaign_id'] = $campaign->meta_value;
          $post_list[] = $campaign->post_id;
        }
      }

      $url_count = DB::connection('analytics')
        ->table('wp_postmeta')
        ->whereIn('post_id', $post_list)
        ->where('meta_key', 'ad_urls')
        ->get();

      foreach ($url_count as $count) {
        for($i = 0; $i < $count->meta_value; ++$i) {
          $meta_key = 'ad_urls_' . $i . '_url';
          $url = DB::connection('analytics')
            ->table('wp_postmeta')
            ->select('meta_value')
            ->where('meta_key', $meta_key)
            ->where('post_id', $count->post_id)
            ->get();

          $data[$count->post_id]['urls'][] = $url[0]->meta_value;
        }
      }

      foreach ($data as $post => $value) {

        $url = 'http://data.morningchalkup.com/api/v1/email/simple/'.$value['campaign_id'];

        $query = '';

        foreach ($value['urls'] as $ad_url) {
          $query .= 'url[]=' . $ad_url . '&';
        }

        $url .= '?' . substr($query, 0, -1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $result = $result['response'];

        $url = DB::connection('analytics')
          ->table('wp_postmeta')
          ->select('meta_value')
          ->where('meta_key', 'recipients')
          ->where('post_id', $post)
          ->update(['meta_value' => $result['recipients']]);

        $url = DB::connection('analytics')
          ->table('wp_postmeta')
          ->select('meta_value')
          ->where('meta_key', 'opens')
          ->where('post_id', $post)
          ->update(['meta_value' => $result['opens']]);

        $url = DB::connection('analytics')
          ->table('wp_postmeta')
          ->select('meta_value')
          ->where('meta_key', 'ad_clicks')
          ->where('post_id', $post)
          ->update(['meta_value' => $result['ad_clicks']]);

      }

    }

    public function updateAllAdsData() {

      $campaigns = DB::connection('analytics')->table('wp_postmeta')
        ->where('meta_key', 'campaign_id')->get();

      foreach ($campaigns as $campaign) {
        $data[$campaign->post_id]['campaign_id'] = $campaign->meta_value;
      }

      $url_count = DB::connection('analytics')->table('wp_postmeta')
        ->where('meta_key', 'ad_urls')->get();

      foreach ($url_count as $count) {
        for($i = 0; $i < $count->meta_value; ++$i) {
          $meta_key = 'ad_urls_' . $i . '_url';
          $url = DB::connection('analytics')
            ->table('wp_postmeta')
            ->select('meta_value')
            ->where('meta_key', $meta_key)
            ->where('post_id', $count->post_id)
            ->get();

          $data[$count->post_id]['urls'][] = $url[0]->meta_value;
        }
      }

      foreach ($data as $post => $value) {

        $url = 'http://data.morningchalkup.com/api/v1/email/simple/'.$value['campaign_id'];

        $query = '';

        foreach ($value['urls'] as $ad_url) {
          $query .= 'url[]=' . $ad_url . '&';
        }

        $url .= '?' . substr($query, 0, -1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $result = $result['response'];

        $url = DB::connection('analytics')
          ->table('wp_postmeta')
          ->select('meta_value')
          ->where('meta_key', 'recipients')
          ->where('post_id', $post)
          ->update(['meta_value' => $result['recipients']]);

        $url = DB::connection('analytics')
          ->table('wp_postmeta')
          ->select('meta_value')
          ->where('meta_key', 'opens')
          ->where('post_id', $post)
          ->update(['meta_value' => $result['opens']]);

        $url = DB::connection('analytics')
          ->table('wp_postmeta')
          ->select('meta_value')
          ->where('meta_key', 'ad_clicks')
          ->where('post_id', $post)
          ->update(['meta_value' => $result['ad_clicks']]);

      }

    }
}
