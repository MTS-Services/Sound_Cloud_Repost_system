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
        "Alternative Rock" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="altrock" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff6b35"/><stop offset="100%" style="stop-color:#f7931e"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#altrock)"/><path d="M8 8l8 8M16 8l-8 8" stroke="#2d3436" stroke-width="2" stroke-linecap="round"/></svg>',

        "Ambient" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><radialGradient id="ambient"><stop offset="0%" style="stop-color:#667eea"/><stop offset="50%" style="stop-color:#764ba2"/><stop offset="100%" style="stop-color:#f093fb"/></radialGradient></defs><circle cx="12" cy="12" r="10" fill="url(#ambient)" opacity="0.8"/><circle cx="12" cy="12" r="6" fill="none" stroke="#00d4ff" stroke-width="1" opacity="0.6"/><circle cx="12" cy="12" r="3" fill="#00d4ff" opacity="0.4"/></svg>',

        "Classical" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="classical" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#c9a96e"/><stop offset="100%" style="stop-color:#8b6914"/></linearGradient></defs><rect width="24" height="24" fill="url(#classical)" rx="3"/><path d="M8 6v12M16 6v12M8 12h8" stroke="#2d3436" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="8" r="2" fill="#2d3436"/></svg>',

        "Country" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="country" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#d4a574"/><stop offset="100%" style="stop-color:#8b4513"/></linearGradient></defs><circle cx="12" cy="12" r="11" fill="url(#country)"/><path d="M6 12c2-4 4-4 6 0s4 4 6 0" fill="none" stroke="#2d3436" stroke-width="2"/><circle cx="12" cy="7" r="2" fill="#2d3436"/><path d="M10 16h4v2h-4z" fill="#2d3436"/></svg>',

        "Dance & EDM" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="edm" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff0080"/><stop offset="50%" style="stop-color:#ff8c00"/><stop offset="100%" style="stop-color:#40e0d0"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#edm)"/><rect x="8" y="14" width="2" height="6" fill="#2d3436" rx="1"/><rect x="11" y="10" width="2" height="10" fill="#2d3436" rx="1"/><rect x="14" y="8" width="2" height="12" fill="#2d3436" rx="1"/></svg>',

        "Dancehall" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="dancehall" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="50%" style="stop-color:#ff6347"/><stop offset="100%" style="stop-color:#32cd32"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#dancehall)"/><path d="M8 10l4 4 4-4" fill="#2d3436"/><circle cx="12" cy="16" r="2" fill="#2d3436"/></svg>',

        "Deep House" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="deephouse" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#1a1a2e"/><stop offset="50%" style="stop-color:#16213e"/><stop offset="100%" style="stop-color:#0f3460"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#deephouse)"/><circle cx="12" cy="12" r="6" fill="none" stroke="#00d4ff" stroke-width="2"/><circle cx="12" cy="12" r="2" fill="#00d4ff"/></svg>',

        "Disco" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="disco" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff1493"/><stop offset="25%" style="stop-color:#ff69b4"/><stop offset="50%" style="stop-color:#dda0dd"/><stop offset="75%" style="stop-color:#9370db"/><stop offset="100%" style="stop-color:#8a2be2"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#disco)"/><g transform="translate(12,12)"><rect x="-1" y="-8" width="2" height="4" fill="#ffd700" transform="rotate(0)"/><rect x="-1" y="-8" width="2" height="4" fill="#ffd700" transform="rotate(45)"/><rect x="-1" y="-8" width="2" height="4" fill="#ffd700" transform="rotate(90)"/><rect x="-1" y="-8" width="2" height="4" fill="#ffd700" transform="rotate(135)"/></g></svg>',

        "Drum & Bass" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="dnb" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff4757"/><stop offset="100%" style="stop-color:#c44569"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#dnb)"/><path d="M6 12l6-6 6 6-6 6z" fill="#2d3436"/><circle cx="12" cy="12" r="2" fill="url(#dnb)"/></svg>',

        "Dubstep" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="dubstep" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00ff88"/><stop offset="100%" style="stop-color:#00aa55"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#dubstep)"/><path d="M8 8h8l-4 8z" fill="#2d3436"/><rect x="6" y="16" width="12" height="2" fill="#2d3436"/></svg>',

        "Electronic" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="electronic" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00f5ff"/><stop offset="100%" style="stop-color:#0080ff"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#electronic)"/><rect x="6" y="10" width="12" height="4" fill="#2d3436" rx="2"/><circle cx="8" cy="12" r="1" fill="url(#electronic)"/><circle cx="12" cy="12" r="1" fill="url(#electronic)"/><circle cx="16" cy="12" r="1" fill="url(#electronic)"/></svg>',

        "Folk & Singer-Songwriter" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="folk" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#8b4513"/><stop offset="100%" style="stop-color:#daa520"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#folk)"/><ellipse cx="12" cy="14" rx="6" ry="4" fill="#2d3436"/><rect x="11" y="6" width="2" height="8" fill="#2d3436"/><circle cx="12" cy="8" r="1" fill="url(#folk)"/></svg>',

        "Hip-hop & Rap" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="hiphop" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff6347"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#hiphop)"/><path d="M8 8v8l8-4z" fill="#2d3436"/><rect x="6" y="15" width="12" height="2" fill="#2d3436"/></svg>',

        "House" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="house" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff6b9d"/><stop offset="100%" style="stop-color:#c44569"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#house)"/><rect x="8" y="8" width="8" height="8" fill="#2d3436" rx="1"/><rect x="10" y="10" width="4" height="2" fill="url(#house)"/><rect x="10" y="14" width="4" height="2" fill="url(#house)"/></svg>',

        "Indie" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="indie" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff7675"/><stop offset="100%" style="stop-color:#fd79a8"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#indie)"/><circle cx="12" cy="12" r="4" fill="none" stroke="#2d3436" stroke-width="2"/><circle cx="12" cy="12" r="1" fill="#2d3436"/></svg>',

        "Jazz & Blues" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="jazz" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#jazz)"/><path d="M8 8c0 4 8 4 8 8" fill="none" stroke="#ffd700" stroke-width="2"/><circle cx="16" cy="16" r="2" fill="#ffd700"/></svg>',

        "Latin" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="latin" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff6b35"/><stop offset="50%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff1744"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#latin)"/><path d="M9 9c0 3 6 3 6 6" fill="none" stroke="#2d3436" stroke-width="2"/><circle cx="15" cy="15" r="1.5" fill="#2d3436"/><circle cx="9" cy="9" r="1.5" fill="#2d3436"/></svg>',

        "Metal" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="metal" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#metal)"/><path d="M6 10l6 8 6-8-6-4z" fill="#ff0000"/><path d="M10 8l4 0 2 4-4 0z" fill="#ffd700"/></svg>',

        "Piano" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="piano" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#f1f2f6"/><stop offset="100%" style="stop-color:#ddd"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#piano)" stroke="#2d3436" stroke-width="1"/><rect x="7" y="8" width="10" height="8" fill="#e8e8e8" stroke="#2d3436"/><rect x="9" y="8" width="1" height="5" fill="#2d3436"/><rect x="11" y="8" width="1" height="5" fill="#2d3436"/><rect x="14" y="8" width="1" height="5" fill="#2d3436"/></svg>',

        "Pop" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="pop" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff9ff3"/><stop offset="50%" style="stop-color:#f368e0"/><stop offset="100%" style="stop-color:#ff3838"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#pop)"/><path d="M12 6l1.5 4.5h4.5l-3.75 2.5L15.75 18 12 15.5 8.25 18l1.5-4.5L6 11h4.5z" fill="#ffd700"/><circle cx="12" cy="12" r="3" fill="url(#pop)"/></svg>',

        "R&B & Soul" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="rnb" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#8e44ad"/><stop offset="100%" style="stop-color:#9b59b6"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#rnb)"/><path d="M8 10c0-2 8-2 8 0v4c0 2-8 2-8 0z" fill="#ffd700"/><circle cx="12" cy="12" r="2" fill="url(#rnb)"/></svg>',

        "Reggae" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="reggae" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff1744"/><stop offset="33%" style="stop-color:#ffd600"/><stop offset="66%" style="stop-color:#00e676"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#reggae)"/><path d="M6 12h12" stroke="#2d3436" stroke-width="2"/><path d="M9 9l6 6" stroke="#2d3436" stroke-width="2"/><path d="M15 9l-6 6" stroke="#2d3436" stroke-width="2"/></svg>',

        "Reggaeton" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="reggaeton" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff6b35"/><stop offset="100%" style="stop-color:#ff1744"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#reggaeton)"/><rect x="8" y="9" width="8" height="6" fill="#2d3436" rx="1"/><rect x="10" y="11" width="4" height="2" fill="url(#reggaeton)"/></svg>',

        "Rock" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="rock" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#rock)"/><path d="M8 16l8-8M8 8l8 8" stroke="#ff4757" stroke-width="3" stroke-linecap="round"/><circle cx="12" cy="12" r="2" fill="#ff4757"/></svg>',

        "Soundtrack" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="soundtrack" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff6347"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#soundtrack)"/><rect x="8" y="8" width="8" height="8" fill="#2d3436" rx="1"/><path d="M10 10l4 4M14 10l-4 4" stroke="url(#soundtrack)" stroke-width="2"/></svg>',

        "Speech" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="speech" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00b894"/><stop offset="100%" style="stop-color:#00cec9"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#speech)"/><ellipse cx="12" cy="12" rx="6" ry="4" fill="#2d3436"/><rect x="11" y="10" width="2" height="4" fill="url(#speech)"/></svg>',

        "Techno" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="techno" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00f5ff"/><stop offset="100%" style="stop-color:#0080ff"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#techno)"/><rect x="8" y="10" width="8" height="4" fill="#2d3436"/><rect x="9" y="11" width="6" height="2" fill="url(#techno)"/></svg>',

        "Trance" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="trance" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#a29bfe"/><stop offset="100%" style="stop-color:#6c5ce7"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#trance)"/><path d="M6 12c2-4 4-4 6 0s4 4 6 0" fill="none" stroke="#ffd700" stroke-width="3"/></svg>',

        "Trap" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="trap" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#trap)"/><path d="M8 8l8 8M8 16l8-8" stroke="#ff6b35" stroke-width="2"/><rect x="10" y="10" width="4" height="4" fill="#ff6b35"/></svg>',

        "Triphop" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="triphop" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#fd79a8"/><stop offset="100%" style="stop-color:#e84393"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#triphop)"/><path d="M8 10l4 4 4-4" fill="#2d3436"/><rect x="10" y="12" width="4" height="4" fill="#2d3436"/></svg>',

        "World" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="world" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00b894"/><stop offset="50%" style="stop-color:#ffeaa7"/><stop offset="100%" style="stop-color:#fd79a8"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#world)"/><circle cx="12" cy="12" r="6" fill="none" stroke="#2d3436" stroke-width="2"/><path d="M12 6v12M6 12h12" stroke="#2d3436" stroke-width="1"/></svg>',

        "all audio genres" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><radialGradient id="allgenres"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="20%" style="stop-color:#ff6b35"/><stop offset="40%" style="stop-color:#00b894"/><stop offset="60%" style="stop-color:#a29bfe"/><stop offset="80%" style="stop-color:#fd79a8"/><stop offset="100%" style="stop-color:#2d3436"/></radialGradient></defs><circle cx="12" cy="12" r="10" fill="url(#allgenres)"/><circle cx="12" cy="12" r="3" fill="#2d3436"/></svg>',

        "audiobooks" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="audiobooks" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#6c5ce7"/><stop offset="100%" style="stop-color:#a29bfe"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#audiobooks)"/><rect x="8" y="6" width="8" height="12" fill="#f1f2f6" rx="1"/><rect x="9" y="8" width="6" height="1" fill="url(#audiobooks)"/><rect x="9" y="10" width="6" height="1" fill="url(#audiobooks)"/><rect x="9" y="12" width="4" height="1" fill="url(#audiobooks)"/></svg>',

        "business" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="business" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#business)"/><rect x="8" y="8" width="8" height="8" fill="#00b894" rx="1"/><rect x="9" y="10" width="2" height="2" fill="url(#business)"/><rect x="13" y="10" width="2" height="2" fill="url(#business)"/><rect x="9" y="13" width="6" height="1" fill="url(#business)"/></svg>',

        "comedy" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="comedy" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff6347"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#comedy)"/><circle cx="9" cy="10" r="1" fill="#2d3436"/><circle cx="15" cy="10" r="1" fill="#2d3436"/><path d="M8 14c0 2 4 4 4 4s4-2 4-4" fill="#2d3436"/></svg>',

        "entertainment" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="entertainment" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff7675"/><stop offset="50%" style="stop-color:#fd79a8"/><stop offset="100%" style="stop-color:#a29bfe"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#entertainment)"/><path d="M8 8l8 8M8 16l8-8" stroke="#ffd700" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="#ffd700"/></svg>',

        "learning" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="learning" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00b894"/><stop offset="100%" style="stop-color:#00cec9"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#learning)"/><rect x="8" y="7" width="8" height="10" fill="#f1f2f6" rx="1"/><rect x="9" y="9" width="6" height="1" fill="url(#learning)"/><rect x="9" y="11" width="6" height="1" fill="url(#learning)"/><rect x="9" y="13" width="4" height="1" fill="url(#learning)"/></svg>',

        "news & politics" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="news" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#news)"/><rect x="7" y="6" width="10" height="12" fill="#f1f2f6" rx="1"/><rect x="8" y="8" width="8" height="2" fill="url(#news)"/><rect x="8" y="11" width="8" height="1" fill="url(#news)"/><rect x="8" y="13" width="6" height="1" fill="url(#news)"/><rect x="8" y="15" width="5" height="1" fill="url(#news)"/></svg>',

        "religion & spirituality" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="religion" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff6347"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#religion)"/><path d="M12 6v12M6 12h12" stroke="#2d3436" stroke-width="3" stroke-linecap="round"/><circle cx="12" cy="12" r="2" fill="#2d3436"/></svg>',

        "science" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="science" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00b894"/><stop offset="100%" style="stop-color:#00cec9"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#science)"/><circle cx="12" cy="12" r="6" fill="none" stroke="#ffd700" stroke-width="2"/><circle cx="12" cy="8" r="1" fill="#ffd700"/><circle cx="8" cy="14" r="1" fill="#ffd700"/><circle cx="16" cy="14" r="1" fill="#ffd700"/><path d="M12 8l-4 6M12 8l4 6" stroke="#ffd700" stroke-width="1"/></svg>',

        "sports" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="sports" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff6b35"/><stop offset="100%" style="stop-color:#f7931e"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#sports)"/><circle cx="12" cy="12" r="6" fill="#2d3436"/><path d="M12 6v12M6 12h12M9 9l6 6M15 9l-6 6" stroke="url(#sports)" stroke-width="1"/></svg>',

        "Storytelling" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="storytelling" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#a29bfe"/><stop offset="100%" style="stop-color:#6c5ce7"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#storytelling)"/><ellipse cx="12" cy="10" rx="6" ry="3" fill="#ffd700"/><path d="M9 13c1 2 2 2 3 2s2 0 3-2" fill="none" stroke="#ffd700" stroke-width="2" stroke-linecap="round"/></svg>',

        "Technology" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="technology" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00f5ff"/><stop offset="100%" style="stop-color:#0080ff"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#technology)"/><rect x="8" y="8" width="8" height="8" fill="#2d3436" rx="1"/><circle cx="10" cy="10" r="1" fill="url(#technology)"/><circle cx="14" cy="10" r="1" fill="url(#technology)"/><circle cx="10" cy="14" r="1" fill="url(#technology)"/><circle cx="14" cy="14" r="1" fill="url(#technology)"/><rect x="11" y="11" width="2" height="2" fill="url(#technology)"/></svg>',
    ];

    return $genres;
}

function AllGenresWithIcons()
{
    $genres = [
        "Alternative Rock" => [
            'lucide' => '<x-lucide-zap class="w-6 h-6 text-orange-500" />',
            'heroicon' => '<x-heroicon-o-bolt class="w-6 h-6 text-orange-500" />'
        ],

        "Ambient" => [
            'lucide' => '<x-lucide-waves class="w-6 h-6 text-purple-500" />',
            'heroicon' => '<x-heroicon-o-wifi class="w-6 h-6 text-purple-500" />'
        ],

        "Classical" => [
            'lucide' => '<x-lucide-music class="w-6 h-6 text-yellow-600" />',
            'heroicon' => '<x-heroicon-o-musical-note class="w-6 h-6 text-yellow-600" />'
        ],

        "Country" => [
            'lucide' => '<x-lucide-guitar class="w-6 h-6 text-amber-600" />',
            'heroicon' => '<x-heroicon-o-home class="w-6 h-6 text-amber-600" />'
        ],

        "Dance & EDM" => [
            'lucide' => '<x-lucide-audio-lines class="w-6 h-6 text-pink-500" />',
            'heroicon' => '<x-heroicon-o-signal class="w-6 h-6 text-pink-500" />'
        ],

        "Dancehall" => [
            'lucide' => '<x-lucide-disc-3 class="w-6 h-6 text-green-500" />',
            'heroicon' => '<x-heroicon-o-play-circle class="w-6 h-6 text-green-500" />'
        ],

        "Deep House" => [
            'lucide' => '<x-lucide-circle-dot class="w-6 h-6 text-blue-800" />',
            'heroicon' => '<x-heroicon-o-stop-circle class="w-6 h-6 text-blue-800" />'
        ],

        "Disco" => [
            'lucide' => '<x-lucide-sparkles class="w-6 h-6 text-pink-400" />',
            'heroicon' => '<x-heroicon-o-star class="w-6 h-6 text-pink-400" />'
        ],

        "Drum & Bass" => [
            'lucide' => '<x-lucide-activity class="w-6 h-6 text-red-500" />',
            'heroicon' => '<x-heroicon-o-chart-bar class="w-6 h-6 text-red-500" />'
        ],

        "Dubstep" => [
            'lucide' => '<x-lucide-zap class="w-6 h-6 text-green-400" />',
            'heroicon' => '<x-heroicon-o-lightning-bolt class="w-6 h-6 text-green-400" />'
        ],

        "Electronic" => [
            'lucide' => '<x-lucide-radio class="w-6 h-6 text-cyan-500" />',
            'heroicon' => '<x-heroicon-o-signal class="w-6 h-6 text-cyan-500" />'
        ],

        "Folk & Singer-Songwriter" => [
            'lucide' => '<x-lucide-guitar class="w-6 h-6 text-brown-600" />',
            'heroicon' => '<x-heroicon-o-microphone class="w-6 h-6 text-brown-600" />'
        ],

        "Hip-hop & Rap" => [
            'lucide' => '<x-lucide-mic class="w-6 h-6 text-yellow-500" />',
            'heroicon' => '<x-heroicon-o-microphone class="w-6 h-6 text-yellow-500" />'
        ],

        "House" => [
            'lucide' => '<x-lucide-home class="w-6 h-6 text-purple-500" />',
            'heroicon' => '<x-heroicon-o-home class="w-6 h-6 text-purple-500" />'
        ],

        "Indie" => [
            'lucide' => '<x-lucide-heart class="w-6 h-6 text-pink-400" />',
            'heroicon' => '<x-heroicon-o-heart class="w-6 h-6 text-pink-400" />'
        ],

        "Jazz & Blues" => [
            'lucide' => '<x-lucide-music-2 class="w-6 h-6 text-yellow-500" />',
            'heroicon' => '<x-heroicon-o-musical-note class="w-6 h-6 text-yellow-500" />'
        ],

        "Latin" => [
            'lucide' => '<x-lucide-flame class="w-6 h-6 text-red-500" />',
            'heroicon' => '<x-heroicon-o-fire class="w-6 h-6 text-red-500" />'
        ],

        "Metal" => [
            'lucide' => '<x-lucide-zap class="w-6 h-6 text-gray-700" />',
            'heroicon' => '<x-heroicon-o-lightning-bolt class="w-6 h-6 text-gray-700" />'
        ],

        "Piano" => [
            'lucide' => '<x-lucide-piano class="w-6 h-6 text-gray-800" />',
            'heroicon' => '<x-heroicon-o-musical-note class="w-6 h-6 text-gray-800" />'
        ],

        "Pop" => [
            'lucide' => '<x-lucide-star class="w-6 h-6 text-pink-500" />',
            'heroicon' => '<x-heroicon-o-star class="w-6 h-6 text-pink-500" />'
        ],

        "R&B & Soul" => [
            'lucide' => '<x-lucide-heart class="w-6 h-6 text-purple-600" />',
            'heroicon' => '<x-heroicon-o-heart class="w-6 h-6 text-purple-600" />'
        ],

        "Reggae" => [
            'lucide' => '<x-lucide-sun class="w-6 h-6 text-green-600" />',
            'heroicon' => '<x-heroicon-o-sun class="w-6 h-6 text-green-600" />'
        ],

        "Reggaeton" => [
            'lucide' => '<x-lucide-volume-2 class="w-6 h-6 text-orange-500" />',
            'heroicon' => '<x-heroicon-o-speaker-wave class="w-6 h-6 text-orange-500" />'
        ],

        "Rock" => [
            'lucide' => '<x-lucide-guitar class="w-6 h-6 text-red-600" />',
            'heroicon' => '<x-heroicon-o-bolt class="w-6 h-6 text-red-600" />'
        ],

        "Soundtrack" => [
            'lucide' => '<x-lucide-film class="w-6 h-6 text-yellow-600" />',
            'heroicon' => '<x-heroicon-o-film class="w-6 h-6 text-yellow-600" />'
        ],

        "Speech" => [
            'lucide' => '<x-lucide-mic class="w-6 h-6 text-teal-500" />',
            'heroicon' => '<x-heroicon-o-microphone class="w-6 h-6 text-teal-500" />'
        ],

        "Techno" => [
            'lucide' => '<x-lucide-cpu class="w-6 h-6 text-blue-500" />',
            'heroicon' => '<x-heroicon-o-cog class="w-6 h-6 text-blue-500" />'
        ],

        "Trance" => [
            'lucide' => '<x-lucide-waves class="w-6 h-6 text-purple-400" />',
            'heroicon' => '<x-heroicon-o-wifi class="w-6 h-6 text-purple-400" />'
        ],

        "Trap" => [
            'lucide' => '<x-lucide-triangle class="w-6 h-6 text-gray-600" />',
            'heroicon' => '<x-heroicon-o-play class="w-6 h-6 text-gray-600" />'
        ],

        "Triphop" => [
            'lucide' => '<x-lucide-circle-dot class="w-6 h-6 text-pink-600" />',
            'heroicon' => '<x-heroicon-o-stop-circle class="w-6 h-6 text-pink-600" />'
        ],

        "World" => [
            'lucide' => '<x-lucide-globe class="w-6 h-6 text-emerald-500" />',
            'heroicon' => '<x-heroicon-o-globe-alt class="w-6 h-6 text-emerald-500" />'
        ],

        "all audio genres" => [
            'lucide' => '<x-lucide-music class="w-6 h-6 text-indigo-500" />',
            'heroicon' => '<x-heroicon-o-musical-note class="w-6 h-6 text-indigo-500" />'
        ],

        "audiobooks" => [
            'lucide' => '<x-lucide-book-audio class="w-6 h-6 text-purple-600" />',
            'heroicon' => '<x-heroicon-o-book-open class="w-6 h-6 text-purple-600" />'
        ],

        "business" => [
            'lucide' => '<x-lucide-briefcase class="w-6 h-6 text-gray-600" />',
            'heroicon' => '<x-heroicon-o-briefcase class="w-6 h-6 text-gray-600" />'
        ],

        "comedy" => [
            'lucide' => '<x-lucide-smile class="w-6 h-6 text-yellow-500" />',
            'heroicon' => '<x-heroicon-o-face-smile class="w-6 h-6 text-yellow-500" />'
        ],

        "entertainment" => [
            'lucide' => '<x-lucide-sparkles class="w-6 h-6 text-purple-500" />',
            'heroicon' => '<x-heroicon-o-star class="w-6 h-6 text-purple-500" />'
        ],

        "learning" => [
            'lucide' => '<x-lucide-graduation-cap class="w-6 h-6 text-teal-600" />',
            'heroicon' => '<x-heroicon-o-academic-cap class="w-6 h-6 text-teal-600" />'
        ],

        "news & politics" => [
            'lucide' => '<x-lucide-newspaper class="w-6 h-6 text-gray-700" />',
            'heroicon' => '<x-heroicon-o-newspaper class="w-6 h-6 text-gray-700" />'
        ],

        "religion & spirituality" => [
            'lucide' => '<x-lucide-church class="w-6 h-6 text-yellow-600" />',
            'heroicon' => '<x-heroicon-o-building-library class="w-6 h-6 text-yellow-600" />'
        ],

        "science" => [
            'lucide' => '<x-lucide-atom class="w-6 h-6 text-teal-500" />',
            'heroicon' => '<x-heroicon-o-beaker class="w-6 h-6 text-teal-500" />'
        ],

        "sports" => [
            'lucide' => '<x-lucide-trophy class="w-6 h-6 text-orange-500" />',
            'heroicon' => '<x-heroicon-o-trophy class="w-6 h-6 text-orange-500" />'
        ],

        "Storytelling" => [
            'lucide' => '<x-lucide-book-open class="w-6 h-6 text-purple-600" />',
            'heroicon' => '<x-heroicon-o-book-open class="w-6 h-6 text-purple-600" />'
        ],

        "Technology" => [
            'lucide' => '<x-lucide-cpu class="w-6 h-6 text-blue-500" />',
            'heroicon' => '<x-heroicon-o-computer-desktop class="w-6 h-6 text-blue-500" />'
        ],
    ];

    return $genres;
}


function searchQuery()
{
    $searches = [
        'campaign, my campaigns, repost, ..... related all possible text.' => [
            'route_map' => ['campaigns', 'my-campagin', 'repost']
        ],
        'track, my tracks, repost, ..... related all possible text.' => [
            'route_map' => ['tracks', 'my-tracks', 'repost']
        ],
        'artist, my artists, repost, ..... related all possible text.' => [
            'route_map' => ['artists', 'my-artists', 'repost']
        ],
    ];
    return $searches;
}
