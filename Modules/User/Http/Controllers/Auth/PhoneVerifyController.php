<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\SendCodeRequest;
use Modules\User\Http\Requests\VerifySmsCodeRequest;
use Modules\User\Service\Facades\SmsTokenServiceFacade;

class PhoneVerifyController extends Controller
{
    public function sendCode(SendCodeRequest $request)
    {
        $response = SmsTokenServiceFacade::phone($request->input('phone'))->sendSmsCode();

        return $response->ok()
            ? response(['message' => 'Sms successfully sent!'])
            : response(['message' => 'Error sending sms code!'], 500);
    }

    public function verify(VerifySmsCodeRequest $request)
    {
        $isValidCode = SmsTokenServiceFacade::phone($request->user()->phone)->check($request->input('code'));

        if ($isValidCode) {
            $request->user()->markPhoneAsVerified();
            return response(['message' => 'Your phone number has been verified!']);
        }

        return response(['message' => 'Invalid or expired code!'], 402);
    }
}