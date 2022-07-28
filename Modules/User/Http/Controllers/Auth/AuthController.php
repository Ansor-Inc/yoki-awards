<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\Auth\LoginRequest;
use Modules\User\Http\Requests\Auth\RegisterUserRequest;
use Modules\User\Http\Requests\Auth\RegisterVerifyUserRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Interfaces\UserRepositoryInterface;
use Modules\User\Service\SmsTokenService;
use function response;

class AuthController extends Controller
{
    protected UserRepositoryInterface $userRepository;
    protected SmsTokenService $smsTokenService;

    public function __construct(UserRepositoryInterface $userRepository, SmsTokenService $smsTokenService)
    {
        $this->userRepository = $userRepository;
        $this->smsTokenService = $smsTokenService;
    }

    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();

        try {
            $this->smsTokenService->phone($data['phone'])->sendSmsCode();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error sending sms code!'])->setStatusCode(500);
        }

        return response()->json(['message' => 'Sms successfully sent!'])->setStatusCode(200);
    }

    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        $data = $request->validated();

        $check = $this->smsTokenService->phone($data['payload']['phone'])->check($data['code']);

        if ($check) {
            $user = $this->userRepository->createUser($data['payload']);
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->respondWithToken($token);
        }

        return response()->json([
            'message' => 'Invalid code or phone!'
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

        return response()->json([
            'message' => 'These credentials do not match our records!'
        ]);
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
