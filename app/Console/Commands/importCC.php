<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CampaignMonitor;
use Carbon\Carbon;
use DB;

class importCC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import comp corner';

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
        $url = 'https://competitioncorner.net/api2/mcu/subscribers';
        $authorization = "Authorization: Bearer " . env('COMP_CORNER_TOKEN');

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        $subs = json_decode($result);

        $data = array();

        foreach($subs as $sub) {
          $data[] = array(
            'EmailAddress' => $sub->email,
            'Name' => $sub->firstName . ' ' . $sub->lastName,
            'CustomFields' => array(
              array(
                'key' => 'Country',
                'value' => $sub->countryName
              ),
              array(
                'key' => 'City',
                'value' => $sub->city
              ),
              array(
                'key' => 'State',
                'value' => $sub->region
              ),
              array(
                'key' => 'comp_corner',
                'value' => 1
              ),
              'ConsentToTrack' => "Yes"
            ),
          );
        }

        $result = CampaignMonitor::subscribers(env('CAMPAIGNMONITOR_MCU_LIST_ID'))->import($data, false);
    }
}
