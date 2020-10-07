@extends('layouts.app')

@section('head')
     <script src="https://www.google.com/recaptcha/api.js"></script>

    <script>
        function onSubmit(token) {
            document.getElementById("demo-form").submit();
        }
    </script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New Thread</div>

                <div class="card-body">
                    <form method="POST" action="/threads">
                        @csrf

                        <!-- channel -->
                        <div class="form-group">
                            <label for="channel_id">Choose a channel:</label>
                            
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value="">Choose One...</option>

                                @foreach($channels as $channel)
                                    <option
                                        value="{{ $channel->id }}"
                                        {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                        {{ $channel->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- title -->
                        <div class="form-group">
                            <label for="title">Title:</label>
                            
                            <input
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                required
                            />
                        </div>


                        <!-- body -->
                        <div class="form-group">
                            <label for="body">Body:</label>
                            
                            <textarea
                                name="body"
                                required
                                id="body"
                                class="form-control"
                                rows="8">{{ old('body') }}</textarea>
                        </div>

                        <!-- ReCapthca -->
                        <button
                            class="g-recaptcha" 
                            data-sitekey="reCAPTCHA_site_key" 
                            data-callback='onSubmit' 
                            data-action='submit'>
                            Submit
                        </button>

                        <!-- publish -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Publish
                            </button>
                        </div>


                        <!-- errors -->
                        @if (count($errors))
                            <ul class="alert alert-danger pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
