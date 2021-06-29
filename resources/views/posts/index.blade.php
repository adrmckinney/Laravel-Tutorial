@extends('layouts.app')

@section('content')
<div class="container">
    @foreach($posts as $post)
        <div class="row">
            <div class="col-4 offset-4">
                <a href="/profile/{{ $post->user->id }}">
                    <img src="/storage/{{ $post->image }}" alt="something" class='w-100' style="max-width: 500px">
                </a>
            </div>
        </div>
        <div class="row pt-2 pb-4">
            <div class="col-4 offset-4">
                <div>
                    <span class="font-weight-bold">
                        <a href="/profile/{{ $post->user->id }}">
                            <span class="text-dark">{{ $post->user->username }}</span>
                        </a>
                    </span> {{ $post->caption }}
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
