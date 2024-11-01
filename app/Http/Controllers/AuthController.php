<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request): array
    {
        /** @var User $user */
        $user = User::query()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid user credentials.',
            ];
        }

        $token = $user->createToken($user->email . $user->password);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token->plainTextToken
        ];
    }
}
