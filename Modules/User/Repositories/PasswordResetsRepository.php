<?php

namespace Modules\User\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\User\Interfaces\CanResetPasswordContract;
use Modules\User\Interfaces\PasswordResetsRepositoryInterface;

class PasswordResetsRepository implements PasswordResetsRepositoryInterface
{
    protected $expires;
    protected $throttle;

    public function __construct($expires, $throttle)
    {
        $this->expires = $expires;
        $this->throttle = $throttle;
    }

    public function create(CanResetPasswordContract $user)
    {
        $phone = $user->getPhoneForPasswordReset();

        $this->deleteExisting($user);

        $token = $this->createNewToken();

        $this->getTable()->insert($this->getPayload($phone, $token));

        return $token;
    }

    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array)$this->getTable()->where(
            'phone', $user->getPhoneForPasswordReset()
        )->first();

        return $record &&
            !$this->tokenExpired($record['created_at']) &&
            ($token == $record['token']);
    }

    public function recentlyCreatedToken(CanResetPasswordContract $user)
    {
        $record = (array)$this->getTable()->where(
            'phone', $user->getPhoneForPasswordReset()
        )->first();

        return $record && $this->tokenRecentlyCreated($record['created_at']);
    }

    public function delete(CanResetPasswordContract $user)
    {
        $this->deleteExisting($user);
    }

    public function deleteExpired()
    {
        $expiredAt = Carbon::now()->subSeconds($this->expires);

        $this->getTable()->where('created_at', '<', $expiredAt)->delete();
    }

    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }

    protected function tokenRecentlyCreated($createdAt)
    {
        if ($this->throttle <= 0) {
            return false;
        }

        return Carbon::parse($createdAt)->addSeconds(
            $this->throttle
        )->isFuture();
    }

    protected function deleteExisting(CanResetPasswordContract $user)
    {
        return $this->getTable()->where('phone', $user->getPhoneForPasswordReset())->delete();
    }

    protected function getPayload($phone, $token)
    {
        return ['phone' => $phone, 'token' => $token, 'created_at' => new Carbon];
    }

    protected function createNewToken()
    {
        return Str::random(32);
    }

    protected function getTable()
    {
        return DB::table('password_resets');
    }
}
