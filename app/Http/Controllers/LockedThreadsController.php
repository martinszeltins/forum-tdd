<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockedThreadsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Lock the thread.
     */
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
