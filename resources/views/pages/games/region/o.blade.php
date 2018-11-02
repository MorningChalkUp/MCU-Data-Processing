@extends('layouts.master')

@section('body')

  <div class="container">
    <div class="row p-5">
      <div class="col">
        @foreach($all as $div => $results)
          <h1>{{ $div }}</h1>
          <table>
          @foreach($results['leaderboardRows'] as $result)
            {{$result['overallRank']}}). {{$result['entrant']['competitorName']}} ({{$result['overallScore']}}),
            {{-- <tr>
              <td>{{$result['entrant']['competitorName']}}</td>
              <td>{{$result['overallScore']}}</td>
            </tr> --}}
            @if($result['overallRank'] == 3)
              @break
            @endif
          @endforeach
          </table>
        @endforeach
        {{-- <table>
          @foreach($results['leaderboardRows'] as $result)
            <tr>
              <td>
                {{$result['entrant']['competitorName']}}
              </td>
              @foreach($result['scores'] as $event)
              <td>
                @if (isset($event['points']))
                  {{ $event['points'] }}
                @endif
              </td>
              @endforeach
            </tr>
          @endforeach
        </table> --}}
      </div>
    </div>
  </div>

@endsection