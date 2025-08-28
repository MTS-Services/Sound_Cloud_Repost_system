@props(['token', 'name', 'id'])
{{--
This Blade template is used for sending email verification links.
It receives 'token', 'name', and 'id' as props.
--}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Email Confirmation - RepostChain</title>

    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5 !important;
            color: #333333;
        }

        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .main-container {
            max-width: 600px;
            margin: 0 auto;
            border-spacing: 0;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .content-cell {
            padding: 40px 30px;
            background-color: #ffffff;
            color: #333333;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #333333;
            text-align: center;
            margin: 30px 0;
            line-height: 1.4;
        }

        .greeting strong {
            font-weight: 700;
        }

        .confirm-button {
            display: block;
            background-color: #f97316;
            color: #ffffff !important;
            padding: 14px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 30px auto;
            max-width: 400px;
            transition: background-color 0.2s;
        }

        .confirm-button:hover {
            background-color: #f97316;
        }

        .alternative-text {
            text-align: center;
            margin: 20px 0 30px 0;
            font-size: 16px;
            color: #666666;
        }

        .fallback-section {
            margin: 40px 0;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 4px;
            border: 1px solid #eaeaea;
        }

        .fallback-text {
            font-size: 14px;
            color: #666666;
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .fallback-url {
            background-color: #ffffff;
            padding: 12px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            color: #333333;
            word-break: break-all;
            text-align: center;
            border: 1px solid #dddddd;
        }

        .footer-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eeeeee;
        }

        .footer-text {
            font-size: 14px;
            color: #888888;
            text-align: center;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .footer-link {
            color: #f97316;
            text-decoration: none;
        }

        .footer-link:hover {
            text-decoration: underline;
        }

        .company-info {
            font-size: 12px;
            color: #999999;
            text-align: center;
            margin-top: 20px;
            line-height: 1.4;
        }

        @media only screen and (max-width: 600px) {
            .content-cell {
                padding: 25px 20px;
            }

            .logo-text {
                font-size: 22px;
            }

            .greeting {
                font-size: 18px;
                margin: 25px 0;
            }

            .confirm-button {
                padding: 12px 25px;
                font-size: 15px;
            }

            .fallback-section {
                margin: 30px 0;
                padding: 15px;
            }
        }

        /* Dark Mode Styles */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #121212 !important;
                color: #e0e0e0;
            }

            .main-container {
                background-color: #1e1e1e !important;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
            }

            .content-cell {
                background-color: #1e1e1e !important;
                color: #e0e0e0 !important;
            }

            .logo-text {
                color: #ffffff !important;
            }

            .greeting {
                color: #e0e0e0 !important;
            }

            .alternative-text {
                color: #a0a0a0 !important;
            }

            .fallback-section {
                background-color: #2a2a2a !important;
                border-color: #333333 !important;
            }

            .fallback-text {
                color: #b0b0b0 !important;
            }

            .fallback-url {
                background-color: #222222 !important;
                border-color: #444444 !important;
                color: #c0c0c0 !important;
            }

            .footer-section {
                border-top-color: #333333 !important;
            }

            .footer-text {
                color: #999999 !important;
            }

            .company-info {
                color: #777777 !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #333333;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f5f5f5; min-width: 100%;">
        <tr>
            <td align="center" valign="top" style="padding: 30px 0;">
                <table class="main-container" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; border-spacing: 0; border-collapse: collapse; background-color: #ffffff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                    <tr>
                        <td class="content-cell" style="padding: 40px 30px; background-color: #ffffff; color: #333333;">
                            <div class="logo-container">
                                <div class="logo-text">
                                    REPOSTCHAIN
                                </div>
                            </div>

                            <div class="greeting">
                                <strong>{{ $name }}</strong>, please confirm your email address
                            </div>

                            <a href="{{ route('user.email.verify', ['id' => $id, 'token' => $token]) }}" class="confirm-button">
                                Confirm Email In to Newsletter
                            </a>

                            <div class="fallback-section">
                                <div class="fallback-text">
                                    If the link above isn't working, please copy and paste the following into your
                                    browser:
                                </div>
                                <div class="fallback-url">
                                    {{ config('app.url') }}/confirm/email?id={{ $id }}&token={{ $token }}
                                </div>
                            </div>

                            <div class="footer-section">

                                <div class="footer-text">
                                    You are receiving this email to keep you informed of important changes to your
                                    <strong>RepostChain</strong> account
                                </div>

                                {{-- <div class="company-info">
                                    <strong>RepostChain</strong> is operated by Audiu Ltd. • Unit 4a, Tileyard
                                    Studios, London, N7 9AH, UK
                                </div> --}}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
