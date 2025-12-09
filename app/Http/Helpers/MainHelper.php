<?php

use App\Models\ApplicationSetting;
use App\Models\Campaign;
use App\Models\Feature;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\UserSetting;
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

function soundcloud_image($url)
{
    $image = asset('default_img/no_img.jpg');
    return $url ? asset('storage/' . $url) : $image;
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

function repostPrice($repost_price, $commentend = false, $liked = false)
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

    return $repost_price + $total;
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

    // $prefix = 'ORDER-';
    $prefix = 'OIDRC-';

    // $microseconds = explode(' ', microtime(true))[0];

    // $date = date('ymd');
    $time = date('his');
    $order_id = $prefix . $time . mt_rand(10, 99);
    $order = Order::where('order_id', $order_id)->first();
    if ($order) {
        return generateOrderID();
    }
    return $order_id;



    // return $prefix . $date . $time . mt_rand(10000, 99999);
}
function totalReposts($campaign = null)
{
    if (!$campaign) {
        $campaign = 0;
    }
    return $campaign->reposts()->count();
}

// Get all genres
// function AllGenres()
// {
//     $genres = [
//         "Alternative Rock" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="altrock" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff6b35"/><stop offset="100%" style="stop-color:#f7931e"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#altrock)"/><path d="M8 8l8 8M16 8l-8 8" stroke="#2d3436" stroke-width="2" stroke-linecap="round"/></svg>',

//         "Ambient" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><radialGradient id="ambient"><stop offset="0%" style="stop-color:#667eea"/><stop offset="50%" style="stop-color:#764ba2"/><stop offset="100%" style="stop-color:#f093fb"/></radialGradient></defs><circle cx="12" cy="12" r="10" fill="url(#ambient)" opacity="0.8"/><circle cx="12" cy="12" r="6" fill="none" stroke="#00d4ff" stroke-width="1" opacity="0.6"/><circle cx="12" cy="12" r="3" fill="#00d4ff" opacity="0.4"/></svg>',

//         "Classical" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="classical" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#c9a96e"/><stop offset="100%" style="stop-color:#8b6914"/></linearGradient></defs><rect width="24" height="24" fill="url(#classical)" rx="3"/><path d="M8 6v12M16 6v12M8 12h8" stroke="#2d3436" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="8" r="2" fill="#2d3436"/></svg>',

//         "Country" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="country" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#d4a574"/><stop offset="100%" style="stop-color:#8b4513"/></linearGradient></defs><circle cx="12" cy="12" r="11" fill="url(#country)"/><path d="M6 12c2-4 4-4 6 0s4 4 6 0" fill="none" stroke="#2d3436" stroke-width="2"/><circle cx="12" cy="7" r="2" fill="#2d3436"/><path d="M10 16h4v2h-4z" fill="#2d3436"/></svg>',

//         "Dance & EDM" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="edm" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff0080"/><stop offset="50%" style="stop-color:#ff8c00"/><stop offset="100%" style="stop-color:#40e0d0"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#edm)"/><rect x="8" y="14" width="2" height="6" fill="#2d3436" rx="1"/><rect x="11" y="10" width="2" height="10" fill="#2d3436" rx="1"/><rect x="14" y="8" width="2" height="12" fill="#2d3436" rx="1"/></svg>',

//         "Hip-hop & Rap" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="hiphop" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff6347"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#hiphop)"/><path d="M8 8v8l8-4z" fill="#2d3436"/><rect x="6" y="15" width="12" height="2" fill="#2d3436"/></svg>',

//         "Indie" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="indie" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff7675"/><stop offset="100%" style="stop-color:#fd79a8"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#indie)"/><circle cx="12" cy="12" r="4" fill="none" stroke="#2d3436" stroke-width="2"/><circle cx="12" cy="12" r="1" fill="#2d3436"/></svg>',

//         "Jazz & Blues" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="jazz" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#jazz)"/><path d="M8 8c0 4 8 4 8 8" fill="none" stroke="#ffd700" stroke-width="2"/><circle cx="16" cy="16" r="2" fill="#ffd700"/></svg>',

//         "Latin" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="latin" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff6b35"/><stop offset="50%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff1744"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#latin)"/><path d="M9 9c0 3 6 3 6 6" fill="none" stroke="#2d3436" stroke-width="2"/><circle cx="15" cy="15" r="1.5" fill="#2d3436"/><circle cx="9" cy="9" r="1.5" fill="#2d3436"/></svg>',

//         "Metal" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="metal" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#metal)"/><path d="M6 10l6 8 6-8-6-4z" fill="#ff0000"/><path d="M10 8l4 0 2 4-4 0z" fill="#ffd700"/></svg>',

//         "Pop" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="pop" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff9ff3"/><stop offset="50%" style="stop-color:#f368e0"/><stop offset="100%" style="stop-color:#ff3838"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#pop)"/><path d="M12 6l1.5 4.5h4.5l-3.75 2.5L15.75 18 12 15.5 8.25 18l1.5-4.5L6 11h4.5z" fill="#ffd700"/><circle cx="12" cy="12" r="3" fill="url(#pop)"/></svg>',

//         "R&B & Soul" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="rnb" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#8e44ad"/><stop offset="100%" style="stop-color:#9b59b6"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#rnb)"/><path d="M8 10c0-2 8-2 8 0v4c0 2-8 2-8 0z" fill="#ffd700"/><circle cx="12" cy="12" r="2" fill="url(#rnb)"/></svg>',

//         "Reggae" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="reggae" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ff1744"/><stop offset="33%" style="stop-color:#ffd600"/><stop offset="66%" style="stop-color:#00e676"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#reggae)"/><path d="M6 12h12" stroke="#2d3436" stroke-width="2"/><path d="M9 9l6 6" stroke="#2d3436" stroke-width="2"/><path d="M15 9l-6 6" stroke="#2d3436" stroke-width="2"/></svg>',

//         "Rock" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="rock" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#2d3436"/><stop offset="100%" style="stop-color:#636e72"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#rock)"/><path d="M8 16l8-8M8 8l8 8" stroke="#ff4757" stroke-width="3" stroke-linecap="round"/><circle cx="12" cy="12" r="2" fill="#ff4757"/></svg>',

//         "Soundtrack" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="soundtrack" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff6347"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#soundtrack)"/><rect x="8" y="8" width="8" height="8" fill="#2d3436" rx="1"/><path d="M10 10l4 4M14 10l-4 4" stroke="url(#soundtrack)" stroke-width="2"/></svg>',

//         "World" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><defs><linearGradient id="world" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#00b894"/><stop offset="50%" style="stop-color:#ffeaa7"/><stop offset="100%" style="stop-color:#fd79a8"/></linearGradient></defs><circle cx="12" cy="12" r="10" fill="url(#world)"/><circle cx="12" cy="12" r="6" fill="none" stroke="#2d3436" stroke-width="2"/><path d="M12 6v12M6 12h12" stroke="#2d3436" stroke-width="1"/></svg>',
//     ];

//
// }
function AllGenres()
{
    $genres = [
        "Alternative Rock" => "Alternative Rock",
        "Ambient" => "Ambient",
        "Classical" => "Classical",
        "Country" => "Country",
        "Dance & EDM" => "Dance & EDM",
        "Dancehall" => "Dancehall",
        "Deep House" => "Deep House",
        "Disco" => "Disco",
        "Drum & Bass" => "Drum & Bass",
        "Dubstep" => "Dubstep",
        "Electronic" => "Electronic",
        "Folk" => "Folk",
        "Hip-hop & Rap" => "Hip-hop & Rap",
        "House" => "House",
        "Indie" => "Indie",
        "Jazz & Blues" => "Jazz & Blues",
        "Latin" => "Latin",
        "Metal" => "Metal",
        "Piano" => "Piano",
        "Pop" => "Pop",
        "R&B & Soul" => "R&B & Soul",
        "Reggae" => "Reggae",
        "Reggaeton" => "Reggaeton",
        "Rock" => "Rock",
        "Soundtrack" => "Soundtrack",
        "Techno" => "Techno",
        "Trance" => "Trance",
        "Trap" => "Trap",
        "Triphop" => "Triphop",
        "World" => "World",
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

        "Hip-hop & Rap" => [
            'lucide' => '<x-lucide-mic class="w-6 h-6 text-yellow-500" />',
            'heroicon' => '<x-heroicon-o-microphone class="w-6 h-6 text-yellow-500" />'
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

        "Rock" => [
            'lucide' => '<x-lucide-guitar class="w-6 h-6 text-red-600" />',
            'heroicon' => '<x-heroicon-o-bolt class="w-6 h-6 text-red-600" />'
        ],

        "Soundtrack" => [
            'lucide' => '<x-lucide-film class="w-6 h-6 text-yellow-600" />',
            'heroicon' => '<x-heroicon-o-film class="w-6 h-6 text-yellow-600" />'
        ],

        "World" => [
            'lucide' => '<x-lucide-globe class="w-6 h-6 text-emerald-500" />',
            'heroicon' => '<x-heroicon-o-globe-alt class="w-6 h-6 text-emerald-500" />'
        ]
    ];

    return $genres;
}

// function AllGenresWithIcons()
// {
//     $genres = [
//         "Alternative Rock" => [
//             'lucide' => '<x-lucide-zap class="w-6 h-6 text-orange-500" />',
//             'heroicon' => '<x-heroicon-o-bolt class="w-6 h-6 text-orange-500" />'
//         ],

//         "Ambient" => [
//             'lucide' => '<x-lucide-waves class="w-6 h-6 text-purple-500" />',
//             'heroicon' => '<x-heroicon-o-wifi class="w-6 h-6 text-purple-500" />'
//         ],

//         "Classical" => [
//             'lucide' => '<x-lucide-music class="w-6 h-6 text-yellow-600" />',
//             'heroicon' => '<x-heroicon-o-musical-note class="w-6 h-6 text-yellow-600" />'
//         ],

//         "Country" => [
//             'lucide' => '<x-lucide-guitar class="w-6 h-6 text-amber-600" />',
//             'heroicon' => '<x-heroicon-o-home class="w-6 h-6 text-amber-600" />'
//         ],

//         "Dance & EDM" => [
//             'lucide' => '<x-lucide-audio-lines class="w-6 h-6 text-pink-500" />',
//             'heroicon' => '<x-heroicon-o-signal class="w-6 h-6 text-pink-500" />'
//         ],

//         // "Dancehall" => [
//         //     'lucide' => '<x-lucide-disc-3 class="w-6 h-6 text-green-500" />',
//         //     'heroicon' => '<x-heroicon-o-play-circle class="w-6 h-6 text-green-500" />'
//         // ],

//         // "Deep House" => [
//         //     'lucide' => '<x-lucide-circle-dot class="w-6 h-6 text-blue-800" />',
//         //     'heroicon' => '<x-heroicon-o-stop-circle class="w-6 h-6 text-blue-800" />'
//         // ],

//         // "Disco" => [
//         //     'lucide' => '<x-lucide-sparkles class="w-6 h-6 text-pink-400" />',
//         //     'heroicon' => '<x-heroicon-o-star class="w-6 h-6 text-pink-400" />'
//         // ],

//         // "Drum & Bass" => [
//         //     'lucide' => '<x-lucide-activity class="w-6 h-6 text-red-500" />',
//         //     'heroicon' => '<x-heroicon-o-chart-bar class="w-6 h-6 text-red-500" />'
//         // ],

//         // "Dubstep" => [
//         //     'lucide' => '<x-lucide-zap class="w-6 h-6 text-green-400" />',
//         //     'heroicon' => '<x-heroicon-o-lightning-bolt class="w-6 h-6 text-green-400" />'
//         // ],

//         // "Electronic" => [
//         //     'lucide' => '<x-lucide-radio class="w-6 h-6 text-cyan-500" />',
//         //     'heroicon' => '<x-heroicon-o-signal class="w-6 h-6 text-cyan-500" />'
//         // ],

//         // "Folk & Singer-Songwriter" => [
//         //     'lucide' => '<x-lucide-guitar class="w-6 h-6 text-brown-600" />',
//         //     'heroicon' => '<x-heroicon-o-microphone class="w-6 h-6 text-brown-600" />'
//         // ],

//         "Hip-hop & Rap" => [
//             'lucide' => '<x-lucide-mic class="w-6 h-6 text-yellow-500" />',
//             'heroicon' => '<x-heroicon-o-microphone class="w-6 h-6 text-yellow-500" />'
//         ],

//         // "House" => [
//         //     'lucide' => '<x-lucide-home class="w-6 h-6 text-purple-500" />',
//         //     'heroicon' => '<x-heroicon-o-home class="w-6 h-6 text-purple-500" />'
//         // ],

//         "Indie" => [
//             'lucide' => '<x-lucide-heart class="w-6 h-6 text-pink-400" />',
//             'heroicon' => '<x-heroicon-o-heart class="w-6 h-6 text-pink-400" />'
//         ],

//         "Jazz & Blues" => [
//             'lucide' => '<x-lucide-music-2 class="w-6 h-6 text-yellow-500" />',
//             'heroicon' => '<x-heroicon-o-musical-note class="w-6 h-6 text-yellow-500" />'
//         ],

//         "Latin" => [
//             'lucide' => '<x-lucide-flame class="w-6 h-6 text-red-500" />',
//             'heroicon' => '<x-heroicon-o-fire class="w-6 h-6 text-red-500" />'
//         ],

//         "Metal" => [
//             'lucide' => '<x-lucide-zap class="w-6 h-6 text-gray-700" />',
//             'heroicon' => '<x-heroicon-o-lightning-bolt class="w-6 h-6 text-gray-700" />'
//         ],

//         // "Piano" => [
//         //     'lucide' => '<x-lucide-piano class="w-6 h-6 text-gray-800" />',
//         //     'heroicon' => '<x-heroicon-o-musical-note class="w-6 h-6 text-gray-800" />'
//         // ],

//         "Pop" => [
//             'lucide' => '<x-lucide-star class="w-6 h-6 text-pink-500" />',
//             'heroicon' => '<x-heroicon-o-star class="w-6 h-6 text-pink-500" />'
//         ],

//         "R&B & Soul" => [
//             'lucide' => '<x-lucide-heart class="w-6 h-6 text-purple-600" />',
//             'heroicon' => '<x-heroicon-o-heart class="w-6 h-6 text-purple-600" />'
//         ],

//         "Reggae" => [
//             'lucide' => '<x-lucide-sun class="w-6 h-6 text-green-600" />',
//             'heroicon' => '<x-heroicon-o-sun class="w-6 h-6 text-green-600" />'
//         ],

//         // "Reggaeton" => [
//         //     'lucide' => '<x-lucide-volume-2 class="w-6 h-6 text-orange-500" />',
//         //     'heroicon' => '<x-heroicon-o-speaker-wave class="w-6 h-6 text-orange-500" />'
//         // ],

//         "Rock" => [
//             'lucide' => '<x-lucide-guitar class="w-6 h-6 text-red-600" />',
//             'heroicon' => '<x-heroicon-o-bolt class="w-6 h-6 text-red-600" />'
//         ],

//         "Soundtrack" => [
//             'lucide' => '<x-lucide-film class="w-6 h-6 text-yellow-600" />',
//             'heroicon' => '<x-heroicon-o-film class="w-6 h-6 text-yellow-600" />'
//         ],

//         // "Speech" => [
//         //     'lucide' => '<x-lucide-mic class="w-6 h-6 text-teal-500" />',
//         //     'heroicon' => '<x-heroicon-o-microphone class="w-6 h-6 text-teal-500" />'
//         // ],

//         "World" => [
//             'lucide' => '<x-lucide-globe class="w-6 h-6 text-emerald-500" />',
//             'heroicon' => '<x-heroicon-o-globe-alt class="w-6 h-6 text-emerald-500" />'
//         ],

//         // "all audio genres" => [
//         //     'lucide' => '<x-lucide-music class="w-6 h-6 text-indigo-500" />',
//         //     'heroicon' => '<x-heroicon-o-musical-note class="w-6 h-6 text-indigo-500" />'
//         // ],

//         // "audiobooks" => [
//         //     'lucide' => '<x-lucide-book-audio class="w-6 h-6 text-purple-600" />',
//         //     'heroicon' => '<x-heroicon-o-book-open class="w-6 h-6 text-purple-600" />'
//         // ],

//         // "business" => [
//         //     'lucide' => '<x-lucide-briefcase class="w-6 h-6 text-gray-600" />',
//         //     'heroicon' => '<x-heroicon-o-briefcase class="w-6 h-6 text-gray-600" />'
//         // ],

//         // "comedy" => [
//         //     'lucide' => '<x-lucide-smile class="w-6 h-6 text-yellow-500" />',
//         //     'heroicon' => '<x-heroicon-o-face-smile class="w-6 h-6 text-yellow-500" />'
//         // ],

//         // "entertainment" => [
//         //     'lucide' => '<x-lucide-sparkles class="w-6 h-6 text-purple-500" />',
//         //     'heroicon' => '<x-heroicon-o-star class="w-6 h-6 text-purple-500" />'
//         // ],

//         // "learning" => [
//         //     'lucide' => '<x-lucide-graduation-cap class="w-6 h-6 text-teal-600" />',
//         //     'heroicon' => '<x-heroicon-o-academic-cap class="w-6 h-6 text-teal-600" />'
//         // ],

//         // "news & politics" => [
//         //     'lucide' => '<x-lucide-newspaper class="w-6 h-6 text-gray-700" />',
//         //     'heroicon' => '<x-heroicon-o-newspaper class="w-6 h-6 text-gray-700" />'
//         // ],

//         // "religion & spirituality" => [
//         //     'lucide' => '<x-lucide-church class="w-6 h-6 text-yellow-600" />',
//         //     'heroicon' => '<x-heroicon-o-building-library class="w-6 h-6 text-yellow-600" />'
//         // ],

//         // "science" => [
//         //     'lucide' => '<x-lucide-atom class="w-6 h-6 text-teal-500" />',
//         //     'heroicon' => '<x-heroicon-o-beaker class="w-6 h-6 text-teal-500" />'
//         // ],

//         // "sports" => [
//         //     'lucide' => '<x-lucide-trophy class="w-6 h-6 text-orange-500" />',
//         //     'heroicon' => '<x-heroicon-o-trophy class="w-6 h-6 text-orange-500" />'
//         // ],

//         // "Storytelling" => [
//         //     'lucide' => '<x-lucide-book-open class="w-6 h-6 text-purple-600" />',
//         //     'heroicon' => '<x-heroicon-o-book-open class="w-6 h-6 text-purple-600" />'
//         // ],

//         // "Technology" => [
//         //     'lucide' => '<x-lucide-cpu class="w-6 h-6 text-blue-500" />',
//         //     'heroicon' => '<x-heroicon-o-computer-desktop class="w-6 h-6 text-blue-500" />'
//         // ],
//     ];

//     return $genres;
// }



// Global Search Function
function searchableRoutes()
{
    $searchableData = [
        [
            'title' => 'My Campaigns',
            'keywords' => ['campaign', 'my campaigns', 'repost campaigns'],
            'route' => route('user.cm.my-campaigns'),
        ],
        [
            'title' => 'Campaigns',
            'keywords' => ['campaign', 'all campaigns', 'repost campaigns', 'recommended', 'recommended pro', 'repost', 'reposts campaigns', 'create campaign'],
            'route' => route('user.cm.campaigns'),
        ],
        [
            'title' => 'Requests',
            'keywords' => ['requests', 'repost requests', 'incomming requests', 'outgoing requests', 'create request'],
            'route' => route('user.reposts-request'),
        ],
        [
            'title' => 'Members',
            'keywords' => ['members', 'create request', 'create requests'],
            'route' => route('user.members'),
        ],
        [
            'title' => 'Plans',
            'keywords' => ['plans', 'pricing', 'premium plans', 'pro plans', 'features', 'feature comparison', 'Core Features', 'Free Boosts', 'Simultaneous Campaigns', 'Priority Direct Requests', 'Free Forever', 'subscribe', 'upgrade', 'upgrade now'],
            'route' => route('user.plans'),
        ],
        [
            'title' => 'My Account',
            'keywords' => ['my account', 'my profile', 'profile', 'email', 'password', 'update', 'update password', 'update email', 'update profile', 'change password', 'change email', 'change profile', 'account'],
            'route' => route('user.my-account'),
        ],
        [
            'title' => 'Analytics',
            'keywords' => ['analytics', 'stats', 'statistics', 'stats', 'statistics', 'stats', 'statistics'],
            'route' => route('user.analytics'),
        ],
        [
            'title' => 'Settings',
            'keywords' => [],
            'route' => '',
        ],
        [
            'title' => 'Help & Support',
            'keywords' => ['Help & Support', 'FAQ', 'FAQs', 'Frequently Asked Questions', 'Contact Us', 'Contact', 'Support', 'Help', 'help', 'support', 'faq', 'faqs', 'frequently asked questions', 'privacy & safety', '&', 'privacy', 'safety', 'community guidelines', 'community', 'guidelines'],
            'route' => route('user.help-support'),
        ],

    ];

    return json_encode($searchableData);
}


function proUser($userUrn = null)
{
    if ($userUrn == null) {
        $userUrn = user()->urn;
    }
    $isPro = UserPlan::where('user_urn', $userUrn)->active()->exists();
    return $isPro;
}

function requestReceiveable($userUrn)
{
    $requestReceiveable = UserSetting::where('user_urn', $userUrn)->value('accept_repost') ?? 0;
    return $requestReceiveable;
}
function invoiceId()
{
    return 'INV-' . date('Y') . '-0' . date('his');
}


if (!function_exists('featuredAgain')) {
    function featuredAgain($campaignId = null)
    {
        if ($campaignId) {
            $latestFeaturedAt = Campaign::where('id', $campaignId)
                ->featured()
                ->latest('featured_at')
                ->value('featured_at');
        } else {
            $latestFeaturedAt = Campaign::self()
                ->featured()
                ->latest('featured_at')
                ->value('featured_at');
        }

        if (!$latestFeaturedAt) {
            return true;
        }

        $hoursSinceLastFeature = Carbon::parse($latestFeaturedAt)->diffInHours(now());
        return $hoursSinceLastFeature >= 24;
    }
}

if (!function_exists('boostAgain')) {
    function boostAgain($campaignId = null)
    {
        if ($campaignId) {
            $latestBoostedAt = Campaign::where('id', $campaignId)
                ->where('is_boost', 1)
                ->latest('boosted_at')
                ->value('boosted_at');
        } else {
            $latestBoostedAt = Campaign::self()
                ->where('is_boost', 1)
                ->latest('boosted_at')
                ->value('boosted_at');
        }

        if (!$latestBoostedAt) {
            return true;
        }

        $hoursSinceLastBoost = Carbon::parse($latestBoostedAt)->diffInMinutes(now());
        return $hoursSinceLastBoost >= 15;
    }

    if (!function_exists('userPlanName')) {
        function userPlanName($userUrn = null)
        {
            if ($userUrn) {
                $user = User::where('urn', $userUrn)->first();
            } else {
                $user = user();
            }
            if (empty($user->activePlan()) || $user->activePlan()->price == 0) {
                return 'Free Plan';
            } else {
                return $user->activePlan()->plan?->name;
            }
        }
    }
}


if (!function_exists('userFeatures')) {
    function userFeatures($userUrn = null): array
    {
        $user = $userUrn ? User::where('urn', $userUrn)->first() : user();
        $plan = $user->activePlan() ? $user->activePlan()->plan : null;

        if (!$plan) {
            $plan = Plan::where('monthly_price', 0)->first();
        }
        if ($plan) {
            $plan->load('featureRelations.feature');

            $features = $plan->featureRelations->pluck('value', 'feature.key')->toArray();
            return $features;
        }
        return [
            Feature::KEY_DIRECT_REQUESTS => "10",
            Feature::KEY_SIMULTANEOUS_CAMPAIGNS => "2",
            Feature::KEY_MULTI_ACCOUNT_PROMOTION => "1 account",
            Feature::KEY_CAMPAIGN_TARGETING => "True",
            Feature::KEY_FEATURED_CAMPAIGN_PRIORITY => "False",
            Feature::KEY_CAMPAIGN_RATING_AND_ANALYTICS => "Basic",
            Feature::KEY_GROWTH_ANALYTICS => "False",
            Feature::KEY_COMMUNITY_SUPPORT_AND_NETWORKING => "True",
            Feature::KEY_COLLABORATION_HUB => "True",
            Feature::KEY_SUPPORT_LEVEL => "Community Support",
        ];
    }
}

function hasEmailSentPermission($value, $userUrn = null): bool
{
    if ($userUrn) {
        return UserSetting::where('user_urn', $userUrn)->value($value) ?? false;
    }
    return UserSetting::where('user_urn', user()->urn)->value($value) ?? false;
}
// if(!function_exists('app_setting')){
//     function app_setting($key)
//     {
//         $setting = ApplicationSetting::where('key', $key)->first();
//         if ($setting) {
//             return $setting->value;
//         }
//         return null;
//     }
// }
if (!function_exists('app_setting')) {
    function app_setting($key)
    {
        static $settings = [];

        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        $setting = ApplicationSetting::where('key', $key)->first();

        $settings[$key] = $setting ? $setting->value : null;

        return $settings[$key];
    }
}

// function logos()

if (!function_exists('number_shorten')) {
    function number_shorten($number, $precision = 1)
    {
        if ($number < 900) {
            // 0 - 900
            $number_format = number_format($number, $precision);
            $suffix = '';
        } elseif ($number < 900000) {
            // 0.9k-850k
            $number_format = number_format($number / 1000, $precision);
            $suffix = 'k';
        } elseif ($number < 900000000) {
            // 0.9m-850m
            $number_format = number_format($number / 1000000, $precision);
            $suffix = 'M';
        } elseif ($number < 900000000000) {
            // 0.9b-850b
            $number_format = number_format($number / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $number_format = number_format($number / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unnecessary .0
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $number_format = str_replace($dotzero, '', $number_format);
        }

        return $number_format . $suffix;
    }
}

if (!function_exists('is_email_verified')) {
    function is_email_verified($userUrn = null)
    {
        $user = $userUrn ? User::where('urn', $userUrn)->first() : user();
        return $user->hasVerifiedEmail();
    }
}
