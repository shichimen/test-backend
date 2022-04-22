<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UpdateAccountRequest;
use App\Http\Requests\Api\SignInRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function getMe(Request $request)
    {
        return new UserResource($request->user());
    }

    public function putMe(UpdateAccountRequest $request)
    {
        $data = $request->except('password');

        if ($request->has('password') && $request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $request->user()->update($data);
        return $request->user();
    }

    public function login(SignInRequest $request)
    {
        $user = User::where('login', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        return [
            'user' => new UserResource($user),
            'accessToken' => $user->createToken('app')->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
