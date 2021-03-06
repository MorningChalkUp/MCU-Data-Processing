@extends('emails.wrapper')

@section('body')

  <tr>
    <td style="padding: 40px 40px 10px; font-family: Roboto, sans-serif; font-size: 16px; line-height: 24px; color: #333132;">

      <p>{{ $data['date'] }}</p>

      <p>{{ $data['name'] }},</p>

      <p>Your next sponsorship starts on {{ $data['ad_date'] }}.</p>
			
			<p>The deadline to submit your ad copy is {{ $data['ad_deadline'] }} at 5:00 PM PT. Once your copy has been submitted, our team will review all sponsorships to ensure they meet our guidelines and spot check them for quality.</p>

			<p>Please reach out if you have any questions.</p>

      <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
        <tbody>
          <tr>
            <td style="border-radius: 3px; background: #3d5ba9; text-align: center;" class="button-td">
              <a href="https://ads.morningchalkup.com/?p={{ $data['week_id'] }}" style="background: #3d5ba9; border: 15px solid #3d5ba9; font-family: Roboto, sans-serif; font-size: 16px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                <span style="color:#ffffff;" class="button-link">Draft Your Ads</span>
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
