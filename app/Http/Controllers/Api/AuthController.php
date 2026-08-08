<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // Đặt tên token = "mobile" để phân biệt với token web nếu có,
        // và để dễ revoke riêng token của app khi cần (vd đổi mật khẩu).
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        // Chỉ revoke token hiện tại đang dùng để gọi request này,
        // không revoke hết mọi thiết bị đã đăng nhập.
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Đã đăng xuất.']);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}
