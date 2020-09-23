@forelse($threads as $thread)
    <div class="card" style="margin-bottom: 20px">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @if ($thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>

                    <h5>
                        Posted by <a href="{{ route('profile', $thread->creator) }}">
                            {{ $thread->creator->name }}
                        </a>
                    </h5>
                </div>

                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="body">
                {{ $thread->body }}
            </div>

            <hr>
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse