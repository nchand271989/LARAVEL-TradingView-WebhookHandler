<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
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
use Illuminate\Support\Facades\Event;

use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
{
    /** Register any application services. */
    public function register(): void
    {
        //
    }

    /** Bootstrap any application services. */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        /** Store is_admin in session after login */
        Fortify::authenticateUsing(function (Request $request) {
            $credentials = $request->only(Fortify::username(), 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                session(['is_admin' => $user->is_admin]);                                                                   // Store is_admin in session
                return $user;
            }
            return null;
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });
    
        Event::listen(Registered::class, function ($event) {
            return redirect()->route('verification.notice');                                                                // Redirect after registration
        });

    }
}
