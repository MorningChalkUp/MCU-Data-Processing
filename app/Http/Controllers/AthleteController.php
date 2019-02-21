<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;
use Illuminate\Support\Facades\Storage;

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

              DB::table('cf_open2018_agoq')->insert([$data]);

              // $athletes[] = $data;

            }
          }

          ++$page;
        } while ($page <= $total_pages);

        dd('done');

      }
    }
  }

  public function getDataAGOQ() {

    $events = array(
      '1' => 0,
      '2' => 1,
      '3' => 2,
      '4' => 3,
      '5' => 4
    );

    $groups = array(
      'M 35-39' => 18,
      'W 35-39' => 19,
      'M 40-44' => 12,
      'W 40-44' => 13,
      'M 45-49' => 3,
      'W 45-49' => 4,
      'M 50-54' => 5,
      'W 50-54' => 6,
      'M 55-59' => 7,
      'W 55-59' => 8,
      'M 60+' => 9,
      'W 60+' => 10,
      'M 16-17' => 16,
      'W 16-17' => 17,
      'M 14-15' => 14,
      'W 14-15' => 15,
    );

    $data = array();
    $athletes = array();

    foreach ($groups as $group => $groupvalue) {
      $page = 1;
      do {

        $options = array(
          'division' => $groupvalue,
          'sort' => '0',
          'page' => $page,
        );
        $option_list = array();
        foreach ($options as $key => $value) {
          $option_list[] = $key . '=' . $value;
        }

        $query = implode('&', $option_list);

        $url = 'https://games.crossfit.com/competitions/api/v1/competitions/onlinequalifiers/2018/leaderboards?' . $query;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $total_pages = $result['pagination']['totalPages'];

        if ($result != null && isset($result['leaderboardRows'])) {

          foreach ($result['leaderboardRows'] as $count => $athlete) {
            $data['competitorId'] = intval($athlete['entrant']['competitorId']);
            $data['competitorName'] = $athlete['entrant']['competitorName'];
            $data['firstName'] = $athlete['entrant']['firstName'];
            $data['lastName'] = $athlete['entrant']['lastName'];
            $data['gender'] = $athlete['entrant']['gender'];
            $data['regionalCode'] = intval($athlete['entrant']['regionalCode']);
            $data['regionId'] = intval($athlete['entrant']['regionId']);
            $data['regionName'] = $athlete['entrant']['regionName'];
            $data['divisionId'] = intval($athlete['entrant']['divisionId']);
            $data['divisionName'] = $group;
            $data['affiliateId'] = intval($athlete['entrant']['affiliateId']);
            $data['affiliateName'] = $athlete['entrant']['affiliateName'];
            $data['age'] = intval($athlete['entrant']['age']);
            $data['height'] = $athlete['entrant']['height'];
            $data['weight'] = $athlete['entrant']['weight'];
            $data['overall_rank'] = intval($athlete['overallRank']);
            $data['overall_score'] = intval($athlete['overallScore']);

            foreach ($events as $event => $eventvalue) {
              $wod = 'wod' . ($eventvalue + 1);
              $data[$wod . '_score'] = intval($athlete['scores'][$eventvalue]['score']);
              $data[$wod . '_score_display'] = $athlete['scores'][$eventvalue]['scoreDisplay'];
              $data[$wod . '_rank'] = intval($athlete['scores'][$eventvalue]['rank']);
            }


            DB::table('cf_open2018_agoq')->insert([$data]);
            // DB::table('cf_open2018_agoq')->where('competitorId', $data['competitorId'])->update([$data]);

          }
        }

        ++$page;
      } while ($page <= $total_pages);

    }

    dd('done');

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

  public function regionE1() {
    $region = array(
      'East' => 21, // East
      'Europe' => 22, // Europe
      'South' => 23, // South
    );
    $div = array(
      'Men' => 1,  // Men
      'Women' => 2,  // Women
      'Team' => 11, // Team
    );
    $sort = array(
      'Overall' => 0,  // Overall
      'Event1' => 1,  // Event 1
      'Event2' => 2,  // Event 2
      'Event3' => 3,  // Event 3
      'Event4' => 4,  // Event 4
      'Event5' => 5,  // Event 5
      'Event6' => 6,  // Event 6
    );

    $query = 'division=' . $div['Women'] . '&' .
      'regional=' . $region['South'] . '&' .
      'sort=' . $sort['Event1'] . '&' .
      'page=1';

    $url = 'https://games.crossfit.com/competitions/api/v1/competitions/regionals/2018/leaderboards?' . $query;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    // dd($result);

    return view('pages.games.region.e1')->with('result', $result);
  }

  public function rOverall() {
    $region = array(
      'East' => 21, // East
      'Europe' => 22, // Europe
      'South' => 23, // South
      'Central' => 24, // Central
      'West' => 25, // Central
      'LA' => 26, // Central
      'Atlantic' => 27,
      'Meridian' => 28,
      'Pacific' => 29,
      'All' => 32,
    );
    $divs = array(
      'Men' => 1,  // Men
      'Women' => 2,  // Women
      'Team' => 11, // Team
      'M14' => 14,
      'W14' => 15,
      'M16' => 16,
      'W16' => 17,
      'M35' => 18,
      'W35' => 19,
      'M40' => 12,
      'W40' => 13,
      'M45' => 3,
      'W45' => 4,
      'M50' => 5,
      'W50' => 6,
      'M55' => 7,
      'W55' => 8,
      'M60' => 9,
      'W60' => 10,
    );
    $sort = array(
      'Overall' => 0,  // Overall
      'Event1' => 1,  // Event 1
      'Event2' => 2,  // Event 2
      'Event3' => 3,  // Event 3
      'Event4' => 4,  // Event 4
      'Event5' => 5,  // Event 5
      'Event6' => 6,  // Event 6
    );

    foreach ($divs as $div => $id) {

      $query = 'division=' . $id . '&' .
      // $query = 'division=' . $divs['Team'] . '&' .
        // 'regional=' . $region['Central'] . '&' .
        'sort=' . $sort['Overall'] . '&' .
        'page=1';

      $url = 'https://games.crossfit.com/competitions/api/v1/competitions/games/2018/leaderboards?' . $query;

      // dd($url);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      $result = json_decode(curl_exec($ch), true);
      curl_close($ch);

      $all[$div] = $result;

      // dd($result);
    }
    return view('pages.games.region.o')->with('all', $all);
  }

  public function athleteProfile($id) {

    $url = 'https://games.crossfit.com/competitions/api/v1/athlete/' . $id;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    dd($result);

	}
	
	public function getYearTop() {
		// $url = 'https://games.crossfit.com/competitions/api/v1/competitions/open/2018/leaderboards?division=2&sort=0&scaled=0&page=1';
		$url = 'https://games.crossfit.com/competitions/api/v1/competitions/open/2017/leaderboards?division=2&sort=0&scaled=0&page=1';
		
		$ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = json_decode(curl_exec($ch), true);
		curl_close($ch);
		
		// 2018
		/* foreach($result['leaderboardRows'] as $athlete) {
			echo $athlete['entrant']['competitorName'] . '<br>';
		} */

		// 2017
		foreach($result['athletes'] as $athlete) {
			echo $athlete['name'] . '<br>';
		}
		
		// dd($result);
	}

	public static function get2019() {
		$base = 'https://games.crossfit.com/competitions/api/v1/competitions/open/2019/leaderboards';
		
		$registrations = array();

		$divs = array(
			'RX' => array(
				'Men' => 1,
				'Women' => 2,
			),
			/* '14 - 15' => array(
				'Men' => 14,
				'Women' => 15,
			),
			'16 - 17' => array(
				'Men' => 16,
				'Women' => 17,
			),
			'35 - 39' => array(
				'Men' => 18,
				'Women' => 19,
			),
			'40 - 44' => array(
				'Men' => 12,
				'Women' => 13,
			),
			'45 - 49' => array(
				'Men' => 3,
				'Women' => 4,
			),
			'50 - 54' => array(
				'Men' => 5,
				'Women' => 6,
			),
			'55 - 59' => array(
				'Men' => 7,
				'Women' => 8,
			),
			'60+' => array(
				'Men' => 9,
				'Women' => 10,
			), */
		);
		
		foreach($divs as $key => $div) {
			$args = array(
				'division' => $div['Men'],
				'scaled' => 0,
				'page' => 1,
			);
			$url = $base . '?' . http_build_query($args);
			$result = AthleteController::getUrl($url);
			$registrations[$key]['Men']['Registrants'] = $result['pagination']['totalCompetitors'];
			$pages = $result['pagination']['totalPages'];
			
			while($args['page'] <= $pages) {
				$end = Carbon::now();

				if($args['page'] != 1) {
					$url = $base . '?' . http_build_query($args);
					$result = AthleteController::getUrl($url);
				}
				if(isset($result['leaderboardRows'])){
					foreach($result['leaderboardRows'] as $athlete) {
						$country = $athlete['entrant']['countryOfOriginName'];
						$affiliate = $athlete['entrant']['affiliateName'];
						if(isset($registrations[$key]['Men']['Countries']['List'][$country])) {
							++$registrations[$key]['Men']['Countries']['List'][$country];
						} else {
							if(isset($registrations[$key]['Men']['Countries']['count'])) {
								++$registrations[$key]['Men']['Countries']['count'];
							} else {
								$registrations[$key]['Men']['Countries']['count'] = 1;
							}
							$registrations[$key]['Men']['Countries']['List'][$country] = 1;
						}
					}
					
					++$args['page'];
					
				}
			}
				
			/* $args = array(
				'division' => $div['Women'],
				'scaled' => 0,
				'page' => 1,
			);
			$url = $base . '?' . http_build_query($args);
			$result = AthleteController::getUrl($url);
			$registrations[$key]['Women']['Registrants'] = $result['pagination']['totalCompetitors'];

			foreach($result['leaderboardRows'] as $athlete) {
				$country = $athlete['entrant']['countryOfOriginName'];
				$affiliate = $athlete['entrant']['affiliateName'];
				if(isset($registrations[$key]['Women']['Countries']['List'][$country])) {
					++$registrations[$key]['Women']['Countries']['List'][$country];
				} else {
					if(isset($registrations[$key]['Women']['Countries']['count'])) {
						++$registrations[$key]['Women']['Countries']['count'];
					} else {
						$registrations[$key]['Women']['Countries']['count'] = 1;
					}
					$registrations[$key]['Women']['Countries']['List'][$country] = 1;
				}
			} */
		}

		Storage::disk('local')->put('data.json', json_encode($registrations));
		// $url = Storage::url('data.json');

		// dd($url);

		// return view('pages.games.open.registrations')->with('registrations', $registrations);
	}

	public static function getUrl($url) {

		$ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = json_decode(curl_exec($ch), true);
		curl_close($ch);

		return $result;

	}
}
