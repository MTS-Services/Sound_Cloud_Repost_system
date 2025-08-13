<?php

use App\Models\Order;
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
    $image = asset('https://i.imgur.com/IpH9g5Q.png');
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

function soundcloud_image($url)
{
    $image = asset('default_img/no_img.jpg');
    return $url ? $url : $image;
}

function auth_storage_url($url)
{
    $image = asset('default_img/other.png');
    return $url ? $url : $image;
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

function repostPrice($user = null)
{

    $commentPrice = 2;
    $likePrice = 2;

    $total = $commentPrice + $likePrice;
    if (!$user) {
        $user = user();
    }
    $user->load('userInfo');
    $followers_count = $user?->userInfo?->followers_count;
    if ($followers_count === null) {
        return 1 + $total; // Default to 1 if followers count is not available
    }
    return ceil($followers_count / 100) + $total ?: 1 + $total; // Ensure at least 1 credit
}

function userCredits($user = null)
{
    if (!$user) {
        $user = user();
    }
    $user->load(['succedDebitTransactions', 'succedCreditTransactions']);
    $debit = $user->debitTransactions->sum('credits');
    $credit = $user->creditTransactions->sum('credits');
    return $debit - $credit;
}

function SouceClassName($className)
{
    $className = basename(str_replace('\\', '/', $className));
    return trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $className));
}


function generateOrderID()
{

    $prefix = 'ORDER-';

    $microseconds = explode(' ', microtime(true))[0];

    $date = date('ymd');
    $time = date('is');

    return $prefix . $date . $time . mt_rand(10000, 99999);
}
function totalReposts($campaign = null)
{
    if (!$campaign) {
        $campaign = 0;
    }
    return $campaign->reposts()->count();
}

// Get all genres
function AllGenres()
{
    $genres = [
        "Alternative Rock",
        "Ambient",
        "Classical",
        "Country",
        "Dance & EDM",
        "Dancehall",
        "Deep House",
        "Disco",
        "Drum & Bass",
        "Dubstep",
        "Electronic",
        "Folk & Singer-Songwriter",
        "Hip-hop & Rap",
        "House",
        "Indie",
        "Jazz & Blues",
        "Latin",
        "Metal",
        "Piano",
        "Pop",
        "R&B & Soul",
        "Reggae",
        "Reggaeton",
        "Rock",
        "Soundtrack",
        "Speech",
        "Techno",
        "Trance",
        "Trap",
        "Triphop",
        "world",
        "all audio genres",
        "audiobooks",
        "business",
        "comedy",
        "entertainment",
        "learning",
        "news & politics",
        "religion & spirituality",
        "science",
        "sports",
        "Storytelling",
        "Technology",
    ];

    return $genres;
}
