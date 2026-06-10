<x-authentication-layout>
    <div id="welcome-message-paragraph" class="hidden mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 transition-opacity duration-700 ease-in-out opacity-0">
        <h1 id="welcome-message" class="text-2xl font-bold mb-1">
            Welcome back, <span id="username"></span>! 😊
        </h1>
        <p id="welcome-submessage" class="text-sm font-medium"></p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let storedUser = JSON.parse(localStorage.getItem('user') || 'null');

            if (!storedUser) {
                const usernameStr = localStorage.getItem('username');
                if (!usernameStr) return;
                storedUser = usernameStr;
            }

            const fullName = (storedUser.name ?? storedUser.fullname ?? storedUser).replace(/^"|"$/g, '');
            const lastName = fullName.split(" ").pop();

            const usernameSpan = document.getElementById('username');
            const welcomeMessageDiv = document.getElementById('welcome-message-paragraph');
            const subMessage = document.getElementById('welcome-submessage');

            usernameSpan.textContent = lastName;

            // Short & catchy messages
            const messages = [
                "Time to shine! 🌟",
                "Let's make today amazing! 🚀",
                "Your dashboard awaits! 💼",
                "Keep smiling, friend! 😊",
                "New day, new opportunities! 🌞",
                "Let's crush it today! 💪",
                "Adventure starts here! 🗺️",
                "Your energy is contagious! ⚡",
                "Ready to create magic? ✨",
                "Let's rock and roll! 🎸",
                "Make it happen today! 🏆",
                "Smiles all around! 😄",
                "Dream big, act bigger! 🌈",
                "You got this! 💯",
                "Start strong, finish stronger! 🏃‍♂️",
                "Time to slay those tasks! 🐉",
                "Positive vibes only! 🌸",
                "Fuel your ambition! 🔥",
                "Another great day to shine! ✨",
                "Let's go, superstar! ⭐",
                "Hustle with a smile! 😎",
                "Make every second count! ⏰",
                "The world is yours! 🌍",
                "Keep your head high! 🦅",
                "Step up and shine! 🌟",
                "Your energy = your superpower! ⚡",
                "Time to sparkle and shine! ✨",
                "Victory awaits you today! 🏅",
                "Bring your A-game! 🎯",
                "Keep glowing, friend! 💖",
                "Smile, sparkle, succeed! ✨",
                "Take the day by storm! 🌪️",
                "Let's make waves! 🌊",
                "Be unstoppable! 🏋️‍♀️",
                "Smash your goals! 💥",
                "Radiate positivity! 🌞",
                "Own your moment! 👑",
                "Today is your canvas! 🎨",
                "Happiness looks good on you! 😄"
            ];

            const randomMessage = messages[Math.floor(Math.random() * messages.length)];
            subMessage.textContent = randomMessage;

            // Show the card
            welcomeMessageDiv.classList.remove('hidden');
            setTimeout(() => {
                welcomeMessageDiv.style.opacity = 1;
            }, 50);

            localStorage.setItem('user_last_name', lastName);
        });
    </script>



    @if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        {{ session('status') }}
    </div>
    @endif

    <!-- Validation Errors -->
    <x-validation-errors class="mb-4" />

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf
        <div class="space-y-4">
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" name="password" required autocomplete="current-password" />
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
            <div class="mr-1">
                <a class="text-sm underline hover:no-underline" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            </div>
            @endif
            <x-button class="ml-3" id="loginButton">
                <span id="loginButtonText">{{ __('Sign in') }}</span>
                <span id="loginSpinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in...
                </span>
            </x-button>
        </div>
    </form>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('loginButton');
            const buttonText = document.getElementById('loginButtonText');
            const spinner = document.getElementById('loginSpinner');
            
            // Disable button and show loading state
            button.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');
        });

        // Re-enable button if validation errors occur (page reloads)
        window.addEventListener('pageshow', function(event) {
            const button = document.getElementById('loginButton');
            const buttonText = document.getElementById('loginButtonText');
            const spinner = document.getElementById('loginSpinner');
            
            if (button) {
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });
    </script>
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-gray-100 dark:border-gray-700/60">
        <!-- Warning -->
        <div class="mt-5">
            <div class="bg-yellow-500/20 text-yellow-700 px-3 py-2 rounded-lg text-center">
                {{-- <svg class="inline w-3 h-3 shrink-0 fill-current" viewBox="0 0 12 12">
                    <path
                        d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                </svg> --}}
                <span class="text-sm">
                    &copy; <?php echo date("Y"); ?> Kenvies Investments Product.
                </span>
            </div>
        </div>
    </div>
</x-authentication-layout>