<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\RegisterVerifyUserRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterUserRequest $request)
    {
        
    }

    public function registerVerify(RegisterVerifyUserRequest $request)
    {

    }

    public function login(LoginRequest $request)
    {

    }

    public function logout()
    {

    }

}
