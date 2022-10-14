<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\Auth\VerifyPasswordResetRequest;
use Modules\User\Http\Requests\ResetPasswordRequest;
use Modules\User\Http\Requests\ResetPasswordSendCodeRequest;
use Modules\User\Repositories\Interfaces\PasswordResetsRepositoryInterface;
use Modules\User\Repositories\Interfaces\UserRepositoryInterface;
use Modules\User\Services\Facades\SmsTokenServiceFacade;

class PasswordResetController extends Controller
{
    protected UserRepositoryInterface $userRepository;
    protected PasswordResetsRepositoryInterface $passwordResetsRepository;

    public function __construct(UserRepositoryInterface $userRepository, PasswordResetsRepositoryInterface $passwordResetsRepository)
    {
        $this->userRepository = $userRepository;
        $this->passwordResetsRepository = $passwordResetsRepository;
    }

    public function sendCode(ResetPasswordSendCodeRequest $request)
    {
        $response = SmsTokenServiceFacade::phone($request->input('phone'))->sendSmsCode();

        return $response->ok()
            ? response(['message' => 'Sms sent successfully!'])
            : response(['message' => 'Error sending sms!'], 500);
    }

    public function verifyResetPassword(VerifyPasswordResetRequest $request)
    {
        $user = $this->userRepository->getUserByPhone($request->input('phone'));

        $checkCode = SmsTokenServiceFacade::phone($user->getPhoneForPasswordReset())->check($request->input('code'));

        if (!$checkCode) {
            return response(['message' => 'Invalid or expired code!'], 402);
        }

        if ($this->passwordResetsRepository->recentlyCreatedToken($user)) {
            return response(['message' => 'Too many attempts! Please try again after 60 seconds!']);
        }

        $token = $this->passwordResetsRepository->create($user);

        return response(['password_reset_token' => $token]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepository->getUserByPhone($request->input('phone'));

        if (!$this->passwordResetsRepository->exists($user, $data['password_reset_token'])) {
            return response(['message' => 'Invalid or expired token!'], 401);
        }

        DB::transaction(function () use ($user, $data) {
            $user->forceFill(['password' => Hash::make($data['password'])]);
            $user->save();
            $this->passwordResetsRepository->delete($user);
        });

        return response(['message' => 'Password changed successfully!']);
    }
}
