<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Dashboard Setup') }}
    </title>

    {{-- Theme selector && Theme store --}}
    <script>
        // On page load, immediately apply theme from localStorage to prevent flash
        (function() {
            let theme = localStorage.getItem('theme') || 'system';

            // Apply theme immediately
            if (theme === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', systemPrefersDark);
                document.documentElement.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.setAttribute('data-theme', theme);
            }
        })();
    </script>
    <script src="{{ asset('assets/js/theme-toggle.js') }}"></script>

    {{-- End theme selector && Theme store --}}


    {{-- Icons --}}
    {{-- Phosphor Icon --}}
    {{-- <link href=" https://cdn.jsdelivr.net/npm/phosphor-icons@1.4.2/src/css/icons.min.css " rel="stylesheet"> --}}
    {{-- BoxIcons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" />

    {{-- <script src="{{ asset('assets/frontend/js/jquery.js') }}"></script> --}}
    @vite(['resources/css/admin-dashboard.css', 'resources/js/app.js'])

    @stack('css')
</head>

<body x-data="{
    sidebar_expanded: true,
    mobile_menu_open: false,
    mobile: false,
    tablet: false,
    init() {
        this.updateScreenStates();
        window.addEventListener('resize', () => {
            this.updateScreenStates();
        });
    },
    updateScreenStates() {
        this.mobile = window.innerWidth <= 768;
        this.tablet = window.innerWidth <= 1024;
        this.sidebar_expanded = this.tablet; // Automatically expand sidebar on tablet
        // Optionally, close sidebar on table by default:
        if (this.table) {
            this.mobile_menu_open = false;
        }
    },
}" class="bg-gradient-theme">

    <div class="flex h-screen">
        <aside class="bg-red-500/50 h-full z-10 transition-all duration-300 ease-in-out overflow-hidden"
            :class="{
                'w-fit': !sidebar_expanded, // Acts like a collapsed fit-width
                'w-fit max-w-[360px]': sidebar_expanded, // Full sidebar width
                'absolute top-0 left-0 -translate-x-0': mobile_menu_open && tablet,
                'absolute top-0 left-0 -translate-x-full': !mobile_menu_open && tablet,
            }">
            <div class="flex items-center gap-3 whitespace-nowrap px-2 py-4">
                <i data-lucide="home"></i>
                <span x-show="sidebar_expanded" x-transition:enter="transition-opacity duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="font-semibold">
                    Home
                </span>
            </div>
        </aside>
        <div class="w-full max-h-screen overflow-y-auto relative">
            <header class="bg-blue-500 sticky w-full top-0 left-0">
                <div class="flex items-center justify-end">

                    <p x-show="sidebar_expanded">expanded</p>
                    <p x-show="mobile">Mobile</p>
                    <p x-show="tablet">Tablet</p>
                    <button class="btn btn-primary"
                        @click="mobile_menu_open = !mobile_menu_open; sidebar_expanded = !sidebar_expanded">menu
                        bar</button>

                </div>
            </header>
            <main class="bg-purple-500 p-5 mt-5">
                <div class="bg-yellow-500 my-5">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim minima dolorum odit quibusdam placeat
                    harum amet, molestias illum provident ea exercitationem earum voluptatibus eligendi numquam delectus
                    alias laboriosam ut vel animi, eaque voluptatum iusto! Ut dignissimos praesentium dolorum quidem
                    sequi eum! In natus illo nostrum. Quas labore, ut consequatur aliquam eaque sint laborum adipisci
                    maiores mollitia dolore. Iure earum doloribus excepturi necessitatibus aperiam ullam nemo, dolores
                    vel quisquam facere assumenda magnam, cupiditate, dignissimos esse iste doloremque unde delectus rem
                    voluptatem. Dolorum eius omnis ut voluptas consectetur sunt maxime ducimus qui enim sapiente culpa
                    libero, illo facilis nobis aperiam! Suscipit natus minus expedita perferendis labore, nisi placeat
                    corporis aut. Tempora voluptate aliquid autem fuga officia beatae asperiores cumque molestiae iste
                    ab! Esse architecto consequatur aperiam provident repudiandae numquam dolor, quod fugiat. Dolore,
                    aut velit architecto nisi consequuntur delectus autem cupiditate illo aliquid sint, exercitationem
                    corrupti, fugit beatae suscipit quisquam adipisci! Esse incidunt ad laboriosam magni obcaecati, quam
                    nihil impedit dicta? Ut nam exercitationem est quidem. Debitis pariatur quidem ullam delectus sunt
                    modi magni obcaecati temporibus velit in nemo aliquid distinctio autem veritatis, facilis alias
                    nobis assumenda, ut eius eos dolorum tenetur maxime omnis! Atque eum odit incidunt blanditiis,
                    dolorum nostrum sapiente optio impedit ipsa, natus nobis? Corporis consectetur est, tenetur esse
                    voluptates ea aspernatur dignissimos magnam iusto cupiditate blanditiis vel asperiores fugiat totam
                    debitis obcaecati quam libero ratione sint quia repellendus! Asperiores vero molestias aut saepe
                    beatae obcaecati, distinctio excepturi, sapiente temporibus corporis, quisquam modi. Tempore
                    architecto, aut, qui eligendi provident vitae similique quaerat aliquam quibusdam distinctio eum
                    asperiores animi explicabo laborum ipsum, dicta ut ea mollitia amet officiis! Assumenda, quaerat
                    blanditiis! Animi atque, aliquid architecto harum consectetur commodi voluptate inventore
                    repudiandae. Natus rerum veniam perspiciatis earum reprehenderit pariatur, itaque, voluptatibus
                    exercitationem sed beatae laudantium blanditiis consequuntur numquam? Autem at excepturi quo quae
                    minima officia nam sunt! Non iure, consequatur commodi dolores architecto amet ipsum odio vitae
                    sapiente quasi, qui consectetur quod quas nihil mollitia repellendus. Consequatur iste iusto,
                    accusamus voluptatum facilis ipsam minus veritatis incidunt dolores? Eum exercitationem, incidunt
                    commodi laborum, quaerat iure at ullam explicabo animi culpa, debitis maxime? Harum consequuntur
                    magni corrupti inventore atque nostrum illo aliquam eligendi tempore, quod exercitationem, debitis
                    excepturi quam, voluptate veritatis voluptates culpa. Illum voluptas provident quod voluptatibus
                    nisi quis asperiores ducimus reiciendis accusantium. Incidunt enim reiciendis ipsum? Et quibusdam
                    saepe, est, possimus debitis dignissimos eos, cupiditate sed labore voluptates sint vel expedita
                    corrupti inventore velit animi ullam harum recusandae non sit iste maiores? Eaque veritatis animi
                    ipsam a hic. Dolor aspernatur perferendis repellendus nostrum labore explicabo dolore quo amet esse?
                    Odit odio, excepturi perspiciatis quaerat distinctio quae ullam commodi sit error debitis suscipit
                    quos eius magnam, quisquam sed dicta qui magni laborum architecto consequuntur? Ratione eveniet
                    nihil amet eius sequi adipisci molestiae hic, atque, tempore totam accusantium iure at, suscipit
                    quibusdam porro in ipsa? Porro odio quibusdam hic eum tenetur dolorum reiciendis libero nesciunt
                    modi. Fugiat iusto voluptatum nihil, voluptas error earum placeat dolorum, nam enim delectus,
                    adipisci voluptatibus consectetur! Voluptatem, autem!
                </div>
                <div class="bg-yellow-500 my-5">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim minima dolorum odit quibusdam placeat
                    harum amet, molestias illum provident ea exercitationem earum voluptatibus eligendi numquam delectus
                    alias laboriosam ut vel animi, eaque voluptatum iusto! Ut dignissimos praesentium dolorum quidem
                    sequi eum! In natus illo nostrum. Quas labore, ut consequatur aliquam eaque sint laborum adipisci
                    maiores mollitia dolore. Iure earum doloribus excepturi necessitatibus aperiam ullam nemo, dolores
                    vel quisquam facere assumenda magnam, cupiditate, dignissimos esse iste doloremque unde delectus rem
                    voluptatem. Dolorum eius omnis ut voluptas consectetur sunt maxime ducimus qui enim sapiente culpa
                    libero, illo facilis nobis aperiam! Suscipit natus minus expedita perferendis labore, nisi placeat
                    corporis aut. Tempora voluptate aliquid autem fuga officia beatae asperiores cumque molestiae iste
                    ab! Esse architecto consequatur aperiam provident repudiandae numquam dolor, quod fugiat. Dolore,
                    aut velit architecto nisi consequuntur delectus autem cupiditate illo aliquid sint, exercitationem
                    corrupti, fugit beatae suscipit quisquam adipisci! Esse incidunt ad laboriosam magni obcaecati, quam
                    nihil impedit dicta? Ut nam exercitationem est quidem. Debitis pariatur quidem ullam delectus sunt
                    modi magni obcaecati temporibus velit in nemo aliquid distinctio autem veritatis, facilis alias
                    nobis assumenda, ut eius eos dolorum tenetur maxime omnis! Atque eum odit incidunt blanditiis,
                    dolorum nostrum sapiente optio impedit ipsa, natus nobis? Corporis consectetur est, tenetur esse
                    voluptates ea aspernatur dignissimos magnam iusto cupiditate blanditiis vel asperiores fugiat totam
                    debitis obcaecati quam libero ratione sint quia repellendus! Asperiores vero molestias aut saepe
                    beatae obcaecati, distinctio excepturi, sapiente temporibus corporis, quisquam modi. Tempore
                    architecto, aut, qui eligendi provident vitae similique quaerat aliquam quibusdam distinctio eum
                    asperiores animi explicabo laborum ipsum, dicta ut ea mollitia amet officiis! Assumenda, quaerat
                    blanditiis! Animi atque, aliquid architecto harum consectetur commodi voluptate inventore
                    repudiandae. Natus rerum veniam perspiciatis earum reprehenderit pariatur, itaque, voluptatibus
                    exercitationem sed beatae laudantium blanditiis consequuntur numquam? Autem at excepturi quo quae
                    minima officia nam sunt! Non iure, consequatur commodi dolores architecto amet ipsum odio vitae
                    sapiente quasi, qui consectetur quod quas nihil mollitia repellendus. Consequatur iste iusto,
                    accusamus voluptatum facilis ipsam minus veritatis incidunt dolores? Eum exercitationem, incidunt
                    commodi laborum, quaerat iure at ullam explicabo animi culpa, debitis maxime? Harum consequuntur
                    magni corrupti inventore atque nostrum illo aliquam eligendi tempore, quod exercitationem, debitis
                    excepturi quam, voluptate veritatis voluptates culpa. Illum voluptas provident quod voluptatibus
                    nisi quis asperiores ducimus reiciendis accusantium. Incidunt enim reiciendis ipsum? Et quibusdam
                    saepe, est, possimus debitis dignissimos eos, cupiditate sed labore voluptates sint vel expedita
                    corrupti inventore velit animi ullam harum recusandae non sit iste maiores? Eaque veritatis animi
                    ipsam a hic. Dolor aspernatur perferendis repellendus nostrum labore explicabo dolore quo amet esse?
                    Odit odio, excepturi perspiciatis quaerat distinctio quae ullam commodi sit error debitis suscipit
                    quos eius magnam, quisquam sed dicta qui magni laborum architecto consequuntur? Ratione eveniet
                    nihil amet eius sequi adipisci molestiae hic, atque, tempore totam accusantium iure at, suscipit
                    quibusdam porro in ipsa? Porro odio quibusdam hic eum tenetur dolorum reiciendis libero nesciunt
                    modi. Fugiat iusto voluptatum nihil, voluptas error earum placeat dolorum, nam enim delectus,
                    adipisci voluptatibus consectetur! Voluptatem, autem!
                </div>
            </main>
        </div>
    </div>

    <!-- Custom Cursor -->
    <div class="cursor-wrapper hidden lg:block">
        <div class="custom-cursor"></div>
    </div>
    {{-- <div class="flex h-screen">
        <!-- Sidebar (Sticky) -->
        <x-admin::side-bar :active="$page_slug" />

        <!-- Main Content Section -->
        <div class="flex-1 px-3 overflow-y-auto relative">
            <x-admin::header />
            <main class="relative top-5 z-[1]">
                <div class="h-screen">Content 1</div>
                <div class="h-screen">Content 2</div>
            </main>
        </div>
    </div> --}}



    {{-- <div class="flex max-w-screen">
        <x-admin::side-bar :active="$page_slug" />
        <div class="w-full px-4">
            <x-admin::header />
            <main class="flex-1 p-4">
                {{ $slot }}
            </main>
        </div>
    </div> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const $cursorWrapper = $('.cursor-wrapper');
            const $cursor = $('.custom-cursor');

            // Initialize position off-screen
            $cursorWrapper.css('transform', 'translate(-100%, -100%)');

            // Move the cursor with the mouse
            $(document).on('mousemove', function(e) {
                const x = e.clientX;
                const y = e.clientY;
                $cursorWrapper.css('transform',
                    `translate(${x}px, ${y}px) translate(-50%, -50%)`);


                // Optional: Occasionally generate star
                if (Math.random() < 0.2) {
                    createStar(e.clientX, e.clientY);
                }
            });

            // Click animation
            $(document).on('mousedown', function() {
                $cursor.addClass('click');
            });
            $(document).on('mouseup', function() {
                $cursor.removeClass('click');
            });

            // Pulse on hover over links/buttons
            $('a, button').hover(
                function() {
                    $cursor.addClass('animate-scalePulse');
                },
                function() {
                    $cursor.removeClass('animate-scalePulse');
                }
            );

            // Optional star effect (uncomment to enable)
            function createStar(x, y) {
                const $star = $('<div class="star"></div>');

                // Add random colors
                const colors = ['#FF5733', '#33FF57', '#5733FF', '#FFFF33', '#33FFFF'];
                const color = colors[Math.floor(Math.random() * colors.length)];
                $star.css('background', `radial-gradient(circle, ${color}, transparent)`);

                // Position the star
                const offsetX = 0;
                const offsetY = 0;
                $star.css({
                    position: 'absolute',
                    left: `${x + offsetX}px`,
                    top: `${y + offsetY}px`,
                });

                // Append to body and remove after animation
                $('body').append($star);
                $star.on('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function() {
                    $(this).remove();
                });
            }
        });
    </script>

    {{-- Lucide Icons --}}
    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    <script>
        lucide.createIcons();
    </script>

</body>

</html>
