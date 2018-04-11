<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class UpdateRecentAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:update {--old}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update MCU Ads For the past 7 days';

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

        if($this->option('old')) {
            $posts = DB::connection('analytics')
                ->table('wp_posts')
                ->select('ID')
                ->where('post_date', '<=', date('Y-m-d',strtotime('-7 days')))
                ->where('post_date', '>=', date('Y-m-d',strtotime('-30 days')))
                ->where('post_status', 'publish')
                ->where('post_type', 'report')
                ->get();

            $output = "Old Ads Updated";
        } else {
            $posts = DB::connection('analytics')
                ->table('wp_posts')
                ->select('ID')
                ->where('post_date', '>=', date('Y-m-d',strtotime('-7 days')))
                ->where('post_status', 'publish')
                ->where('post_type', 'report')
                ->get();

            $output = "Recent Ads Updated";
        }

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

        $end = Carbon::now();

        $this->info('Finish Time: ' . $end);

      
        DB::connection('mysql')
            ->table('cron_run')
            ->insert([
                'run_time' => $start,
                'run_finish' => $end,
                'run_event' => $output,
            ]);
    }
}
