@extends('layouts.app')

@section('content')
<thread-view
    :initial-replies-count="{{ $thread->replies_count }}"
    inline-template>

    <div class="container">

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ route('profile', $thread->creator) }}">
                                    {{ $thread->creator->name }}
                                </a> posted:

                                {{ $thread->title }}
                            </span>

                            @can('update', $thread)
                                <form action="{{ $thread->path() }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-link">
                                        Delete Thread
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <replies
                    :data="{{ $thread->replies }}"
                    @add="repliesCount++"
                    @remove="repliesCount--">
                </replies>

                {{-- {{ $replies->links() }} --}}
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>This thread was published {{ $thread->created_at->diffForHumans() }}</p>
                        <p>by <a href="#">{{ $thread->creator->name }}</a></p>
                        <p>Currently has <span v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count) }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection
