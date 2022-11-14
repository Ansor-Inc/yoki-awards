<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\Auth\LoginRequest;
use Modules\User\Http\Requests\Auth\RegisterUserRequest;
use Modules\User\Repositories\Interfaces\UserRepositoryInterface;

class AuthController extends Controller
{

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterUserRequest $request)
    {
        $registeredUser = $this->userRepository->registerUser($request->validated());

        if (!isset($registeredUser)) {
            return response(['message' => "Ro‘yxatdan o‘tishda xatolik yuz berdi!"], 500);
        }

        return response([
            'message' => 'Your account has been created! Please verify your phone number!',
            'token_type' => 'bearer',
            'token' => $registeredUser->createToken('auth_token')->plainTextToken
        ]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepository->getUserByPhone($data['phone']);

        if ($user && Hash::check($data['password'], $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->respondWithToken($token);
        };

        return response()->json(['message' => trans('auth.failed')], 404);
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
