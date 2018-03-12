<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CampaignMonitor;

class EmailDataController extends Controller
{
  public function getEmailData($id, Request $request) {

    $query = $request->all();

    $ad_clicks = 0;
    $ad_links = array();

    $clicks = CampaignMonitor::campaigns($id)->get_clicks();

    if (isset($query['url']) && $query['url'] != []) {
      
      $domains = $query['url'];
      $pages = $clicks->response->NumberOfPages;
      $currentPage = 1;

      do {
        if ($currentPage != 1) {
          $clicks = CampaignMonitor::campaigns($id)->get_clicks('', $currentPage);
        }

        foreach ($clicks->response->Results as $click) {
          foreach($domains as $domain) {
            if (strpos(strtolower($click->URL), strtolower($domain)) !== false) {
              ++$ad_clicks;
              if (isset($ad_links[$click->URL])) {
                ++$ad_links[$click->URL];
              } else {
                $ad_links[$click->URL] = 1;
              }
            }
          }
        }

        ++$currentPage;
      } while ($currentPage <= $pages);
    }

    $campaign = CampaignMonitor::campaigns($id)->get_summary();

    $campaigns = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_campaigns();

    foreach ($campaigns->response as $item) {
      if ($item->CampaignID == $id) {
        $title = $item->Name;
        $subject = $item->Subject;
        break;
      }
    }

    $data = array(
      'recipients' => $campaign->response->Recipients,
      'opens' => $campaign->response->UniqueOpened,
      'clicks_unique' => $campaign->response->Clicks,
      'clicks_total' => $clicks->response->TotalNumberOfRecords,
      'ad_clicks' => $ad_clicks,
      'ad_links' => $ad_links,
      'title' => $title,
      'subject' => $subject,
      'web_view' => $campaign->response->WebVersionURL,
    );

    return array('response' => $data, 'status' => 200);
  }
}
