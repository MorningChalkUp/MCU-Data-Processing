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

        ))
        ->hourly();


        $schedule->call(function(

            $campaigns = DB::connection('analytics')->table('wp_postmeta')
                ->where('meta_key', 'campaign_id')->get();

            foreach ($campaigns as $campaign) {
                $post_date = DB::connection('analytics')
                    ->table('wp_posts')
                    ->select('post_date')
                    ->where('ID', $campaign->post_id)
                    ->get();

                if ( strtotime($post_date[0]->post_date) < strtotime('-7 days')) {
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
