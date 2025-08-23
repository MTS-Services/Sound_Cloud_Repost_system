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
            ['key' => 'library_name', 'value' => 'Laravel', 'env_key' => 'APP_NAME'],
            ['key' => 'application_short_name', 'value' => 'LV', 'env_key' => 'APP_SORT_NAME'],
            ['key' => 'application_name', 'value' => 'laravel', 'env_key' => 'COMPANY_NAME'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'env_key' => 'TIMEZONE'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'env_key' => 'DATE_FORMAT'],
            ['key' => 'time_format', 'value' => 'H:i:s', 'env_key' => 'TIME_FORMAT'],
            ['key' => 'favicon', 'value' => 'laravel', 'env_key' => 'FAVICON'],
            ['key' => 'app_logo', 'value' => '', 'env_key' => 'APP_LOGO'],
            ['key' => 'theme_mode', 'value' => 'light', 'env_key' => 'THEME_MODE'],
            ['key' => 'public_registration', 'value' => '1', 'env_key' => 'PUBLIC_REGISTRATION'],
            ['key' => 'registration_approval', 'value' => '1', 'env_key' => 'REGISTRATION_APPROVAL'],
            ['key' => 'environment', 'value' => '1', 'env_key' => 'APP_ENV'],
            ['key' => 'app_debug', 'value' => '1', 'env_key' => 'APP_DEBUG'],
            ['key' => 'debugbar', 'value' => '1', 'env_key' => 'DEBUGBAR'],

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
        ];

        foreach ($settings as $setting) {
            ApplicationSetting::create($setting);
        }
    }
}
