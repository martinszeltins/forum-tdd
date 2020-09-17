<reply
    :attributes="{{ $reply }}"
    inline-template
    v-cloak>

    <div id="reply-{{ $reply->id }}" class="card m-3">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner) }}">
                        {{ $reply->owner->name}}
                    </a>
                </h5>

                @if (Auth::check())
                    <div>
                        <favorite :reply="{{ $reply }}"></favorite>
                    </div>
                @endif
            </div>
            
            said {{ $reply->created_at->diffForHumans() }}
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea
                        v-model="body"
                        class="form-control"
                        ref="edit_input">
                    </textarea>
                </div>

                <button
                    @click="update"
                    class="btn btn-xs btn-primary">

                    <div v-text="updateBtn"></div>
                </button>
                
                <button
                    @click="editing = false"
                    class="btn btn-xs btn-link">
                    Cancel
                </button>
            </div>

            <div v-else v-text="body"></div>
        </div>

        @can ('update', $reply)
            <div class="panel-footer level">
                <button
                    @click="openEditor"
                    class="btn btn-secondary btn-xs mr-1">
                    Edit
                </button>

                <button
                    @click="destroy"
                    class="btn btn-danger btn-xs mr-1"
                    v-text="deleteBtn">
                </button>
            </div>
        @endcan
    </div>
</reply>