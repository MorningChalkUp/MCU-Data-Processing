<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class CMController extends Controller
{
    public function updateAds() {
      $posts = DB::connection('analytics')->select('select id from wp_posts where post_type = "report" and post_status = "publish"');
      foreach($posts as $post) {
        $meta = DB::connection('analytics')->select('select meta_key, meta_value from wp_postmeta where post_id = ?', [$post->id]);

        $meta_options = array();
        
        foreach($meta as $option) {
          $meta_options[$option->meta_key] = $option->meta_value;
        }

        $urls = array();

        for ($i = 0; $i < $meta_options['ad_urls']; ++$i) {
          $urls[] = $meta_options['ad_urls_' . $i . '_url'];
        }

        $data = $this->getData($meta_options['campaign_id'], $urls);

        DB::connection('analytics')->table('wp_postmeta')
          ->where('post_id', $post->id)
          ->where('meta_key', 'recipients')
          ->update(['meta_key' => 0]);
        dd($data);
      }
      
    }

    public function updateDB() {
      $posts = DB::connection('analytics')->select('select id from wp_posts where post_type = "report"');
      foreach($posts as $post) {
        $url = 'http://ads.mcu.test/?p=' . $post->id;

        echo '<a target="_blank" href="' . $url . '">' . $url . '</a><br>';
      }
    }

    private function getData($id, $domains = null) {
      
      $query = '';

      if (isset($domains) && $domains != []) {
        $query = '?';
        foreach ($domains as $domain) {
          $query .= 'url[]=' . $domain;
        }
      }

      // $url = 'http://data.morningchalkup.com/api/email/simple/'.$id . $query;
      $url = 'http://mcu-data.test/api/email/simple/'.$id . $query;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      $result = json_decode(curl_exec($ch), true);
      curl_close($ch);

      $data = $result['response'];

      return $data;
    }
}
