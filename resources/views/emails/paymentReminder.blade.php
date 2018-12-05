@extends('emails.wrapper')

@section('body')

  <tr>
    <td style="padding: 40px 40px 10px; font-family: Roboto, sans-serif; font-size: 16px; line-height: 24px; color: #333132;">

      <p>{{ $data['date'] }}}</p>

      <p>{{ $data['name'] }},</p>

      <p>Your first ad buy will be starting on {{ $data['ad_date'] }}. Don't forget to complete your payment beforehand by visiting <a href="https://ads.morningchalkup.com/p?{{ $data['order_id'] }}">here</a>.</p>

      <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
        <tbody>
          <tr>
            <td style="border-radius: 3px; background: #3d5ba9; text-align: center;" class="button-td">
              <a href="https://ads.morningchalkup.com/p?{{ $data['order_id'] }}" style="background: #3d5ba9; border: 15px solid #3d5ba9; font-family: Roboto, sans-serif; font-size: 16px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                <span style="color:#ffffff;" class="button-link">Pay Your Balance</span>
              </a>
            </td>
          </tr>
        </tbody>
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
