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
            ->stateless()
            ->with([
                'config_id' => '1708686646786988',
            ])
            ->redirect();
    }

    public function callback()
    {
        $facebookUser = Socialite::driver('facebook')
            ->stateless()
            ->setScopes([])
            ->fields(['id', 'name'])
            ->user();

        $user = User::query()
            ->where('facebook_id', $facebookUser->getId())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $facebookUser->getName() ?: 'Facebook User',
                // 不拿 email，就给一个占位邮箱，避免数据库 not null / unique 问题
                'email' => 'fb_' . $facebookUser->getId() . '@malapan.local',
                'facebook_id' => $facebookUser->getId(),
                'password' => bcrypt(Str::random(32)),
            ]);
        } else {
            if (! $user->facebook_id) {
                $user->facebook_id = $facebookUser->getId();
                $user->save();
            }
        }

        Auth::login($user);

        return redirect('/');
    }
}
