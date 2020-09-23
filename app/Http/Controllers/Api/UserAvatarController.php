<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserAvatar;

class UserAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StoreUserAvatar $request)
    {
        auth()->user()->saveAvatar(
            request()->file('avatar')
        );

        return back();
    }
}
