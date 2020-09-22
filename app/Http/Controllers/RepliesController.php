<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReply;
use App\Http\Requests\UpdateReply;
use Exception;
use App\Reply;
use App\Thread;
use App\Rules\SpamFree;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    public function store(StoreReply $request, $channel, Thread $thread)
    {
        $this->authorize('create', new Reply);

        $reply = $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return $reply->load('owner');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->delete();

        if (request()->expectsJson()) {
            return response([
                'status' => 'Reply deleted',
            ]);
        }

        return back();
    }

    public function update(UpdateReply $request, Reply $reply)
    {
        $reply->update($request->validated());
    }
}
