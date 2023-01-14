<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Actions\LoginUser;
use Modules\User\Actions\RegisterUser;
use Modules\User\Http\Requests\Auth\LoginRequest;
use Modules\User\Http\Requests\Auth\RegisterUserRequest;
use Modules\User\Http\Requests\SetFcmTokenRequest;
use Modules\User\Repositories\Interfaces\UserRepositoryInterface;

class AuthController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterUserRequest $request, RegisterUser $registerUser)
    {
        if ($token = $registerUser->execute($request->validated())) {
            return response([
                'message' => 'Your account has been created! Please verify your phone number!',
                'token_type' => 'bearer',
                'token' => $token
            ]);
        }

        return response(['message' => "Ro‘yxatdan o‘tishda xatolik yuz berdi!"], 500);
    }

    public function login(LoginRequest $request, LoginUser $loginUser)
    {
        if ($token = $loginUser->execute($request->validated())) {
            return $this->respondWithToken($token);
        }

        return response()->json(['message' => trans('auth.failed')], 422);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out!'])->setStatusCode(200);
    }

    public function respondWithToken(string $token)
    {
        return response()->json([
            'token_type' => 'bearer',
            'token' => $token
        ]);
    }
}
