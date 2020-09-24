<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Filters\ThreadFilters;
use App\Http\Requests\StoreThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        $trending = array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));

        return view('threads.index', compact('threads', 'trending'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThread $request)
    {
        $thread = Thread::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'channel_id' => $request->channel_id,
            'body' => $request->body,
        ]);

        return redirect($thread->path())->with(
            'flash',
            'Your thread has been published'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]));

        return view('threads.show', [
            'thread' => $thread,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    /**
     * Get Threads with filters
     */
    public function getThreads($channel, $filters)
    {
        $threads = Thread::latest()->filter($filters);
        
        if ($channel->exists) {
            $threads->whereChannelId($channel->id);
        }

       return $threads->paginate(5);
    }
}
