<?php

namespace App\Console;

use DB;
use CampaignMonitor;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function(

          $posts = DB::connection('analytics')
            ->table('wp_posts')
            ->select('ID')
            ->where('post_date', '>=', date('Y-m-d',strtotime('-7 days')))
            ->where('post_status', 'publish')
            ->where('post_type', 'report')
            ->get();

          foreach ($posts as $post) {
            $campaign = DB::connection('analytics')
              ->table('wp_postmeta')
              ->select('meta_value')
              ->where('post_id', $post->ID)
              ->where('meta_key', 'campaign_id')
              ->first();

            if($campaign != null) {
              $url_count = DB::connection('analytics')
                ->table('wp_postmeta')
                ->where('post_id', $post->ID)
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

              foreach ($data as $value) {

                $url = 'http://data.morningchalkup.com/api/v1/email/simple/' . $campaign->meta_value;

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
                  ->where('post_id', $post->ID)
                  ->update(['meta_value' => $result['recipients']]);

                $url = DB::connection('analytics')
                  ->table('wp_postmeta')
                  ->select('meta_value')
                  ->where('meta_key', 'opens')
                  ->where('post_id', $post->ID)
                  ->update(['meta_value' => $result['opens']]);

                $url = DB::connection('analytics')
                  ->table('wp_postmeta')
                  ->select('meta_value')
                  ->where('meta_key', 'ad_clicks')
                  ->where('post_id', $post->ID)
                  ->update(['meta_value' => $result['ad_clicks']]);

              }

            }
          }
          
           DB::connection('mysql')
            ->table('cron_run')
            ->insert([
                'run_time' => date('Y-m-d H:i:s'),
                'run_event' => 'Hourly Ads Update'
            ]);
            
        ))
        ->hourly();


        $schedule->call(function(

          $posts = DB::connection('analytics')
            ->table('wp_posts')
            ->select('ID')
            ->where('post_date', '<=', date('Y-m-d',strtotime('-7 days')))
            ->where('post_date', '>=', date('Y-m-d',strtotime('-30 days')))
            ->where('post_status', 'publish')
            ->where('post_type', 'report')
            ->get();

          foreach ($posts as $post) {
            $campaign = DB::connection('analytics')
              ->table('wp_postmeta')
              ->select('meta_value')
              ->where('post_id', $post->ID)
              ->where('meta_key', 'campaign_id')
              ->first();

            if($campaign != null) {
              $url_count = DB::connection('analytics')
                ->table('wp_postmeta')
                ->where('post_id', $post->ID)
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

              foreach ($data as $value) {

                $url = 'http://data.morningchalkup.com/api/v1/email/simple/' . $campaign->meta_value;

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
                  ->where('post_id', $post->ID)
                  ->update(['meta_value' => $result['recipients']]);

                $url = DB::connection('analytics')
                  ->table('wp_postmeta')
                  ->select('meta_value')
                  ->where('meta_key', 'opens')
                  ->where('post_id', $post->ID)
                  ->update(['meta_value' => $result['opens']]);

                $url = DB::connection('analytics')
                  ->table('wp_postmeta')
                  ->select('meta_value')
                  ->where('meta_key', 'ad_clicks')
                  ->where('post_id', $post->ID)
                  ->update(['meta_value' => $result['ad_clicks']]);

              }

            }
          }
          
           DB::connection('mysql')
            ->table('cron_run')
            ->insert([
                'run_time' => date('Y-m-d H:i:s'),
                'run_event' => 'Hourly Ads Update'
            ]);

        ))
        ->weekly()
        ->withoutOverlapping();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
