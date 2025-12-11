<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'application_short_name', 'value' => 'LV', 'env_key' => 'APPLICATION_SHORT_NAME'],
            ['key' => 'app_name', 'value' => 'laravel', 'env_key' => 'APP_NAME'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'env_key' => 'TIMEZONE'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'env_key' => 'DATE_FORMAT'],
            ['key' => 'time_format', 'value' => 'H:i:s', 'env_key' => 'TIME_FORMAT'],
            ['key' => 'favicon_dark', 'value' => 'laravel', 'env_key' => 'FAVICON_DARK'],
            ['key' => 'app_logo_dark', 'value' => '', 'env_key' => 'APP_LOGO_DARK'],
            ['key' => 'favicon', 'value' => 'laravel', 'env_key' => 'FAVICON'],
            ['key' => 'app_logo', 'value' => '', 'env_key' => 'APP_LOGO'],
            ['key' => 'theme_mode', 'value' => 'light', 'env_key' => 'THEME_MODE'],
            ['key' => 'public_registration', 'value' => '1', 'env_key' => 'PUBLIC_REGISTRATION'],
            ['key' => 'registration_approval', 'value' => '1', 'env_key' => 'REGISTRATION_APPROVAL'],
            ['key' => 'environment', 'value' => '1', 'env_key' => 'APP_ENV'],
            ['key' => 'app_debug', 'value' => '1', 'env_key' => 'APP_DEBUG'],

            // Database Settings
            ['key' => 'database_driver', 'value' => '1', 'env_key' => 'DB_CONNECTION'],
            ['key' => 'database_host', 'value' => '1', 'env_key' => 'DB_HOST'],
            ['key' => 'database_port', 'value' => '1', 'env_key' => 'DB_PORT'],
            ['key' => 'database_name', 'value' => '1', 'env_key' => 'DB_DATABASE'],
            ['key' => 'database_username', 'value' => '1', 'env_key' => 'DB_USERNAME'],
            ['key' => 'database_password', 'value' => '1', 'env_key' => 'DB_PASSWORD'],

            // SMTP Settings
            ['key' => 'smtp_driver', 'value' => 'smtp', 'env_key' => 'MAIL_MAILER'],
            ['key' => 'smtp_host', 'value' => '1', 'env_key' => 'MAIL_HOST'],
            ['key' => 'smtp_port', 'value' => '1', 'env_key' => 'MAIL_PORT'],
            ['key' => 'smtp_encryption', 'value' => 'tls', 'env_key' => 'MAIL_ENCRYPTION'],
            ['key' => 'smtp_username', 'value' => '123456', 'env_key' => 'MAIL_USERNAME'],
            ['key' => 'smtp_password', 'value' => '123', 'env_key' => 'MAIL_PASSWORD'],
            ['key' => 'smtp_from_address', 'value' => 'superadmin@gmail.com', 'env_key' => 'MAIL_FROM_ADDRESS'],
            ['key' => 'smtp_from_name', 'value' => 'Super Admin', 'env_key' => 'MAIL_FROM_NAME'],

            // Payment Gateway Setup
            ['key' => 'paypal_mode', 'value' => 'sandbox', 'env_key' => 'PAYPAL_MODE'],
            ['key' => 'paypal_key', 'value' => 'AdUa_Fvt0tf9rYbd1412hS_ChPoSbTP9fGj1PblIXwwOsBzLTyD8I2xnRDmT6eNgdBRMtiAAl9yVYYjW', 'env_key' => 'PAYPAL_SANDBOX_CLIENT_ID'],
            ['key' => 'paypal_secret', 'value' => 'ELmbYAx_lItW-Ic1loIHQq7PmXVY2OkwbBTJKQq-GJ58n8WcLn5awnRhN_v9tJP58ULO3hSvzmQ2jDEh', 'env_key' => 'PAYPAL_SANDBOX_CLIENT_SECRET'],
            ['key' => 'paypal_gateway_status', 'value' => ApplicationSetting::PAYMENT_GATEWAY_STATUS_INACTIVE],
            ['key' => 'stripe_mode', 'value' => 'sandbox', 'env_key' => 'STRIPE_MODE'],
            ['key' => 'stripe_key', 'value' => 'pk_test_51RpQw7ISIl8QzoFfXw1fwhUTinGRkt0zak7gkeNkOLlcr8MUPKKGh7mK7yJVideMMfVXSOSqfT5pZCrWlYOjE5dh008ykGM5Yx', 'env_key' => 'STRIPE_KEY'],
            ['key' => 'stripe_secret', 'value' => 'sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx', 'env_key' => 'STRIPE_SECRET'],
            ['key' => 'stripe_gateway_status', 'value' => ApplicationSetting::PAYMENT_GATEWAY_STATUS_ACTIVE],


            // Payment Gateway Setup
            ['key' => 'pusher_id', 'value' => '2034531', 'env_key' => 'PUSHER_APP_ID'],
            ['key' => 'pusher_key', 'value' => '8eb5c7620f85b5d5b6ab', 'env_key' => 'PUSHER_APP_KEY'],
            ['key' => 'pusher_secret', 'value' => '6118a200dbd605240508', 'env_key' => 'PUSHER_APP_SECRET'],
            ['key' => 'pusher_cluster', 'value' => 'ap2', 'env_key' => 'PUSHER_APP_CLUSTER'],
            ['key' => 'pusher_port', 'value' => '443', 'env_key' => 'PUSHER_PORT'],
            ['key' => 'pusher_host', 'value' => '', 'env_key' => 'PUSHER_HOST'],
            ['key' => 'pusher_scheme', 'value' => 'http', 'env_key' => 'PUSHER_SCHEME'],
            ['key' => 'pusher_encrypted', 'value' => 'tls', 'env_key' => 'PUSHER_ENCRYPTED'],
        ];

        foreach ($settings as $setting) {
            ApplicationSetting::create($setting);
        }
    }
}
