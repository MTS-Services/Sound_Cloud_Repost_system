<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepostChain - Under Construction</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css'])
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: 'Inter', sans-serif;
        }

        .text-gradient {
            background: linear-gradient(90deg, #FF5500 0%, #FF7730 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .wave-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff5500' fill-opacity='0.05'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6h-2c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h-2z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .waveform {
            position: relative;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .waveform span {
            background: #FF5500;
            width: 6px;
            height: 20px;
            border-radius: 3px;
            animation: wave 1.5s linear infinite;
            transform-origin: bottom;
        }

        .waveform span:nth-child(1) {
            animation-delay: 0s;
        }

        .waveform span:nth-child(2) {
            animation-delay: 0.1s;
        }

        .waveform span:nth-child(3) {
            animation-delay: 0.2s;
        }

        .waveform span:nth-child(4) {
            animation-delay: 0.3s;
        }

        .waveform span:nth-child(5) {
            animation-delay: 0.4s;
        }

        .waveform span:nth-child(6) {
            animation-delay: 0.5s;
        }

        .waveform span:nth-child(7) {
            animation-delay: 0.6s;
        }

        .waveform span:nth-child(8) {
            animation-delay: 0.7s;
        }

        .waveform span:nth-child(9) {
            animation-delay: 0.8s;
        }

        .waveform span:nth-child(10) {
            animation-delay: 0.9s;
        }

        @keyframes wave {

            0%,
            100% {
                height: 20px;
            }

            50% {
                height: 80px;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(255, 85, 0, 0.3);
            }

            50% {
                box-shadow: 0 0 40px rgba(255, 85, 0, 0.6);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .glow-animation {
            animation: glow 2s ease-in-out infinite;
        }

        .progress-bar {
            background: linear-gradient(90deg, #FF5500 0%, #FF7730 100%);
            height: 8px;
            border-radius: 4px;
            animation: progress 3s ease-in-out infinite;
        }

        @keyframes progress {
            0% {
                width: 0%;
            }

            50% {
                width: 75%;
            }

            100% {
                width: 0%;
            }
        }

        .bg-dark-card {
            background-color: rgba(30, 30, 30, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 85, 0, 0.1);
        }

        .countdown-item {
            background: rgba(255, 85, 0, 0.1);
            border: 1px solid rgba(255, 85, 0, 0.2);
        }
    </style>
</head>

<body class="min-h-screen wave-pattern">
    <!-- Header -->
    <header class="py-6 px-4">
        <div class="container mx-auto">
            <div class="flex items-center justify-center">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center glow-animation">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-white w-6 h-6">
                            <circle cx="8" cy="18" r="4"></circle>
                            <path d="M12 18V2l7 4"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gradient">RepostChain</h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Animated Icon -->
            <div class="mb-8 float-animation">
                <div
                    class="w-32 h-32 mx-auto rounded-full bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center glow-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="text-white">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5"></path>
                        <path d="M2 12l10 5 10-5"></path>
                    </svg>
                </div>
            </div>

            <!-- Main Heading -->
            <h1 class="text-5xl md:text-7xl font-bold mb-6">
                <span class="text-white">We're</span>
                <span class="text-gradient"> Building</span>
                <br>
                <span class="text-white">Something</span>
                <span class="text-gradient"> Amazing</span>
            </h1>

            <!-- Description -->
            <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-3xl mx-auto leading-relaxed">
                RepostChain is being crafted to revolutionize how musicians and content creators collaborate.
                Get ready for the future of organic growth and authentic engagement.
            </p>

            <!-- Waveform Animation -->
            <div class="mb-12">
                <div class="waveform">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <!-- Progress Section -->
            <div class="mb-12">
                <div class="bg-dark-card rounded-2xl p-8 mb-8">
                    <h3 class="text-2xl font-bold text-white mb-4">Development Progress</h3>
                    <div class="bg-gray-800 rounded-full h-4 mb-4 overflow-hidden">
                        <div class="progress-bar"></div>
                    </div>
                    <p class="text-gray-400">Building the future of music collaboration...</p>
                </div>
            </div>

            <!-- Countdown Timer -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-white mb-6">Launching Soon</h3>
                {{-- <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl mx-auto">
                    <div class="countdown-item rounded-xl p-4 pulse-animation">
                        <div class="text-3xl font-bold text-gradient" id="days">30</div>
                        <div class="text-gray-400 text-sm">Days</div>
                    </div>
                    <div class="countdown-item rounded-xl p-4 pulse-animation">
                        <div class="text-3xl font-bold text-gradient" id="hours">12</div>
                        <div class="text-gray-400 text-sm">Hours</div>
                    </div>
                    <div class="countdown-item rounded-xl p-4 pulse-animation">
                        <div class="text-3xl font-bold text-gradient" id="minutes">45</div>
                        <div class="text-gray-400 text-sm">Minutes</div>
                    </div>
                    <div class="countdown-item rounded-xl p-4 pulse-animation">
                        <div class="text-3xl font-bold text-gradient" id="seconds">30</div>
                        <div class="text-gray-400 text-sm">Seconds</div>
                    </div>
                </div> --}}
            </div>

            <!-- Newsletter Signup -->
            <div class="bg-dark-card rounded-2xl p-8 mb-12">
                <h3 class="text-2xl font-bold text-white mb-4">Be the First to Know</h3>
                <p class="text-gray-400 mb-6">Join our waitlist and get early access when we launch.</p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email"
                        class="flex-1 px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <button
                        class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-300 glow-animation">
                        Notify Me
                    </button>
                </div>
            </div>

            <!-- Features Preview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-dark-card rounded-xl p-6 float-animation" style="animation-delay: 0.2s;">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center mb-4 mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-white">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Community Driven</h4>
                    <p class="text-gray-400 text-sm">Connect with fellow artists and grow together</p>
                </div>

                <div class="bg-dark-card rounded-xl p-6 float-animation" style="animation-delay: 0.4s;">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center mb-4 mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-white">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                            <polyline points="16 7 22 7 22 13"></polyline>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Smart Analytics</h4>
                    <p class="text-gray-400 text-sm">Track your growth with detailed insights</p>
                </div>

                <div class="bg-dark-card rounded-xl p-6 float-animation" style="animation-delay: 0.6s;">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center mb-4 mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-white">
                            <path
                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Fair & Secure</h4>
                    <p class="text-gray-400 text-sm">Credit-based system ensuring quality exchanges</p>
                </div>
            </div>

            <!-- Social Links -->
            <div class="flex justify-center gap-6">
                <a href="#"
                    class="w-12 h-12 rounded-full bg-gray-800 hover:bg-gradient-to-r hover:from-orange-500 hover:to-orange-600 flex items-center justify-center transition-all duration-300 glow-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="currentColor" class="text-gray-400 hover:text-white">
                        <path
                            d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                    </svg>
                </a>
                <a href="#"
                    class="w-12 h-12 rounded-full bg-gray-800 hover:bg-gradient-to-r hover:from-orange-500 hover:to-orange-600 flex items-center justify-center transition-all duration-300 glow-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="currentColor" class="text-gray-400 hover:text-white">
                        <path
                            d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                    </svg>
                </a>
                <a href="#"
                    class="w-12 h-12 rounded-full bg-gray-800 hover:bg-gradient-to-r hover:from-orange-500 hover:to-orange-600 flex items-center justify-center transition-all duration-300 glow-animation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="currentColor" class="text-gray-400 hover:text-white">
                        <path
                            d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z" />
                    </svg>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 px-4 text-center">
        <p class="text-gray-500 text-sm">© {{ date('Y') }} RepostChain. All rights reserved.</p>
    </footer>

    <script>
        // Simple countdown timer
        function updateCountdown() {
            const now = new Date().getTime();
            const launchDate = new Date(now + (30 * 24 * 60 * 60 * 1000)).getTime(); // 30 days from now
            const distance = launchDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = days;
            document.getElementById('hours').textContent = hours;
            document.getElementById('minutes').textContent = minutes;
            document.getElementById('seconds').textContent = seconds;
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial call
    </script>
</body>

</html>
