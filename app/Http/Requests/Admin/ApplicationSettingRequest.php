<?php

namespace App\Http\Requests\Admin;

use App\Models\ApplicationSetting as App;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // General Settings 
            'application_name'          => 'sometimes|nullable|string|min:3|max:255',
            'library_name'              => 'sometimes|required|string|min:3|max:255',
            'application_short_name'        => 'sometimes|required|string|max:200',
            'timezone'                  => 'sometimes|required|string',
            'date_format'               => 'sometimes|required|string',
            'time_format'               => 'sometimes|required|string',
            'favicon' => 'sometimes|required|image|mimes:jpeg,png,jpg,webp,svg|max:2048|dimensions:max_width=16,max_height=16',
            'app_logo' => 'sometimes|required|image|mimes:jpeg,png,jpg,webp,svg|max:2048|dimensions:max_width=400,max_height=400',
            'theme_mode'                => 'sometimes|required|string',
            Rule::in([App::THEME_MODE_LIGHT, App::THEME_MODE_DARK, App::THEME_MODE_SYSTEM]),
            'public_registration'       => 'sometimes|required|integer',
            Rule::in([App::ALLOW_PUBLIC_REGISTRATION, App::DENY_PUBLIC_REGISTRATION]),
            'registration_approval'     => 'sometimes|required|integer',
            Rule::in([App::REGISTRATION_APPROVAL_AUTO, App::REGISTRATION_APPROVAL_MANUAL]),
            'environment'               => 'sometimes|required|string',
            Rule::in([App::ENVIRONMENT_DEVELOPMENT, App::ENVIRONMENT_PRODUCTION]),
            'app_debug'                 => 'sometimes|required|integer',
            Rule::in([App::APP_DEBUG_TRUE, App::APP_DEBUG_FALSE]),
            'debugbar'                  => 'sometimes|required|integer',
            Rule::in([App::ENABLE_DEBUGBAR, App::DISABLE_DEBUGBAR]),

            // Database Settings
            'database_driver'           => 'sometimes|required|string',
            Rule::in([App::DATATBASE_DRIVER_MYSQL, App::DATATBASE_DRIVER_PGSQL, App::DATATBASE_DRIVER_SQLITE, App::DATATBASE_DRIVER_SQLSRV]),
            'database_host'             => 'sometimes|nullable|string',
            'database_port'             => 'sometimes|nullable|string',
            'database_name'             => 'sometimes|nullable|string',
            'database_username'         => 'sometimes|nullable|string',
            'database_password'         => 'sometimes|nullable|string',


            // SMTP Setup Settings
            'smtp_host'                 => 'sometimes|nullable|string',
            'smtp_port'                 => 'sometimes|nullable|string',
            'smtp_username'             => 'sometimes|nullable|string',
            'smtp_password'             => 'sometimes|nullable|string',
            'smtp_encryption'           => 'sometimes|nullable|string',
            Rule::in([App::SMTP_ENCRYPTION_TLS, App::SMTP_ENCRYPTION_SSL, App::SMTP_ENCRYPTION_NONE]),
            'smtp_driver'               => 'sometimes|nullable|string',
            Rule::in([App::SMTP_DRIVER_MAILER, App::SMTP_DRIVER_MAILGUN, App::SMTP_DRIVER_SES, App::SMTP_DRIVER_POSTMARK, App::SMTP_DRIVER_SENDMAIL]),
            'smtp_from_address'         => 'sometimes|nullable|string',
            'smtp_from_name'            => 'sometimes|nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [

            // General Settings
            'application_name'          => 'Institution Name',
            'library_name'              => 'Library Name',
            'application_short_name'        => 'Library Short Name',
            'timezone'                  => 'Timezone',
            'date_format'               => 'Date Format',
            'time_format'               => 'Time Format',
            'favicon'                   => 'Favicon',
            'app_logo'                  => 'App Logo',
            'theme_mode'                => 'Theme Mode',
            'public_registration'       => 'Public Registration',
            'registration_approval'     => 'Registration Approval',
            'environment'               => 'Environment',
            'app_debug'                 => 'App Debug',
            'debugbar'                  => 'Debugbar',

            // Database Settings
            'database_driver'           => 'Database Driver',
            'database_host'             => 'Database Host',
            'database_port'             => 'Database Port',
            'database_name'             => 'Database Name',
            'database_username'         => 'Database Username',
            'database_password'         => 'Database Password',

            // SMTP Setup Settings
            'smtp_host'                 => 'SMTP Host',
            'smtp_port'                 => 'SMTP Port',
            'smtp_username'             => 'SMTP Username',
            'smtp_password'             => 'SMTP Password',
            'smtp_encryption'           => 'SMTP Encryption',
            'smtp_driver'               => 'SMTP Driver',
            'smtp_from_address'         => 'SMTP From Address',
            'smtp_from_name'            => 'SMTP From Name',
        ];
    }
}
