<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\User\Entities\SmsToken;

class VerifySmsToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('sms_token')) {
            $smsToken = SmsToken::query()->where('token', $request->input('sms_token'))->first();

            if (isset($smsToken)) {
                $smsToken->delete();
                return $next($request);
            }
        }

        return response(['message' => 'Please provide sms-token!'], 422);
    }
}
