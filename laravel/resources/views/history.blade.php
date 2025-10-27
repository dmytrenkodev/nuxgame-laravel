@extends('layouts.app')

@section('title', 'History')

@section('content')
    <div class="card p-4 shadow-sm">
        <h2 class="mb-4">Last 3 results for {{ $user->username }}</h2>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Number</th>
                <th>Result</th>
                <th>Win Amount</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($histories as $h)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $h->number }}</td>
                    <td>{{ $h->result }}</td>
                    <td>{{ $h->win_amount }}</td>
                    <td>{{ $h->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <a href="{{ route('lucky.page', $user->token) }}" class="btn btn-primary mt-3">Back</a>
    </div>
@endsection
