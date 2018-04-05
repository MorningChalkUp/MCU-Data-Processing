<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;

class AthleteController extends Controller
{
  public function getData() {

    /*$options = array(
            'division' => 1,
            'region' => 0,
            'scaled' => 0,
            'sort' => 0,
            'occupation' => 0,
            'page' => 1,
          );
    $client = new Client();
    $resp = $client->request('GET', 'https://games.crossfit.com/competitions/api/v1/competitions/open/2018/leaderboards?division=1&region=0&scaled=0&sort=0&occupation=0&page=1');

    dd($resp);*/

    $events = array(
      '18.1' => 0,
      '18.2' => 1,
      '18.2a' => 2,
      '18.3' => 3,
      '18.4' => 4,
      '18.5' => 5,
      // 'total' => 6,
    );

    $groups = array(
      'M' => 1,
      // 'W' => 2,
      /*'M1415' => 14,
      'W1415' => 15,
      'M1617' => 16,
      'W1617' => 17,
      'M3539' => 18,
      'W3539' => 19,
      'M4044' => 12,
      'W4044' => 13,
      'M4549' => 3,
      'W4549' => 4,
      'M5054' => 5,
      'W5054' =>6,
      'M5559' => 7,
      'W5559' => 8,
      'M60' => 9,
      'W60' => 10,*/
    );

    $scales = array(
      'Rx' => 0,
      'Scaled' => 1,
      'Total' => 2,
    );

    $data = array();
    $athletes = array();

    /*foreach($groups as $group => $groupvalue) {
      foreach($events as $event => $eventvalue) {
        foreach($scales as $scale => $scalevalue) {
          $data[$group][$event][$scale] = 0;
        }
      }
    }*/

    foreach ($groups as $group => $groupvalue) {
      foreach($scales as $scale => $scalevalue) {
        $page = 1;
        // $page = 1001;
        // $page = 2001;
        // $page = 3001;
        // $page = 4001;
        do {
          // $total_pages = 1;
          // $total_pages = 1000;
          // $total_pages = 2000;
          // $total_pages = 3000;
          // $total_pages = 4000;

          // todo 4214 M Rx
          // todo 473 M Scale

          $options = array(
            'division' => 2,
            'region' => '0',
            // 'scaled' => 0,
            'scaled' => 1,
            'sort' => '0',
            'occupation' => '0',
            'page' => $page,
          );
          $option_list = array();
          foreach ($options as $key => $value) {
            $option_list[] = $key . '=' . $value;
          }

          $query = implode('&', $option_list);

          $url = 'https://games.crossfit.com/competitions/api/v1/competitions/open/2018/leaderboards?' . $query;

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_URL, $url);
          $result = json_decode(curl_exec($ch), true);
          curl_close($ch);

          // dd($result);
          // dd($url);

          $total_pages = $result['pagination']['totalPages'];
          // $total_pages = 200;
          if ($result != null && isset($result['leaderboardRows'])) {

            foreach ($result['leaderboardRows'] as $count => $athlete) {
              $data['competitorId'] = intval($athlete['entrant']['competitorId']);
              $data['competitorName'] = $athlete['entrant']['competitorName'];
              $data['firstName'] = $athlete['entrant']['firstName'];
              $data['lastName'] = $athlete['entrant']['lastName'];
              $data['gender'] = $athlete['entrant']['gender'];
              // $data['countryShortCode'] = $athlete['entrant']['countryShortCode'];
              $data['regionalCode'] = intval($athlete['entrant']['regionalCode']);
              $data['regionId'] = intval($athlete['entrant']['regionId']);
              $data['regionName'] = $athlete['entrant']['regionName'];
              $data['divisionId'] = intval($athlete['entrant']['divisionId']);
              $data['affiliateId'] = intval($athlete['entrant']['affiliateId']);
              $data['affiliateName'] = $athlete['entrant']['affiliateName'];
              $data['age'] = intval($athlete['entrant']['age']);
              $data['height'] = $athlete['entrant']['height'];
              $data['weight'] = $athlete['entrant']['weight'];
              $data['scaled_athlete'] = $options['scaled'];
              $data['overall_rank'] = (($page - 1) * 50) + ($count + 1);

              foreach ($events as $event => $eventvalue) {
                $wod = 'wod' . ($eventvalue + 1);
                $data[$wod . '_score'] = intval($athlete['scores'][$eventvalue]['score']);
                $data[$wod . '_rank'] = intval($athlete['scores'][$eventvalue]['rank']);
                $data[$wod . '_scaled'] = intval($athlete['scores'][$eventvalue]['scaled']);
              }

              // dd($data);

              DB::table('cf_open2018')->insert([$data]);

              // $athletes[] = $data;


            //   $scaled_events = 0;
            //   $rx_events = 0;
            //   foreach ($events as $event => $eventvalue) {
            //     if ($eventvalue != 6 && $athlete['scores'][$eventvalue]['score'] != '0') {
            //       if ($athlete['scores'][$eventvalue]['scaled'] == '0') {
            //         $data[$group][$event]['Rx'] += 1;
            //         $rx_events += 1;
            //       } else {
            //         $data[$group][$event]['Scaled'] += 1;
            //         $scaled_events += 1;
            //       }
            //     }

            //     if($eventvalue == 6) {
            //       if ($rx_events == 6) {
            //         $data[$group][$event]['Rx'] += 1;
            //       } elseif ($scaled_events == 6) {
            //         $data[$group][$event]['Scaled'] += 1;
            //       }
            //       if (($rx_events + $scaled_events) == 6) {
            //         $data[$group][$event]['Total'] += 1;
            //       }
            //     }
            //   }
            }
          }

          ++$page;
        } while ($page <= $total_pages);

        // DB::table('open2018')->insert($athletes);

        dd('done');
        echo '<table><tr>';
          echo '<td>' . $data['M']['18.1']['Rx'] . '</td>';
          echo '<td>' . $data['M']['18.2']['Rx'] . '</td>';
          echo '<td>' . $data['M']['18.2a']['Rx'] . '</td>';
          echo '<td>' . $data['M']['18.3']['Rx'] . '</td>';
          echo '<td>' . $data['M']['18.4']['Rx'] . '</td>';
          echo '<td>' . $data['M']['18.5']['Rx'] . '</td>';
          echo '<td>' . $data['M']['total']['Rx'] . '</td>';
          echo '<td>' . $data['M']['total']['Total'] . '</td>';
        echo '</tr><tr>';
          echo '<td>' . $data['M']['18.1']['Scaled'] . '</td>';
          echo '<td>' . $data['M']['18.2']['Scaled'] . '</td>';
          echo '<td>' . $data['M']['18.2a']['Scaled'] . '</td>';
          echo '<td>' . $data['M']['18.3']['Scaled'] . '</td>';
          echo '<td>' . $data['M']['18.4']['Scaled'] . '</td>';
          echo '<td>' . $data['M']['18.5']['Scaled'] . '</td>';
          echo '<td>' . $data['M']['total']['Scaled'] . '</td>';
        echo '</tr></table>';

        die();
        dd($data['M']);

      }
    }
  }
}
