<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Responsive Email Template</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
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
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* Base styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f5f5f5 !important;
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
            width: 100% !important;
            height: 100% !important;
        }

        /* Container */
        .email-wrapper {
            width: 100% !important;
            background-color: #f5f5f5;
            padding: 20px 0;
        }

        .email-container {
            width: 100%;
            max-width: 1020px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            width: 100%;
            border-bottom: #99999948 solid 1px;
            padding: 40px 20px;
            text-align: center;
        }

        .logo-container {
            width: 100%;
            text-align: center;
            margin-bottom: 0;
        }

        .logo {
            width: 300px;

            /* background-color: #ffffff;
            border-radius: 50%;
            margin: 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            color: #ff6b35;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            vertical-align: middle; */
        }

        /* Content */
        .content {
            width: 100%;
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            color: #333333;
            margin-bottom: 20px;
            font-weight: 500;
            line-height: 1.3;
        }

        .message-body {
            font-size: 16px;
            color: #555555;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        /* Responsive Table */
        .table-container {
            width: 100%;
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            font-size: 14px;
        }

        .data-table th {
            background-color: #ff6b35;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
        }

        .data-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #eeeeee;
            font-size: 13px;
            white-space: nowrap;
        }

        .data-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Website Info */
        .website-info {
            background-color: #fff5f2;
            padding: 25px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #ff6b35;
            width: 100%;
        }

        .website-info h3 {
            color: #ff6b35;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 600;
        }

        .info-item {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.5;
        }

        .info-label {
            font-weight: bold;
            color: #333333;
            display: inline-block;
            min-width: 120px;
        }

        .info-value {
            color: #666666;
        }

        /* Button */
        .button-container {
            text-align: center;
            margin-bottom: 30px;
            width: 100%;
        }

        .more-details-btn {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b35, #ff8c42);
            color: white !important;
            padding: 12px 25px !important;
            text-decoration: none !important;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            border: none;
            cursor: pointer;
        }

        /* Socket Section */
        .socket-section {
            background-color: #2c3e50;
            color: white;
            padding: 25px 20px;
            text-align: center;
            width: 100%;
        }

        .socket-section h4 {
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 600;
        }

        .socket-grid {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            max-width: 100%;
        }

        .socket-item {
            background-color: #34495e;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 13px;
            flex: 0 1 auto;
            min-width: 120px;
            text-align: center;
        }

        /* Footer */
        .footer {
            border-top: 1px solid rgba(128, 128, 128, 0.397);
            padding: 20px;
            text-align: center;
            font-size: 12px;
            width: 100%;
        }

        .footer a {
            color: #ff6b35 !important;
            text-decoration: none !important;
        }

        .footer-links {
            margin-top: 10px;
        }

        .footer-links a {
            margin: 0 5px;
        }

        /* Mobile Styles */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 10px 0 !important;
            }

            .email-container {
                margin: 0 10px !important;
                border-radius: 5px !important;
            }

            .header {
                padding: 30px 15px !important;
            }

            .logo {
                width: 150px !important;
            }

            .content {
                padding: 30px 15px !important;
            }

            .greeting {
                font-size: 28px !important;
                line-height: 1.4 !important;
            }

            .message-body {
                font-size: 14px !important;
                line-height: 1.6 !important;
            }

            .data-table {
                font-size: 12px !important;
            }

            .data-table th,
            .data-table td {
                padding: 8px 4px !important;
                font-size: 11px !important;
            }

            .website-info {
                padding: 20px 15px !important;
            }

            .website-info h3 {
                font-size: 16px !important;
            }

            .info-item {
                font-size: 13px !important;
                margin-bottom: 10px !important;
                display: block !important;
            }

            .info-label {
                display: block !important;
                min-width: auto !important;
                margin-bottom: 2px !important;
            }

            .info-value {
                display: block !important;
            }

            .more-details-btn {
                padding: 12px 25px !important;
                font-size: 14px !important;
                width: auto !important;
                max-width: 90% !important;
            }

            .socket-section {
                padding: 20px 15px !important;
            }

            .socket-section h4 {
                font-size: 16px !important;
            }

            .socket-grid {
                flex-direction: column !important;
                align-items: center !important;
            }

            .socket-item {
                width: 100% !important;
                max-width: 200px !important;
                min-width: auto !important;
                font-size: 12px !important;
            }
        }

        /* Tablet Styles */
        @media only screen and (min-width: 601px) and (max-width: 768px) {
            .email-container {
                margin: 0 20px !important;
            }

            .content {
                padding: 35px 25px !important;
            }

            .data-table th,
            .data-table td {
                padding: 10px 6px !important;
                font-size: 12px !important;
            }

            .socket-grid {
                justify-content: center !important;
            }

            .socket-item {
                min-width: 110px !important;
            }
        }

        /* High DPI Displays */
        @media only screen and (-webkit-min-device-pixel-ratio: 2),
        only screen and (min-resolution: 192dpi) {
            .logo {
                font-size: 100px !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #ffffff !important;
            }
        }

        /* Outlook specific */
        < !--[if mso]>.data-table {
            width: 100% !important;
        }

        .socket-grid {
            display: table !important;
            width: 100% !important;
        }

        .socket-item {
            display: table-cell !important;
            width: 25% !important;
        }

        < ![endif]-->
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header with Logo 
            <div class="header">
                <div class="logo-container">
                    <a href="{{ url('/') }}">
                        <img src="{{ storage_url(app_setting('app_logo')) }}" alt="{{ config('app.name') }}"
                            class="logo">
                    </a>
                </div>
            </div>-->

            <!-- Main Content -->
            <div class="content">
                <!-- Greeting -->
                <div class="greeting">
                    {{ $title ?? 'Hello there' }},
                </div>

                <!-- Message Body -->
                @isset($body)
                    <div class="message-body">
                        {{ $body ?? 'You have a new notification' }}
                        <br><br>
                        <p>Thank you for being an active part of our community!</p>
                        <br>
                        <p>Best regards,</p>
                        <p>The {{ config('app.name') }} Team</p>
                    </div>
                @endisset

                <!-- Data Table -->
                @isset($tableData)
                    @foreach ($tableData as $data)
                        <table class="data-table" role="table">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#{{ $loop->iteration }}</td>
                                    <td>{{ $data['created_at_formatted'] }}</td>
                                    <td>${{ $data['amount'] }}</td>
                                    <td>{{ $data['status_label'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @endforeach
                @endisset


                <!-- Website Info -->
                @isset($websiteInfo)
                    <div class="website-info">
                        <h3>Website Information</h3>
                        <div class="info-item">
                            <span class="info-label">Website:</span>
                            <span class="info-value">www.repostchain.com</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Support Email:</span>
                            <span class="info-value">support@yourcompany.com</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">+1 (555) 123-4567</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Business Hours:</span>
                            <span class="info-value">Monday - Friday, 9:00 AM - 6:00 PM EST</span>
                        </div>
                    </div>
                @endisset

                <!-- More Details Button -->
                @isset($url)
                    <div class="button-container">
                        <a href="{{ $url ?? '#' }}" class="more-details-btn">View More Details</a>
                    </div>
                @endisset
            </div>

            {{-- <!-- Socket Section -->
            <div class="socket-section">
                <h4>Connection Information</h4>
                <div class="socket-grid">
                    <div class="socket-item">Server: US-East-1</div>
                    <div class="socket-item">Port: 8443</div>
                    <div class="socket-item">Protocol: HTTPS</div>
                    <div class="socket-item">Status: Connected</div>
                </div>
            </div> --}}

            <!-- Footer -->
            <div class="footer">
                <p>&copy; 2025 Your Company Name. All rights reserved.</p>
                <div class="footer-links">
                    <a href="#">Unsubscribe</a> |
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
