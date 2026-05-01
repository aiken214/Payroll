@extends('layouts.app')
@section('title', 'Edit Employee')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header"><h6 class="mb-0">Edit: {{ $employee->full_name }}</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('employees.update', $employee) }}">
            @csrf
            @method('PUT')
            @include('employees._form', ['employee' => $employee])
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
