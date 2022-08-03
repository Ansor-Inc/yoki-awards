<?php

namespace Modules\User\Service;

use App\Models\SmsToken;

class SmsTokenService
{
    protected string $phone;

    public function phone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function check(string $code): bool
    {
        $token = $this->getSmsTokenByPhoneAndCode($code);

        if ($token) {
            $this->deleteOldCodes();
            return true;
        }

        return false;
    }

    public function sendSmsCode(): static
    {
        $this->deleteOldCodes();
        $code = $this->generateCode();

        $this->saveToken([
            'phone' => $this->phone,
            'code' => $code,
            'is_sent' => true
        ]);

        PlayMobileService::sendSms($this->phone, $code);

        return $this;
    }

    public function deleteOldCodes()
    {
        SmsToken::query()->where('phone', $this->phone)->delete();
    }

    /**
     * @param array $data
     * @return void
     */
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
        return random_int(10000, 99999);
    }
}
