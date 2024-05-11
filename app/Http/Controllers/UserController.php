<?php

namespace App\Http\Controllers;

use App\Http\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Laravel\Sanctum\HasApiTokens;

#[Prefix('user')]
class UserController extends Controller
{
    #[Post('register')]
    public function register(Request $request)
    {
        Log::info($request);
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user]);
    }


    /**
     * @throws AuthException
     */
    #[Get('login')]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info($request);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new AuthException(AuthException::PASSWORD_ERROR, "账号或密码错误", null);
        }
        // 创建 token
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    #[Get('update')]
    public function update(Request $request)
    {
        $user = $request->user();
        Log::info($user);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['user' => $user]);
    }


}
