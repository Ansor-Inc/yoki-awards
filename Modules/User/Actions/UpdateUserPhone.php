<?php

namespace Modules\User\Actions;

use Illuminate\Http\Request;
use Modules\User\Services\Facades\SmsTokenServiceFacade;

class UpdateUserPhone
{
    public function execute(Request $request)
    {
        $phoneIsVerified = SmsTokenServiceFacade::phone($request->input('phone'))->check($request->input('code'));

        if ($phoneIsVerified) {
            $request->user()->update(['phone' => $request->input('phone')]);
            $request->user()->markPhoneAsVerified();
            return true;
        }

        return false;
    }
}
