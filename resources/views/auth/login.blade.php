<x-authentication-layout>
    <script>
        // Run when the page loads
        document.addEventListener("DOMContentLoaded", function() {

            // 1. Get the stored user object from localStorage
            const storedUser = JSON.parse(localStorage.getItem('user'));
            if (!storedUser) return;

            // 2. Extract full name (adjust based on your stored structure)
            const fullName = storedUser.name ?? storedUser.fullname ?? storedUser;

            // 3. Split full name and get last name
            const nameParts = fullName.split(" ");
            const lastName = nameParts[nameParts.length - 1];

            // 4. Insert last name into the DOM
            const usernameSpan = document.getElementById('username');
            usernameSpan.textContent = lastName;

            // 5. Show the welcome message
            const welcomeMessage = document.getElementById('welcome-message');
            welcomeMessage.classList.remove('hidden');

            // 6. Optional: Save last name separately
            localStorage.setItem('user_last_name', lastName);
        });
    </script>

    <h1 id="welcome-message" class="text-2xl text-gray-800 dark:text-gray-100 font-bold mb-6 hidden">
        Welcome back, <span id="username"></span>😊
    </h1>
    @if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        {{ session('status') }}
    </div>
    @endif

    <!-- Validation Errors -->
    <x-validation-errors class="mb-4" />

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
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
            <x-button class="ml-3">
                {{ __('Sign in') }}
            </x-button>
        </div>
    </form>
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
                    A Kenvies Investments Product</span>
            </div>
        </div>
    </div>
</x-authentication-layout>