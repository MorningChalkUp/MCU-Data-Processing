@extends('layouts.master')

@section('body')

  <div class="container">
    <div class="row">
      <div class="col">
        <table class="table table-sm">
          <tbody>
            @foreach($result['leaderboardRows'] as $leader)
              <tr>
                <td>{{$leader['scores'][0]['workoutrank']}}</td>
                <td>
                  {{$leader['entrant']['competitorName']}}
                </td>
                {{-- <td>
                  @if(isset($leader['scores'][0]['scoreDisplay']))
                    00:{{$leader['scores'][0]['scoreDisplay']}}
                  @else
                    --
                  @endif
                </td> --}}
                <td>
                  {{$leader['entrant']['age']}}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection