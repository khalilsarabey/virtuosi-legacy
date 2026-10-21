<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserLoginRequest;
use App\Models\User;
use App\View\Traits\HttpResponses;
use Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(UserLoginRequest $request)
    {
        $request->validated($request->all());
        $credentials = request(['email', 'password']);
        $credentials['type'] = 'api';
        $credentials['active'] = 1;
        if (!auth()->attempt($credentials)) {
            return $this->error(null, 'Invalid Data', 422);
        }
        $authToken = auth()->user()->createToken('auth-token')->plainTextToken;
        return $this->success(['access_token' => $authToken, 'user' => auth()->user()->only(['id', 'name', 'email', 'balance'])], 'Login Successful');
    }
}
