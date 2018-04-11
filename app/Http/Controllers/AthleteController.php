<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;

class AthleteController extends Controller
{
  public function getData() {


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
      'M' => 1
    );

    $scales = array(
      'Rx' => 0,
      'Scaled' => 1,
      'Total' => 2,
    );

    $data = array();
    $athletes = array();

    

    foreach ($groups as $group => $groupvalue) {
      foreach($scales as $scale => $scalevalue) {
        $page = 1;
        // $page = 1001;
        // $page = 2001;
        // $page = 3001;
        // $page = 4001;
        do {
          // $total_pages = 1;
          $total_pages = 100;
          // $total_pages = 1000;
          // $total_pages = 2000;
          // $total_pages = 3000;
          // $total_pages = 4000;

          // todo 4214 M Rx
          // todo 473 M Scale

          $options = array(
            'division' => 2,
            'region' => '0',
            'scaled' => 0,
            // 'scaled' => 1,
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

          // $total_pages = $result['pagination']['totalPages'];
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
                $data[$wod . '_score_display'] = $athlete['scores'][$eventvalue]['scoreDisplay'];
                $data[$wod . '_rank'] = intval($athlete['scores'][$eventvalue]['rank']);
                $data[$wod . '_scaled'] = intval($athlete['scores'][$eventvalue]['scaled']);
              }

              // dd($data);

              DB::table('cf_open2018_leaders')->insert([$data]);

              // $athletes[] = $data;

            }
          }

          ++$page;
        } while ($page <= $total_pages);

        dd('done');

      }
    }
  }

  public function updateRegion() {

    $regions = DB::table('cf_regions')->get();
    // $regions = [5,6,9,10,11,14];
    // $regions = [15,17,18,19,20,21];
    // $regions = [22,23,24,25,26,27];
    
    // $regions = [5,6,9];
    // $regions = [10,11,14];
    // $regions = [15,17,18];
    // $regions = [19,20,21];
    // $regions = [22,23,24];
    // $regions = [25,26,27];

    $scale = [0,1];
    $scale = [0];
    // $scale = [1];
    $wod_rankings = ['wod1_rank', 'wod2_rank', 'wod3_rank', 'wod4_rank', 'wod5_rank', 'wod6_rank'];
    $genders = ['M', 'F'];
    // $genders = ['M'];
    // $genders = ['F'];
    foreach ($genders as $gender) {
      foreach ($regions as $region) {
        foreach ($scale as $isScaled) {
          $athletes = DB::table('cf_open2018_leaders')
            // ->where('regionId', $region->regionId)
            ->where('regionId', $region)
            ->where('scaled_athlete', $isScaled)
            ->where('gender', $gender)
            ->orderBy('overall_rank', 'ASC')
            ->get();

          $rank = 1;

          // Overall
          foreach ($athletes as $athlete) {
            DB::table('cf_open2018_leaders')
              ->where('id', $athlete->id)
              ->update(['overall_rank_region' => $rank]);

            ++$rank;
          }

          // By WOD
          foreach ($wod_rankings as $wod) {
            $athletes = DB::table('cf_open2018_leaders')
              // ->where('regionId', $region->regionId)
              ->where('regionId', $region)
              ->where('scaled_athlete', $isScaled)
              ->where('gender', $gender)
              ->orderBy($wod, 'ASC')
              ->get();

            $wod_rank = 1;

            foreach ($athletes as $athlete) {
              DB::table('cf_open2018_leaders')
                ->where('id', $athlete->id)
                ->update([$wod . '_region' => $wod_rank]);

              ++$wod_rank;
            }
          }
        }
      }
    }

    echo 'Done';
  }

  public function getQualifiers() {

  }
}
