@extends('emails.wrapper')

@section('body')
  <tr>
    <td style="padding: 40px 40px 10px; font-family: Roboto, sans-serif; font-size: 16px; line-height: 24px; color: #333132;">

      <p>{{ $data['name'] }},</p>

      <p>Your sponsored link is going in on {{ $data['ad_date'] }}</p>

      <p>Here's what we need from you:</p>

      <ol>
        <li>The link you'd like to direct readers.</li>
        <li>A discount code (If applicable).</li>
        <li>Any specific language requests you have for your ad copy.</li>
      </ol>

      <p>We'll begin drafting your ad copy then send back to you for approval.</p>

      <p>-- Justin</p>
      
    </td>
  </tr>
  <tr>
    <td aria-hidden="true" height="20" style="font-size: 0; line-height: 0;">&nbsp;</td>
  </tr>

@endsection