<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')
            ->scopes(['email'])
            ->redirect();
    }

    public function callback()
    {
        $facebookUser = Socialite::driver('facebook')->user();

        $user = User::query()->where('facebook_id', $facebookUser->getId())->first();

        if (! $user && $facebookUser->getEmail()) {
            $user = User::query()->where('email', $facebookUser->getEmail())->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $facebookUser->getName() ?: 'Facebook User',
                'email' => $facebookUser->getEmail(),
                'facebook_id' => $facebookUser->getId(),
                'password' => bcrypt(Str::random(32)),
            ]);
        } else {
            $user->facebook_id = $facebookUser->getId();
            if (! $user->email && $facebookUser->getEmail()) {
                $user->email = $facebookUser->getEmail();
            }
            $user->save();
        }

        Auth::login($user);

        return redirect()->route('login');
    }
}
