<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CampaignMonitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class GetEngaged extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engaged:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $return = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_lists();

        $emails = array();

        $page = 1;
        Storage::delete('engagement.csv');
        Storage::disk('local')->append('engagement.csv', 'email,active date');

        foreach ($return->response as $list) {
            if ($list->Name == 'The Morning Chalk Up') {
                $list_id = $list->ListID;
            }
        }

        do {
            $r = CampaignMonitor::lists($list_id)->get_active_subscribers(date('Y-m-d', strtotime('-5 years')), $page, 500, 'email', 'asc');

            $this->info('Page ' . $page . ' of ' . $r->response->NumberOfPages);

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
            sleep(60);
            ++$page;

        } while($page <= $r->response->NumberOfPages);
        $end = Carbon::now();

        $this->info('Start Time: ' . $start);
        $this->info('End Time: ' . $end);
    }
}
