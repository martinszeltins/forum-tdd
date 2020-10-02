<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        User::firstWhere('confirmation_token', request('token'))->confirm();

        return redirect('/threads')->with(
            'flash',
            'Your account is confirmed!'
        );
    }
}
