<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApplicationSettingRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Http\Traits\FileManagementTrait;
use App\Models\ApplicationSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Throwable;

class ApplicationSettingController extends Controller implements HasMiddleware
{
    use AuditRelationTraits, FileManagementTrait;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->back();
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:application-setting-general', only: ['general']),
            new Middleware('permission:application-setting-database', only: ['database']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function general(): View
    {
        $data['general_settings'] = ApplicationSetting::whereIn('key', ['application_name', 'library_name', 'application_short_name', 'timezone', 'date_format', 'time_format', 'theme_mode', 'app_logo', 'favicon', 'public_registration', 'registration_approval', 'environment', 'app_debug', 'debugbar'])->pluck('value', 'key')->all();
        $data['timezones'] = availableTimezones();
        return view('backend.admin.application-settings.general', $data);
    }

    public function database(): View
    {

        $data['database_settings'] = ApplicationSetting::whereIn('key', ['database_driver', 'database_host', 'database_port', 'database_name', 'database_username', 'database_password'])->pluck('value', 'key')->all();
        return view('backend.admin.application-settings.database', $data);
    }

    public function smtp(): View
    {

        $data['smtp_settings'] = ApplicationSetting::whereIn('key', ['smtp_driver', 'smtp_host', 'smtp_port', 'smtp_encryption', 'smtp_username', 'smtp_password', 'smtp_from_address', 'smtp_from_name'])->pluck('value', 'key')->all();
        return view('backend.admin.application-settings.smtp', $data);
    }

    public function updateSettings(ApplicationSettingRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $envPath = base_path('.env');
            $env = file($envPath);

            foreach ($validated as $key => $value) {
                if ($key == 'app_logo' || $key == 'favicon') {
                    $value = $this->handleFileUpload($value, 'application_settings', $key);
                }

                $applicationSetting = ApplicationSetting::updateOrCreate(['key' => $key], ['value' => $value]);

                if (!empty($applicationSetting->env_key)) {
                    $env = $this->set($applicationSetting->env_key, '"' . html_entity_decode($value) . '"', $env);
                }
            }
            $fp = fopen($envPath, 'w');
            fwrite($fp, implode($env));
            fclose($fp);
            session()->flash('success', "Settings added successfully.");
            return $this->redirectIndex();
        } catch (Throwable $e) {
            session()->flash('error', "Something went wrong! Please try again.");
            throw $e;
        }
    }

    private function set($key, $value, $env): array
    {
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            if ($entry[0] == $key) {
                $env[$env_key] = $key . "=" . $value . "\n";
            } else {
                $env[$env_key] = $env_value;
            }
        }
        return $env;
    }
}
