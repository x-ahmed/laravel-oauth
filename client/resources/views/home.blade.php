@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success"
                                role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @forelse ($posts as $post)
                            <div class="py-3 border-bottom">
                                <h3>{{ $post->title }}</h3>
                                <div>{{ $post->body }}</div>
                            </div>
                        @empty
                            <a href="{{ route('oauth.redirect') }}">
                                {{ __('Oauth Server Authorization') }}
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
