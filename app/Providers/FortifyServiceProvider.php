<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Custom authentication logic to check user activity status
        Fortify::authenticateUsing(function (Request $request) {
            // Normalize email to lowercase to match Fortify's lowercase_usernames setting
            $email = strtolower(trim($request->email));
            
            // Get fresh user data directly from database (no caching for auth to avoid stale data)
            $user = \App\Models\User::where('email', $email)
                ->select('id', 'name', 'email', 'password', 'activity', 'admin')
                ->first();

            if (!$user) {
                return null;
            }

            // Verify password
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return null;
            }

            // Check if user account is active
            if ($user->activity !== 'Active') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Your account is inactive. Please contact the administrator.'],
                ]);
            }

            return $user;
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
