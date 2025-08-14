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

function repostPrice($user = null, $commentend = false, $liked = false)
{

    $commentPrice = 0;
    $likePrice = 0;

    if ($commentend) {
        $commentPrice = 2;
    }
    if ($liked) {
        $likePrice = 2;
    }

    $total = $commentPrice + $likePrice;
    if (!$user) {
        $user = user();
    }
    $user->load('userInfo');
    $followers_count = $user?->userInfo?->followers_count;
    if ($followers_count === null) {
        return 1 + $total; // Default to 1 if followers count is not available
    }
    return ceil($followers_count / 100) ?: 1 + $total; // Ensure at least 1 credit
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
        "Alternative Rock" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 15.75l-4.5-4.5m0 0l-4.5-4.5m4.5 4.5l4.5-4.5m-4.5 4.5l-4.5 4.5" /></svg>',
        "Ambient" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25a8.25 8.25 0 100-16.5 8.25 8.25 0 000 16.5z" /></svg>',
        "Classical" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75V21" /></svg>',
        "Country" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5l6-6-6-6" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l6-6-6-6" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.5l6-6-6-6" /></svg>',
        "Dance & EDM" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25v2.25m-4.5-4.5l-2.25 2.25m9 0l2.25 2.25M12 18.75h-.008v.008H12z" /></svg>',
        "Dancehall" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19.5v-4.5m0 0h4.5m-4.5 0l-2.25-2.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /></svg>',
        "Deep House" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "Disco" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a6 6 0 100-12 6 6 0 000 12z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v3m0-21v3m9 9h-3m-15 0h-3" /></svg>',
        "Drum & Bass" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15.75l-6-6m0 0l6-6m-6 6l-6-6m6 6l6-6m-6 6l-6 6" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /></svg>',
        "Dubstep" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6" /></svg>',
        "Electronic" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6" /></svg>',
        "Folk & Singer-Songwriter" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25v2.25m-4.5-4.5l-2.25 2.25m9 0l2.25 2.25M12 18.75h-.008v.008H12z" /></svg>',
        "Hip-hop & Rap" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>',
        "House" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6" /></svg>',
        "Indie" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 12m-3 0a3 3 0 106 0 3 3 0 10-6 0z" /></svg>',
        "Jazz & Blues" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6 6" /></svg>',
        "Latin" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
        "Metal" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25v2.25m-4.5-4.5l-2.25 2.25m9 0l2.25 2.25M12 18.75h-.008v.008H12z" /></svg>',
        "Piano" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /></svg>',
        "Pop" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m0 3a3 3 0 110-6 3 3 0 010 6z" /></svg>',
        "R&B & Soul" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6" /></svg>',
        "Reggae" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6" /></svg>',
        "Reggaeton" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "Rock" => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500 has-[:checked]:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l-2 3M9 19l-2-3M9 19v2a1 1 0 001 1h4a1 1 0 001-1v-2M15 19v-6m0 6l-2-3m2 3l-2 3M15 19l-2-3M15 19v-6M15 19l2-3m-2 3l2 3M15 19v-6m-2 3l2-3m-2 3l2 3M15 19l2-3M15 19l-2 3"/></svg>',
        "Soundtrack" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 15.75l-4.5-4.5m0 0l-4.5-4.5m4.5 4.5l4.5-4.5m-4.5 4.5l-4.5 4.5" /></svg>',
        "Speech" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6" /></svg>',
        "Techno" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "Trance" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "Trap" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "Triphop" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "World" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "all audio genres" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "audiobooks" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "business" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "comedy" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "entertainment" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "learning" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "news & politics" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "religion & spirituality" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "science" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "sports" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "Storytelling" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
        "Technology" => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m-3-3h6" /></svg>',
    ];

    return $genres;
}
