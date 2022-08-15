<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Enums\SocialDriver;

class SocialAuthController extends Controller
{
    public function redirect(string $driver)
    {
        $this->validateDriver($driver);

        return Socialite::driver($driver)->stateless()->redirect()->getTargetUrl();
    }

    public function callback(string $driver)
    {
        $this->validateDriver($driver);

        $socialNetworkUser = Socialite::driver($driver)->stateless()->user();

        $user = User::query()->updateOrCreate(
            [
                'email' => $socialNetworkUser->getEmail(),
                'social_auth_id' => $socialNetworkUser->getId(),
                'social_auth_type' => $driver
            ],
            [
                'fullname' => $socialNetworkUser->getName(),
                'avatar' => $socialNetworkUser->getAvatar()
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'token_type' => 'bearer',
            'token' => $token
        ]);
    }

    protected function validateDriver($driver)
    {
        Validator::validate(compact('driver'), [
            'driver' => ['required', 'string', new Enum(SocialDriver::class)]
        ]);
    }
}