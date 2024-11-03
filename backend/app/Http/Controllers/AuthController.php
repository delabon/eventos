<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request): array
    {
        /** @var User $user */
        $user = User::create($request->validated());

        $token = $user->createToken($user->email . $user->password);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token->plainTextToken
        ];
    }

    public function login(LoginUserRequest $request): array|JsonResponse
    {
        /** @var User $user */
        $user = User::query()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return new JsonResponse([
                'errors' => [
                    'email' => [
                        'Invalid credentials'
                    ]
                ]
            ], Response::HTTP_FORBIDDEN);
        }

        $token = $user->createToken($user->email . $user->password);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request): array
    {
        $request->user()->tokens()->delete();

        return [
            'success' => true
        ];
    }

    public function user(Request $request): array
    {
        return [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
        ];
    }

    public function me(Request $request): array
    {
        return $this->user($request);
    }
}
