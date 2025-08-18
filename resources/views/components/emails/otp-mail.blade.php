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
    <title>Email Confirmation - Repostchain</title>
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

        /* Complete design overhaul to match Repostchain dark theme */
        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #2a2a2a !important;
            color: #ffffff;
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
            background-color: #2a2a2a;
        }

        .content-cell {
            padding: 40px 30px;
            background-color: #2a2a2a;
            color: #ffffff;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            display: inline-block;
        }

        .logo-accent {
            position: absolute;
            right: -20px;
            top: -5px;
            width: 60px;
            height: 40px;
            background-color: #ff4500;
            clip-path: polygon(0 0, 100% 0, 80% 100%, 0% 100%);
        }

        .greeting {
            font-size: 28px;
            font-weight: 300;
            color: #ffffff;
            text-align: center;
            margin: 40px 0;
            line-height: 1.3;
        }

        .greeting strong {
            font-weight: 400;
        }

        .close-symbol {
            color: #ffffff;
            font-size: 20px;
            margin-left: 5px;
        }

        .confirm-button {
            display: block;
            background-color: #ff4500;
            color: #000000 !important;
            padding: 16px 40px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 30px auto;
            max-width: 400px;
        }

        .alternative-text {
            text-align: center;
            margin: 20px 0 40px 0;
            font-size: 16px;
            color: #cccccc;
        }

        .alternative-link {
            color: #ff4500;
            text-decoration: none;
        }

        .fallback-section {
            margin: 40px 0;
            padding: 20px;
            background-color: #333333;
            border-radius: 6px;
        }

        .fallback-text {
            font-size: 14px;
            color: #cccccc;
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .fallback-url {
            background-color: #444444;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            color: #ffffff;
            word-break: break-all;
            text-align: center;
            border: 1px solid #555555;
        }

        .highlight {
            /* background-color: #ffff00; */
            color: #000000;
            padding: 2px 4px;
        }

        .footer-section {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid #444444;
        }

        .footer-text {
            font-size: 14px;
            color: #888888;
            text-align: center;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .footer-link {
            color: #ff4500;
            text-decoration: none;
        }

        .company-info {
            font-size: 12px;
            color: #666666;
            text-align: center;
            margin-top: 20px;
            line-height: 1.4;
        }

        .company-highlight {
            /* background-color: #ffff00; */
            color: #000000;
            padding: 1px 3px;
        }

        .bottom-logo {
            text-align: center;
            margin-top: 30px;
        }

        .bottom-logo-icon {
            width: 40px;
            height: 30px;
            background-color: #ff4500;
            display: inline-block;
            position: relative;
        }

        .bottom-logo-icon::before {
            content: "⇄";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .content-cell {
                padding: 20px 15px;
            }

            .greeting {
                font-size: 24px;
            }

            .confirm-button {
                padding: 14px 30px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body
    style="margin: 0; padding: 0; background-color: #2a2a2a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #ffffff;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0"
        style="background-color: #2a2a2a; min-width: 100%;">
        <tr>
            <td align="center" valign="top" style="padding: 20px 0;">
                <table class="main-container" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="content-cell">
                            <!-- Repostchain logo with orange accent -->
                            <div class="logo-container">
                                <div class="logo-text">
                                    ⇄ REPOSTchain
                                    {{-- <div class="logo-accent"></div> --}}
                                </div>
                            </div>

                            <!-- Dynamic personalized greeting -->
                            <div class="greeting">
                                <strong>{{ $name }}</strong><span class="close-symbol">✖</span>, please
                                confirm<br>
                                your email address
                            </div>

                            <!-- Main confirmation button, using the new route -->
                            <a href="{{ route('user.email.verify', ['id' => $id, 'token' => $token]) }}" class="confirm-button">
                                Confirm Email and Opt In to Newsletter
                            </a>

                            <!-- Alternative confirmation text, using the new route -->
                            {{-- <div class="alternative-text">
                                Or just <a href="{{ route('user.email.verify', ['id' => $id, 'token' => $token]) }}"
                                    class="alternative-link">confirm email address</a>
                            </div> --}}

                            <!-- Fallback URL section with dynamic token -->
                            <div class="fallback-section">
                                <div class="fallback-text">
                                    If the link above isn't working, please copy and paste the<br>
                                    following into your browser:
                                </div>
                                <div class="fallback-url">
                                    {{ config('app.url') }}/confirm-email?id={{ $id }}&
                                    token={{ $token }}
                                </div>
                            </div>

                            <!-- Footer section -->
                            <div class="footer-section">
                                <div class="footer-text">
                                    You can <span class="highlight">change</span> your preferences at any time in
                                    your<br>
                                    <a href="#" class="footer-link">Settings</a>
                                </div>

                                <div class="footer-text" style="margin-top: 30px;">
                                    You are receiving this email to keep you informed of<br>
                                    important changes your <span class="company-highlight">Repostchain</span> account
                                </div>

                                <div class="company-info">
                                    <span class="company-highlight">Repostchain</span> is operated by Audiu Ltd. • Unit
                                    4a, Tileyard<br>
                                    Studios, London, N7 9AH, UK
                                </div>

                                <div class="bottom-logo">
                                    <div class="bottom-logo-icon"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
