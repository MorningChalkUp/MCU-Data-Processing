<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CampaignMonitor;
use Carbon\Carbon;
use DB;

class SetEngaged extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engaged:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update DB with engaged users';

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

        $return = CampaignMonitor::clients(\Config::get('campaignmonitor.client_id'))->get_lists();

        foreach ($return->response as $list) {
          if ($list->Name == 'The Morning Chalk Up') {
            $list_id = $list->ListID;
          }
        }

        $emails = DB::connection('mysql')->table('cu_yahoo')->select('email', 'id')->where('id', '>', '4431')->get();

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
                  if (new Carbon('-60 days') <= $action->Date) {
                    $active = true;
                    break(2);
                  }
                }

              }

              // Only check the past 15 emails
              ++$count; if ($count > 60) break;
            
            }

          }

          DB::connection('mysql')->table('cu_yahoo')->where('id', $email->id)->update(['is_engaged' => $active]);

          // dd([$active, $email]);

        }

        $end = Carbon::now();
        $this->info('Finish Time: ' . $end);
    }
}
