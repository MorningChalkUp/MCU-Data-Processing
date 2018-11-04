<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .x-gmail-data-detectors,    /* Gmail */
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }

        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */
        /* Thanks to Eric Lepetit (@ericlepetitsf) for help troubleshooting */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }

    </style>
    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

    /* What it does: Hover styles for buttons */
    .button-td,
    .button-a {
        transition: all 100ms ease-in;
    }
    .button-td:hover,
    .button-a:hover {
        background: #555555 !important;
        border-color: #555555 !important;
    }

    /* Media Queries */
    @media screen and (max-width: 600px) {

        /* What it does: Adjust typography on small screens to improve readability */
        .email-container p {
            font-size: 17px !important;
            line-height: 22px !important;
        }

    }

    </style>
    <!-- Progressive Enhancements : END -->
    
    <!-- Hope this gets in-lined : BEGIN -->
    <style>
        a{color:#3d5ba9;text-decoration:none;}
        .dark a{color:#fff;}
        
    </style>
    <!-- Hope this gets in-lined : END -->

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

</head>
<body width="100%" bgcolor="#ffffff" style="margin: 0; mso-line-height-rule: exactly;">
    <center style="width: 100%; background: #ffffff; text-align: left;">

        <!--
            Set the email width. Defined in two places:
            1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
            2. MSO tags for Desktop Windows Outlook enforce a 600px width.
        -->
        <div style="max-width: 600px; margin: auto;" class="email-container">
            <!--[if mso]>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
            <tr>
            <td>
            <![endif]-->

            <!-- Email Header : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px; font-family:Roboto, sans-serif">
              <tr>
                <!-- <td align="left" style="padding: 20px 40px;font-size:12px"><span style="color:#999999">AUTOMATED EMAIL</span></td> -->
                <td align="right" style="padding: 20px 40px;font-size:12px"> </td>
              </tr>
            </table>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
                <tr>
                    <td style="padding: 20px 40px 0; text-align: center">
                        <img src="https://i4.createsend1.com/ti/j/A2/853/084/104717/mcu-normal.png" width="320" height="63" alt="Morning Chalk Up" border="0" align="center" style="max-width: 100%; height: auto; font-family: Roboto, sans-serif; font-size: 24px; line-height: 36px; color: #555555; margin: auto;" class="g-img">
                    </td>
                </tr>
            </table>
            <!-- Email Header : END -->

            <!-- Email Body : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
                
                <!-- 1 Column Text + Button : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">

                          @yield('body')

                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text + Button : END -->

                <!-- Clear Spacer : BEGIN -->
                <tr><td aria-hidden="true" height="40" style="font-size: 0; line-height: 0;">&nbsp;</td></tr>
                <!-- Clear Spacer : END -->

            </table>
            <!-- Email Body : END -->

            <!--[if mso]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </div>

        <!-- Full Bleed Background Section : BEGIN -->
        <table role="presentation" bgcolor="#eee" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
            <tr>
                <td valign="top" align="center">
                    <div style="max-width: 600px; margin: auto;" class="email-container">
                        <!--[if mso]>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                        <tr>
                        <td>
                        <![endif]-->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 30px 40px 10px; text-align: center; font-family: Roboto, sans-serif; font-size: 15px; line-height: 20px; color: #ffffff;">
                                    <a href="http://morningchalkup.com" style="border:none;text-decoration:none;"><img src="https://i4.createsend1.com/ti/j/A2/853/084/104717/mcu-grey.png" width="300" height="40" alt="Morning Chalk Up" border="0" align="center" style="max-width: 100%; height: auto; font-family: Roboto, sans-serif; font-size: 24px; line-height: 36px; color: #ffffff; margin: auto;" class="g-img"></a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0 40px 20px; text-align: center; font-family: Roboto, sans-serif; font-size: 15px; line-height: 20px; color: #cccccc;">
                                    <a style="color:#3d5ba9;" href="http://facebook.com/morningchalkup" target="_blank"><span style="color:#3d5ba9;">FACEBOOK</span></a>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <a style="color:#3d5ba9;" href="http://instagram.com/morningchalkup" target="_blank"><span style="color:#3d5ba9;">INSTAGRAM</span></a>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <a style="color:#3d5ba9;" href="https://www.youtube.com/channel/UCaVuIEkcQkaLKCUfWF2-Nqg" target="_blank"><span style="color:#3d5ba9;">YOUTUBE</span></a>
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 0 40px; text-align: center; font-family: Roboto, sans-serif; font-size: 12px; line-height: 16px; color: #999999;">
                                    <em>Copyright © <currentyear> Chalk Up Media Group LLC, All rights reserved.</em>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 40px 40px; text-align: center; font-family: Roboto, sans-serif; font-size: 12px; line-height: 16px; color: #999999;">
                                    <a style="color:#999999;" href="http://morningchalkup.com/signup?utm_source=mailchimp&amp;utm_medium=email&amp;utm_campaign=signup-button" target="_blank"><span style="color:#999999;">SUBSCRIBE</span></a>
                                </td>
                            </tr>
                        </table>
                        <!--[if mso]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </div>
                </td>
            </tr>
        </table>
        <!-- Full Bleed Background Section : END -->

    </center>
</body>
</html>