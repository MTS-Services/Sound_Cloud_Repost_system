<section class="min-h-96 bg-bg-dark-secondary py-20" id="home">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="lg:max-w-4/5">
                <h1 class="text-4xl tracking-wide font-semibold text-text-light-primary">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Est assumenda aspernatur voluptates dolores
                    quidem earum.
                </h1>
                <p class="text-text-light-secondary mt-4">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Est assumenda aspernatur voluptates dolores
                    quidem earum.
                </p>
                <x-frontend.primary-link class="mt-8"><i class="fa-brands fa-soundcloud text-3xl"></i>
                    {{ __('Connect With SoundCloud') }}</x-frontend.primary-link>
                <p class="text-text-light-secondary mt-4">Get started for free in under 1 minute</p>
            </div>
            <div>
                <video class="w-full aspect-video object-cover rounded-lg" controls
                    poster="https://img.freepik.com/free-vector/happy-girl-wearing-headphones-enjoying-playlist-listening-music-mobile-phone-singing-songs_74855-14053.jpg?uid=R172629362&ga=GA1.1.832835864.1747204716&semt=ais_items_boosted&w=740">
                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                </video>
                <p class="text-text-light-secondary mt-2 text-center text-sm">See what real artists have to say about
                    RepostExchange</p>
            </div>
        </div>
    </div>
</section>
