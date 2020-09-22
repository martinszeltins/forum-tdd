<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Inspections\Spam;
use Exception;
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

    public function store(Request $request, $channel, Thread $thread)
    {
        try {
            $this->validateReply();
        
            $reply = $thread->addReply([
                'body' => $request->body,
                'user_id' => auth()->id(),
            ]);
        } catch (Exception $exception) {
            return response('Something went wrong', 422);
        }

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

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $validated = $this->validateReply();
    
            $reply->update($validated);
        } catch (Exception $exception) {
            return response('Something went wrong', 422);
        }
    }

    public function validateReply()
    {
        $validated = $this->validate(request(), [
            'body' => 'required',
        ]);
        
        resolve(Spam::class)->detect(request('body'));

        return $validated;
    }
}
