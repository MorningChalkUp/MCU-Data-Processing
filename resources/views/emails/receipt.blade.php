@extends('emails.wrapper')

@section('body')
  <p>
    Order #: {{ $data['order'] }}<br>
    {{ $data['date'] }}
  </p>

  <p>{{ $data['name'] }},</p>

  <p>Thank you for reserving a sponsorship with the Morning Chalk Up.</p>

  <p>Here are the details of your order:</p>

  <hr>

  @foreach($data['items'] as $item)
    
    <h3>Morning Chalk Up Sponsorship -- {{ $item['start'] }} - {{ $item['end'] }}</h3>

    <ul>
      @if(isset($item['facebook']) && $item['facebook'] == 'true')
        <li>Facebook Retargeting</li>
      @endif

      @if(isset($item['ab']) && $item['ab'] == 'true')
        <li>A/B Testing</li>
      @endif

      @if(isset($item['wewrite']) && $item['wewrite'] == 'true')
        <li>We write your ads</li>
      @endif
    </ul>

    <p>${{ number_format($item['cost'], 2,'.', ',') }}</p>

    <hr>

  @endforeach

  <p>
    Grand Total: ${{ number_format($data['total'], 2,'.', ',') }}<br>
    Balance Due: ${{ number_format($data['balance'], 2,'.', ',') }}
    @if($data['balance'] != 0)
      by {{ date('n/j/Y', strtotime("-1 month", strtotime($data['items'][0]['start']))) }}
    @endif

  </p>

  <p>If you have any questions, email <a href="mailto:support@morningchalkup.com">support@morningchalkup.com</a>.</p>
@endsection