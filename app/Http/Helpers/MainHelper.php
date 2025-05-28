<?php

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

function timeFormat($time)
{
    return date(('d M, Y H:i A'), strtotime($time));
}
function timeFormatHuman($time)
{
    return Carbon::parse($time)->diffForHumans();
}
function admin()
{
    return Auth::guard('admin')->user();
}

// function adminFirstName()
// {
//     return Auth::guard('admin')->user()->first_name;
// }

// function adminLastName()
// {
//     return Auth::guard('admin')->user()->last_name;
// }

// function adminFullName()
// {
//     return Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name;
// }

function user()
{
    return Auth::guard('web')->user();
}

// function userFirstName()
// {
//     return Auth::guard('web')->user()->first_name;
// }

// function userLastName()
// {
//     return Auth::guard('web')->user()->last_name;
// }

// function userFullName()
// {
//     return Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->last_name;
// }

// function creater_name($user)
// {
//     return $user->full_name ?? 'System';
// }

// function updater_name($user)
// {
//     return $user->full_name ?? 'Null';
// }

// function deleter_name($user)
// {
//     return $user->full_name ?? 'Null';
// }

function creater_name($user)
{
    return $user->name ?? 'System';
}

function updater_name($user)
{
    return $user->name ?? 'Null';
}

function deleter_name($user)
{
    return $user->name ?? 'Null';
}

function isSuperAdmin()
{
    return auth()->guard('admin')->user()->role->name == 'Super Admin';
}

// function createCSV($filename = 'permissions.csv'): string
// {
//     $permissions = Permission::all(['name', 'guard_name', 'prefix']);

//     $csvPath = public_path('csv/' . $filename);
//     // Ensure the directory exists
//     File::ensureDirectoryExists(dirname($csvPath));
//     // Create the CSV writer
//     $csv = Writer::createFromPath($csvPath, 'w+');
//     // Insert header
//     $csv->insertOne(['name', 'guard_name', 'prefix']);
//     // Insert records
//     foreach ($permissions as $permission) {
//         $csv->insertOne([
//             $permission->name,
//             $permission->guard_name,
//             $permission->prefix,
//         ]);
//     }
//     return $csvPath;
// }


function slugToTitle($slug)
{
    return Str::replace('-', ' ', $slug);
}
function storage_url($urlOrArray)
{
    $image = asset('default_img/no_img.jpg');
    if (is_array($urlOrArray) || is_object($urlOrArray)) {
        $result = '';
        $count = 0;
        $itemCount = count($urlOrArray);
        foreach ($urlOrArray as $index => $url) {

            $result .= $url ? (Str::startsWith($url, 'https://') ? $url : asset('storage/' . $url)) : $image;


            if ($count === $itemCount - 1) {
                $result .= '';
            } else {
                $result .= ', ';
            }
            $count++;
        }
        return $result;
    } else {
        return $urlOrArray ? (Str::startsWith($urlOrArray, 'https://') ? $urlOrArray : asset('storage/' . $urlOrArray)) : $image;
    }
}

function auth_storage_url($url, $gender = false)
{
    $image = asset('default_img/other-1.png');
    if ($gender == 1) {
        $image = asset('default_img/male-1.jpeg');
    } elseif ($gender == 2) {
        $image = asset('default_img/female-1.jpg');
    }
    return $url ? asset('storage/' . $url) : $image;
}
function getSubmitterType($className)
{
    $className = basename(str_replace('\\', '/', $className));
    return trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $className));
}

function availableTimezones()
{
    $timezones = [];
    $timezoneIdentifiers = DateTimeZone::listIdentifiers();

    foreach ($timezoneIdentifiers as $timezoneIdentifier) {
        $timezone = new DateTimeZone($timezoneIdentifier);
        $offset = $timezone->getOffset(new DateTime());
        $offsetPrefix = $offset < 0 ? '-' : '+';
        $offsetFormatted = gmdate('H:i', abs($offset));

        $timezones[] = [
            'timezone' => $timezoneIdentifier,
            'name' => "(UTC $offsetPrefix$offsetFormatted) $timezoneIdentifier",
        ];
    }

    return $timezones;
}
function isImage($path)
{
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return in_array($extension, $imageExtensions);
}
