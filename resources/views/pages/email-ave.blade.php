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
              <td>{{$data['recipiants']}}</td>
              <td>{{$data['opens']}}</td>
              <td>{{$data['open_rate']}}%</td>
              <td>{{$data['clicks']}}</td>
              <td>{{$data['click_rate']}}%</td>
              <td>{{$data['total_clicks']}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection