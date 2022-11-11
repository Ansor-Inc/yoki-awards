<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Entities\User;
use Modules\User\Enums\SocialDriver;

class SocialAuthController extends Controller
{
    public function redirect(string $driver)
    {
        $this->validateDriver($driver);

        return Socialite::driver($driver)->stateless()->redirect();
    }

    public function callback(string $driver)
    {
        $this->validateDriver($driver);

        $socialNetworkUser = Socialite::driver($driver)->stateless()->user();

        $user = User::query()->firstOrCreate(
            [
                'social_auth_id' => $socialNetworkUser->getId(),
                'social_auth_type' => $driver
            ],
            [
                'fullname' => $socialNetworkUser->getName(),
                'avatar' => $socialNetworkUser->getAvatar(),
                'email' => $socialNetworkUser->getEmail(),
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect()->to(config('auth.social_auth_redirect_url') . "?token={$token}");
    }

    protected function validateDriver($driver)
    {
        Validator::validate(compact('driver'), [
            'driver' => ['required', 'string', new Enum(SocialDriver::class)]
        ]);
    }
}