<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Cookie;

use App\Models\TermsAndConditions;
use App\Models\PrivacyPolicy;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Fetch tid and pid from cookies (if available)
        $tid = Cookie::get('tid');
        $pid = Cookie::get('pid');

        // If tid or pid is not available, set default values
        if (!$tid) {
            $terms = TermsAndConditions::latest()->first(); // Get the latest terms
            $tid = $terms ? $terms->tid : null;
        }

        if (!$pid) {
            $privacyPolicy = PrivacyPolicy::latest()->first(); // Get the latest privacy policy
            $pid = $privacyPolicy ? $privacyPolicy->pid : null;
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'tid' => $tid, // Set tid
            'pid' => $pid, // Set pid
        ]);
    }
}
