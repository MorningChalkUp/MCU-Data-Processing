@extends('layouts.master')

@section('body')

  <div class="container">
    <div class="row">
      <div class="col text-center">
        <a href="/email">Full List</a>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>ID</th>
              <th>Send Date</th>
              <th>Recipiants</th>
              <th>Unique Opens</th>
              <th>Open Rate</th>
              <th>Clicks</th>
              <th>Click Rate</th>
              <th>Total Clicks</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($campaigns as $campaign)
            <tr>
              <td>{{$campaign['name']}}</td>
              <td>{{$campaign['id']}}</td>
              <td>{{$campaign['sendDate']}}</td>
              <td>{{$campaign['recipiants']}}</td>
              <td>{{$campaign['opens']}}</td>
              <td>{{$campaign['open_rate']}}%</td>
              <td>{{$campaign['clicks']}}</td>
              <td>{{$campaign['click_rate']}}%</td>
              <td>{{$campaign['total_clicks']}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection