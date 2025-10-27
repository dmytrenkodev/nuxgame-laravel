@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="card p-4 shadow-sm">
        <h2 class="mb-4">Register</h2>

        @if(session('status'))
            <div class="alert alert-danger">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        @if($errors->any())
            <div class="mt-3">
                <ul class="text-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
