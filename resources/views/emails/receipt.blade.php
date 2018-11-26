@extends('emails.wrapper')

@section('body')
  <tr>
    <td style="padding: 40px 40px 10px; font-family: Roboto, sans-serif; font-size: 16px; line-height: 24px; color: #333132;">

      <p>{{ $data['date'] }}<br>
      Order #{{ $data['order'] }}</p>

      <p>{{ $data['name'] }},</p>

      <p>Thank you for reserving a sponsorship with the Morning Chalk Up.</p>

      <p>Here are the details of your order:</p>

      <table role="presentation" cellspacing="0" cellpadding="10" border="0" align="center" style="margin: auto;width:100%" width="100%">

        @foreach($data['items'] as $item)

          <tr style="border-bottom:1px solid #ccc;" valign="bottom">
            <td>
              <p><strong>Morning Chalk Up Sponsorship - {{ $item['start'] }} - {{ $item['end'] }}</strong></p>
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
            </td>
            <td align="right" width="70"  style="white-space: nowrap;">
              @if(isset($item['cost']))
                <strong>${{number_format($item['cost'], 2,'.', ',')}}</strong>
              @else
                <strong>&nbsp;</strong>
              @endif
            </td>
          </tr>
        @endforeach
          <tr>
              <td align="right">Grand Total</td>
              <td align="right" style="white-space: nowrap;"><strong>${{ number_format($data['total'], 2,'.', ',') }}</strong></td>
          </tr>
          <tr>
              @if($data['balance'] != 0)
                <td align="right">
                  Balance Due by: {{ date('n/j/Y', strtotime("-1 month", strtotime($data['items'][0]['start']))) }}
                </td>
                <td align="right" style="white-space: nowrap;"><strong>${{ number_format($data['balance'], 2,'.', ',') }}</strong></td>
              @else
                <td align="right">
                  Balance Due:
                </td>
                <td align="right" style="white-space: nowrap;"><strong>$0.00</strong></td>
              @endif
          </tr>
      </table>
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