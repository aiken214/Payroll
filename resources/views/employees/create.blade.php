@extends('layouts.app')
@section('title', 'Add Employee')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header"><h6 class="mb-0">New Employee</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('employees.store') }}">
            @csrf
            @include('employees._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Employee</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
