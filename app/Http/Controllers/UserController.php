<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api')]
class UserController extends Controller
{
    #[get('/users')]
    public function all()
    {
        $users = User::all();
        return $users;
    }
    #[get('/users2')]
    public function add()
    {
        User::create(['name'=>2,'email'=>2,'password'=>2]);
        return 'User added';
    }

}
