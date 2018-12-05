@extends('emails.wrapper')

@section('body')

  <tr>
    <td style="padding: 40px 40px 10px; font-family: Roboto, sans-serif; font-size: 16px; line-height: 24px; color: #333132;">

      <p>{{ $data['date'] }}</p>

      <p>{{ $data['name'] }},</p>

      <p>Your next ad buy will be starting on {{ $data['ad_date'] }}. Don't forget to update your ad copy by visiting <a href="https://ads.morningchalkup.com/p?{{ $data['week_id'] }}">here</a>.</p>

      <p><a href="https://ads.morningchalkup.com/p?{{ $data['week_id'] }}" class="button-a">Write Your Ads</a></p>
    </td>
  </tr>
  <tr>
    <td align="center" style="padding: 20px 40px 20px; font-family: Roboto, sans-serif; font-size: 14px; line-height: 24px; color: #333132;">
      If you have any questions, email <a href="mailto:support@morningchalkup.com">support@morningchalkup.com</a>.
    </td>
  </tr>
  <tr>
    <td aria-hidden="true" height="20" style="font-size: 0; line-height: 0;">&nbsp;</td>
  </tr>

@endsection
