<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HashController extends Controller
{
    public function hashEmails() {
      $people = DB::table('cu_people')->get();
      
      foreach ($people as $person) {
        if ($person->hash_email == null) {
          $email = strtolower($person->email);
          $hash = hash('sha256', $email);
          DB::table('cu_people')
            ->where('email', $person->email)
            ->update([
              'hash_email' => $hash
            ]);
        }
      }
      $people = DB::table('cu_people')->get();
      dd($people);
    }
}
