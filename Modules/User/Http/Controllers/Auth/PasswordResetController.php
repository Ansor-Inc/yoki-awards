<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\Auth\PasswordResetRequest;
use Modules\User\Http\Requests\Auth\VerifyPasswordResetRequest;
use Modules\User\Interfaces\UserRepositoryInterface;
use Modules\User\Service\SmsTokenService;
use function response;

class PasswordResetController extends Controller
{
    protected UserRepositoryInterface $userRepository;
    protected SmsTokenService $smsTokenService;

    public function __construct(UserRepositoryInterface $userRepository, SmsTokenService $smsTokenService)
    {
        $this->userRepository = $userRepository;
        $this->smsTokenService = $smsTokenService;
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepository->getUserByPhone($data['phone']);

        if (!isset($user)) {
            return response()->json(['message' => 'User with this phone does not exist!']);
        }

        try {
            $this->smsTokenService->phone($data['phone'])->sendSmsCode();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error sending sms code!'])->setStatusCode(500);
        }

        return response()->json(['message' => 'Sms successfully sent!'])->setStatusCode(200);
    }

    public function verifyResetPassword(VerifyPasswordResetRequest $request, SmsTokenService $smsTokenService, UserRepositoryInterface $userRepository)
    {
        $data = $request->validated();

        $check = $smsTokenService->phone($data['payload']['phone'])->check($data['code']);

        if ($check) {
            $userRepository->getUserByPhone($data['payload']['phone'])
                ->update([
                    'password' => $data['payload']['password']
                ]);

            return response()->json([
                'message' => 'Password changed successfully!'
            ]);
        }

        return response()->json([
            'message' => 'Invalid code or phone!'
        ]);
    }
}
