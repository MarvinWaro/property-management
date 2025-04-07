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
use Laravel\Fortify\Contracts\RegisterResponse;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind our custom RegisterResponse so that after registration,
        // the user is logged out and redirected to the login page.
        $this->app->singleton(RegisterResponse::class, function () {
            return new \App\Http\Responses\RegisterResponse;
        });
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

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        /**
         * BLOCK INACTIVE USERS AT LOGIN (Option A)
         */
        Fortify::authenticateUsing(function (Request $request) {
            // 1) Attempt to fetch user by email
            $user = User::where('email', $request->email)->first();

            // 2) Verify password
            if ($user && Hash::check($request->password, $user->password)) {
                // 3) If user is inactive, throw validation error
                if (! $user->status) {
                    throw ValidationException::withMessages([
                        Fortify::username() => ['Your account is inactive. Please contact the admin.'],
                    ]);
                }
                // 4) Otherwise, user is good to log in
                return $user;
            }

            // No user or invalid password => let Fortify handle "invalid credentials"
            return null;
        });
    }

}
