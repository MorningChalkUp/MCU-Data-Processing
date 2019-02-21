<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Http\Controllers\AthleteController;
use Illuminate\Support\Facades\Storage;

class RunOpenData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'open:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create JSON file with open data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = Carbon::now();
        $this->info('Start Time: ' . $start);

        $base = 'https://games.crossfit.com/competitions/api/v1/competitions/open/2019/leaderboards';
		
				$registrations = array();

				$divs = array(
					'14 - 15' => array(
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
					),
					'RX' => array(
						'Men' => 1,
						'Women' => 2,
					),
				);
				
				foreach($divs as $key => $div) {
					$args = array(
						'division' => $div['Men'],
						'scaled' => 0,
						'page' => 1,
					);
					$url = $base . '?' . http_build_query($args);
					$result = AthleteController::getUrl($url);
					// dd($result);
					$registrations[$key]['Men']['Registrants'] = $result['pagination']['totalCompetitors'];
					$pages = $result['pagination']['totalPages'];
					
					while($args['page'] <= $pages) {
						$end = Carbon::now();
						$this->info($key . ' Men - Page ' . $args['page'] . ' of ' . $pages . ': ' . $end);
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

								if(isset($registrations[$key]['Men']['Affiliates']['List'][$affiliate])) {
									++$registrations[$key]['Men']['Affiliates']['List'][$affiliate];
								} else {
									if(isset($registrations[$key]['Men']['Affiliates']['count'])) {
										++$registrations[$key]['Men']['Affiliates']['count'];
									} else {
										$registrations[$key]['Men']['Affiliates']['count'] = 1;
									}
									$registrations[$key]['Men']['Affiliates']['List'][$affiliate] = 1;
								}
							}
							
							++$args['page'];
							
						}

						Storage::disk('local')->put('data.json', json_encode($registrations));

					}
						
					$args = array(
						'division' => $div['Women'],
						'scaled' => 0,
						'page' => 1,
					);
					$url = $base . '?' . http_build_query($args);
					$result = AthleteController::getUrl($url);
					$registrations[$key]['Women']['Registrants'] = $result['pagination']['totalCompetitors'];

					while($args['page'] <= $pages) {
						$end = Carbon::now();
						$this->info($key . ' Women - Page ' . $args['page'] . ' of ' . $pages . ': ' . $end);
						if($args['page'] != 1) {
							$url = $base . '?' . http_build_query($args);
							$result = AthleteController::getUrl($url);
						}
						if(isset($result['leaderboardRows'])){
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

								if(isset($registrations[$key]['Women']['Affiliates']['List'][$affiliate])) {
									++$registrations[$key]['Women']['Affiliates']['List'][$affiliate];
								} else {
									if(isset($registrations[$key]['Women']['Affiliates']['count'])) {
										++$registrations[$key]['Women']['Affiliates']['count'];
									} else {
										$registrations[$key]['Women']['Affiliates']['count'] = 1;
									}
									$registrations[$key]['Women']['Affiliates']['List'][$affiliate] = 1;
								}
							}
							
							++$args['page'];
							
						}

						Storage::disk('local')->put('data.json', json_encode($registrations));

					}
				}

				Storage::disk('local')->put('data.json', json_encode($registrations));

				$end = Carbon::now();
				$this->info('Finish Time: ' . $end);

				$this->info('Total Run Time: ' . $end->diff($start));
        
    }
}
