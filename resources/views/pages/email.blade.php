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
        <h2><a href="{{$data['web_view']}}" target="_blank">{{$data['title']}}</a><br><small>Campaign ID: {{$data['id']}}</small></h2>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <table class="table">
          <thead>
            <tr>
              <th>Recipiants</th>
              <th>Unique Opens</th>
              <th>Open Rate</th>
              <th>Clicks</th>
              <th>Click Rate</th>
              <th>Total Clicks</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{$data['recipients']}}</td>
              <td>{{$data['opens']}}</td>
              <td>{{$data['open_rate']}}%</td>
              <td>{{$data['clicks_unique']}}</td>
              <td>{{$data['click_rate']}}%</td>
              <td>{{$data['clicks_total']}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection