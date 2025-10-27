@extends('layouts.app')

@section('title', 'Lucky Page')

@section('content')
    <div class="card p-4 shadow-sm">
        <h2 class="mb-4">Hello, {{ $user->username }}</h2>

        @if(session('lucky'))
            <div class="alert alert-info">
                <p><strong>Number:</strong> {{ session('lucky.number') }}</p>
                <p><strong>Result:</strong> {{ session('lucky.result') }}</p>
                <p><strong>Win Amount:</strong> {{ session('lucky.win_amount') }}</p>
            </div>
        @endif

        <div class="d-flex gap-2 mb-3">
            <form method="POST" action="{{ route('lucky.regenerate', $user->token) }}">
                @csrf
                <button type="submit" class="btn btn-warning">Regenerate Link</button>
            </form>

            <form method="POST" action="{{ route('lucky.deactivate', $user->token) }}">
                @csrf
                <button type="submit" class="btn btn-danger">Deactivate Link</button>
            </form>

            <form method="POST" action="{{ route('lucky.imfeelinglucky', $user->token) }}">
                @csrf
                <button type="submit" class="btn btn-success">I'm Feeling Lucky</button>
            </form>

            <a href="{{ route('lucky.history', $user->token) }}" class="btn btn-secondary">History</a>
        </div>

        <p><strong>Link valid until:</strong> {{ $user->expires_at->format('d-m-Y H:i') }}</p>
    </div>
@endsection
