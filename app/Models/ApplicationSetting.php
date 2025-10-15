<?php

namespace App\Models;

use App\Models\BaseModel;

class ApplicationSetting extends BaseModel
{
    protected $fillable = ['key', 'value', 'env_key'];

    public const ALLOW_PUBLIC_REGISTRATION = 1;
    public const DENY_PUBLIC_REGISTRATION = 2;

    public static function getPublicRegistrationInfos()
    {
        return [
            self::ALLOW_PUBLIC_REGISTRATION => 'Allow',
            self::DENY_PUBLIC_REGISTRATION => 'Deny',
        ];
    }



    public const PAYMENT_GATEWAY_SANDBOX = 'sandbox';
    public const PAYMENT_GATEWAY_LIVE = 'live';

    public const PAYMENT_GATEWAY_MODES = [
        self::PAYMENT_GATEWAY_SANDBOX => 'Sandbox',
        self::PAYMENT_GATEWAY_LIVE => 'Live',
    ];


    public const PAYMENT_GATEWAY_STATUS_ACTIVE = '1';
    public const PAYMENT_GATEWAY_STATUS_INACTIVE = '0';

    public const PAYMENT_GATEWAY_STATUSES = [
        self::PAYMENT_GATEWAY_STATUS_ACTIVE => 'Active',
        self::PAYMENT_GATEWAY_STATUS_INACTIVE => 'Inactive',
    ];

    // public function getPublicRegistrationLabelAttribute()
    // {
    //     return $this->key == 'public_registration' ? self::getPublicRegistrationInfos()[$this->value] : 'Unknown';
    // }

    public const REGISTRATION_APPROVAL_AUTO = 1;
    public const REGISTRATION_APPROVAL_MANUAL = 2;

    public static function getRegistrationApprovalInfos()
    {
        return [
            self::REGISTRATION_APPROVAL_AUTO => 'Auto',
            self::REGISTRATION_APPROVAL_MANUAL => 'Manual',
        ];
    }

    // public function getRegistrationApprovalLabelAttribute()
    // {
    //     return $this->key == 'registration_approval' ? self::getRegistrationApprovalInfos()[$this->value] : 'Unknown';
    // }

    public const ENVIRONMENT_PRODUCTION = 'production';
    public const ENVIRONMENT_DEVELOPMENT = 'local';

    public static function getEnvironmentInfos()
    {
        return [
            self::ENVIRONMENT_PRODUCTION => 'Production',
            self::ENVIRONMENT_DEVELOPMENT => 'Local',
        ];
    }

    // public function getEnvironmentLabelAttribute()
    // {
    //     return $this->key == 'environment' ? self::getEnvironmentInfos()[$this->value] : 'Unknown';
    // }

    public const APP_DEBUG_TRUE = 1;
    public const APP_DEBUG_FALSE = 0;

    public static function getAppDebugInfos()
    {
        return [
            self::APP_DEBUG_FALSE => 'False',
            self::APP_DEBUG_TRUE => 'True',
        ];
    }

    // public function getAppDebugLabelAttribute()
    // {
    //     return $this->key == 'app_debug' ? self::getAppDebugInfos()[$this->value] : 'Unknown';
    // }

    public const ENABLE_DEBUGBAR = 1;
    public const DISABLE_DEBUGBAR = 0;

    public static function getDebugbarInfos()
    {
        return [
            self::DISABLE_DEBUGBAR => 'False',
            self::ENABLE_DEBUGBAR => 'True',
        ];
    }

    public const PUSHER_CLUSTER_AP1 = 'ap1';
    public const PUSHER_CLUSTER_AP2 = 'ap2';
    public const PUSHER_CLUSTER_AP3 = 'ap3';
    public const PUSHER_CLUSTER_AP4 = 'ap4';
    public const PUSHER_CLUSTER_EU = 'eu';
    public const PUSHER_CLUSTER_EU_WEST_1 = 'eu-west-1';
    public const PUSHER_CLUSTER_MT1 = 'mt1';
    public const PUSHER_CLUSTER_SA1 = 'sa1';
    public const PUSHER_CLUSTER_US2 = 'us2';
    public const PUSHER_CLUSTER_US3 = 'us3';


    public const PUSHER_CLUSTERS = [
        self::PUSHER_CLUSTER_AP1 => 'Asia Pacific 1 (ap1)',
        self::PUSHER_CLUSTER_AP2 => 'Asia Pacific 2 (ap2)',
        self::PUSHER_CLUSTER_AP3 => 'Asia Pacific 3 (ap3)',
        self::PUSHER_CLUSTER_AP4 => 'Asia Pacific 4 (ap4)',
        self::PUSHER_CLUSTER_EU => 'Europe (eu)',
        self::PUSHER_CLUSTER_EU_WEST_1 => 'Europe West 1 (eu-west-1)',
        self::PUSHER_CLUSTER_MT1 => 'Montreal (mt1)',
        self::PUSHER_CLUSTER_SA1 => 'South America 1 (sa1)',
        self::PUSHER_CLUSTER_US2 => 'US East 2 (us2)',
        self::PUSHER_CLUSTER_US3 => 'US East 3 (us3)',
    ];

    public const PUSHER_ENCRYPTION_TLS = 'tls';
    public const PUSHER_ENCRYPTION_SSL = 'ssl';
    public const PUSHER_ENCRYPTION_NONE = 'none';

    public const PUSHER_ENCRYPTIONS = [
        self::PUSHER_ENCRYPTION_TLS => 'TLS',
        self::PUSHER_ENCRYPTION_SSL => 'SSL',
        self::PUSHER_ENCRYPTION_NONE => 'None',
    ];

    public const PUSHER_SCHEME_HTTP = 'http';
    public const PUSHER_SCHEME_HTTPS = 'https';
    public const PUSHER_SCHEMES = [
        self::PUSHER_SCHEME_HTTP => 'HTTP',
        self::PUSHER_SCHEME_HTTPS => 'HTTPS',
    ];



    // public function getDebugbarLabelAttribute()
    // {
    //     return $this->key == 'debugbar' ? self::getDebugbarInfos()[$this->value] : 'Unknown';
    // }

    public const DATE_FORMAT_ONE = 'Y-m-d';
    public const DATE_FORMAT_TWO = 'd/m/Y';
    public const DATE_FORMAT_THREE = 'm/d/Y';

    public static function getDateFormatInfos()
    {
        return [
            self::DATE_FORMAT_ONE => 'YYYY-MM-DD',
            self::DATE_FORMAT_TWO => 'DD/MM/YYYY',
            self::DATE_FORMAT_THREE => 'MM/DD/YYYY',
        ];
    }

    // public function getDateFormatLabelAttribute()
    // {
    //     return $this->key == 'date_format' ? self::getDateFormatInfos()[$this->value] : 'Unknown';
    // }

    public const TIME_FORMAT_12 = 'H:i:s';
    public const TIME_FORMAT_24 = 'H:i:s A';

    public static function getTimeFormatInfos()
    {
        return [
            self::TIME_FORMAT_12 => '12-hour format (HH:mm:ss AM/PM)',
            self::TIME_FORMAT_24 => '24-hour format (HH:mm:ss)',
        ];
    }

    // public function getTimeFormatLabelAttribute()
    // {
    //     return $this->key == 'time_format' ? self::getTimeFormatInfos()[$this->value] : 'Unknown';
    // }

    public const THEME_MODE_SYSTEM = 'system';
    public const THEME_MODE_LIGHT = 'light';
    public const THEME_MODE_DARK = 'dark';

    public static function getThemeModeInfos()
    {
        return [
            self::THEME_MODE_SYSTEM => 'System',
            self::THEME_MODE_LIGHT => 'Light',
            self::THEME_MODE_DARK => 'Dark',
        ];
    }

    // public function getThemeModeLabelAttribute()
    // {
    //     return $this->key == 'theme_mode' ? self::getThemeInfos()[$this->value] : 'Unknown';
    // }

    public const DATATBASE_DRIVER_MYSQL = 'mysql';
    public const DATATBASE_DRIVER_PGSQL = 'pgsql';
    public const DATATBASE_DRIVER_SQLITE = 'sqlite';
    public const DATATBASE_DRIVER_SQLSRV = 'sqlsrv';

    public static function getDatabaseDriverInfos()
    {
        return [
            self::DATATBASE_DRIVER_MYSQL => 'MySQL',
            self::DATATBASE_DRIVER_PGSQL => 'PostgreSQL',
            self::DATATBASE_DRIVER_SQLITE => 'SQLite',
            self::DATATBASE_DRIVER_SQLSRV => 'SQL Server',
        ];
    }

    public const SMTP_DRIVER_MAILER = 'smtp';
    public const SMTP_DRIVER_SENDMAIL = 'sendmail';
    public const SMTP_DRIVER_MAILGUN = 'mailgun';
    public const SMTP_DRIVER_SES = 'ses';
    public const SMTP_DRIVER_POSTMARK = 'postmark';

    public static function getSmtpDriverInfos()
    {
        return [
            self::SMTP_DRIVER_MAILER => 'SMTP Mailer',
            self::SMTP_DRIVER_SENDMAIL => 'Sendmail Mailer',
            self::SMTP_DRIVER_MAILGUN => 'Mailgun Mailer',
            self::SMTP_DRIVER_SES => 'Amazon SES',
            self::SMTP_DRIVER_POSTMARK => 'Postmark Mailer',
        ];
    }

    public const SMTP_ENCRYPTION_NONE = 'none';
    public const SMTP_ENCRYPTION_TLS = 'tls';
    public const SMTP_ENCRYPTION_SSL = 'ssl';

    public static function getSmtpEncryptionInfos()
    {
        return [
            self::SMTP_ENCRYPTION_NONE => 'None',
            self::SMTP_ENCRYPTION_TLS => 'TLS',
            self::SMTP_ENCRYPTION_SSL => 'SSL',
        ];
    }
}
