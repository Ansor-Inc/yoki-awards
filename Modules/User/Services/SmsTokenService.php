<?php

namespace Modules\User\Services;

use App\Components\Sms\Facades\Sms;
use Illuminate\Http\Client\Response;
use Modules\User\Entities\SmsToken;

class SmsTokenService
{
    protected string $phone;

    public function phone(string $phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function check(string $code): bool
    {
        $token = $this->getSmsTokenByPhoneAndCode($code);

        if (isset($token) && !$token->isExpired()) {
            $this->deleteOldCodes();
            return true;
        }

        return false;
    }

    public function sendSmsCode(): Response
    {
        $this->deleteOldCodes();
        $code = $this->generateCode();

        $this->saveToken([
            'phone' => $this->phone,
            'code' => $code
        ]);

        $request = Sms::to($this->phone)->content($this->smsMessage($code))->send();

        if ($request->failed()) {
            throw new \Exception($request->getMessage());
        }

        return $request;
    }

    protected function smsMessage($code)
    {
        return "Yoki verification code: {$code}";
    }

    public function deleteOldCodes()
    {
        SmsToken::query()->where('phone', $this->phone)->delete();
    }

    private function saveToken(array $data): void
    {
        SmsToken::query()->updateOrCreate($data);
    }

    public function getSmsTokenByPhoneAndCode(string $code): object|null
    {
        return SmsToken::query()->where('phone', $this->phone)->where('code', $code)->first();
    }

    private function generateCode(): int
    {
        return random_int(1000, 9999);
    }
}
