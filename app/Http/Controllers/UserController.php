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

#[Prefix('user')]
class UserController extends Controller
{
    #[Post('register')]
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
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
        Log::info('hello', ['data' => 'something']);
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info($request);
        $user = User::where('email', $request->email)->first();
        Log::info($user);
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new AuthException(AuthException::PASSWORD_ERROR);
        }

        return response()->json(['user' => $user]);
    }

    #[Get('update')]
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = $request->user();

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['user' => $user]);
    }


}
