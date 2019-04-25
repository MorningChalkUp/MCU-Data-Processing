<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;

class AffiliateController extends Controller
{
  public function checkEmail() {
    $emails = DB::table('cf_affiliate_scrape')->get();

    foreach ($emails as $email) {
      $result = filter_var( $email->email, FILTER_VALIDATE_EMAIL );
      // echo $email->email . ' - ' . $result . '<br>';
      if (!$result) {
        DB::table('cf_affiliate_scrape')->where('email', $email->email)->delete();
        echo $email->email . ' - ' . $result . '<br>';
      }
    }
  }

  public function topCountries() {
    ini_set('max_execution_time', 300);
    $affiliates = DB::table('affiliates')->get();
    $users = DB::table('cu_people')->distinct()->where('affiliate', '<>', null)->get();

    $countries = array();

    foreach($affiliates as $affiliate) {
      foreach ($users as $user) {
        if ($affiliate->name == $user->affiliate) {
          if(isset($countries[$affiliate->country])) {
            $countries[$affiliate->country] += 1;
          } else {
            $countries[$affiliate->country] = 1;
          }
          break;
        }
      }
    }

    dd($countries);
  }

  public function getHQList() {
    $client = new Client();

    $affiliates = array();

    // $countries = array('US','CA','AU','AF','AX','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM','AW','AU','AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ','BM','BT','BO','BQ','BA','BW','BV','BR','IO','BN','BG','BF','BI','KH','CM','CA','CV','KY','CF','TD','CL','CN','CX','CC','CO','KM','CD','CG','CK','CR','CI','HR','CW','CY','CZ','DK','DJ','DM','DO','EC','EG','SV','GQ','ER','EE','ET','FK','FO','FJ','FI','FR','GF','PF','TF','GA','GM','GE','DE','GH','GI','GR','GL','GD','GP','GU','GT','GG','GN','GW','GY','HT','HM','VA','HN','HK','HU','IS','IN','ID','IQ','IE','IM','IL','IT','JM','JP','JE','JO','KZ','KE','KI','KR','XK','KW','KG','LA','LV','LB','LS','LR','LY','LI','LT','LU','MO','MK','MG','MW','MY','MV','ML','MT','MH','MQ','MR','MU','YT','MX','FM','MD','MC','MN','ME','MS','MA','MZ','MM','NA','NR','NP','NL','NC','NZ','NI','NE','NG','NU','NF','MP','NO','OM','PK','PW','PS','PA','PG','PY','PE','PH','PN','PL','PT','PR','QA','RE','RO','RU','RW','GS','BL','SH','KN','LC','MF','PM','VC','WS','SM','ST','SA','SN','RS','SC','SL','SG','SX','SK','SI','SB','SO','ZA','SS','ES','LK','SR','SJ','SZ','SE','CH','TW','TJ','TZ','TH','TL','TG','TK','TO','TT','TN','TR','TM','TC','TV','UG','UA','AE','GB','US','UY','UM','UZ','VU','VE','VN','VG','VI','WF','EH','YE','ZM','ZW');

    // $countries = array('US','CA','AU','AF','AX','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM','AW','AU','AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ','BM','BT','BO','BQ','BA','BW','BV','BR','IO','BN','BG','BF','BI','KH','CM','CA','CV','KY','CF','TD','CL','CN','CX','CC','CO','KM','CD','CG','CK','CR','CI','HR','CW','CY','CZ');

    // foreach ($countries as $country) {

      $i = 1;

      do {

        // $resp = $client->request('GET', 'https://www.crossfit.com/cf/find-a-box.php?country=' . $country . '&page=' . $i);
        $resp = $client->request('GET', 'https://www.crossfit.com/cf/find-a-box.php?country=US&page=' . $i);

        if(json_decode($resp->getBody()) != null) {
          // dump(json_decode($resp->getBody()));

          $affiliates = array_merge($affiliates, json_decode($resp->getBody())->affiliates);

        }

        ++$i;

      } while (json_decode($resp->getBody()) != null);
      // } while (json_decode($resp->getBody()) != null && $i < 2);

    // }

    return view('pages.csv-affiliates')->with('affiliates', $affiliates);
    // return view('pages.affiliates')->with('affiliates', $affiliates);

    // dump($affiliates);

    // dd($resp->getBody());
    // dd(json_decode($resp->getBody()));
  }
}
