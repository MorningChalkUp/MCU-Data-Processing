@extends('layouts.master')

@section('body')

  <div class="container">
    <div class="row">
      <div class="col">
        <table class="table table-inverse">
          <thead>
            <tr>
              <th>Name</th>
              <th>Send Date</th>
              <th>Subject</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($campaigns as $campaign)
            <tr>
              <td><a href="/email/{{$campaign->CampaignID}}">{{$campaign->Name}}</a></td>
              <td>{{$campaign->SentDate}}</td>
              <td>{{$campaign->Subject}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection