<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Trending;
use Illuminate\Support\Str;
use App\Filters\ThreadFilters;
use App\Http\Requests\StoreThread;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
        $this->middleware('must-be-confirmed')->only('store');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get(),
        ]);
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
            'slug' => Str::slug($request->title),
        ]);

        return redirect($thread->path())->with(
            'flash',
            'Your thread has been published'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->visits()->record();

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
