@extends('layouts.adminlayout')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>Employment Details</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Employer Name: {{ $employment->employer_name }}</h5>
        <p>Position: {{ $employment->position }}</p>
        <p>Occupation: {{ $employment->occupation }}</p>
        <p>Manager Name: {{ $employment->manager_name }}</p>
        <p>Manager Email: {{ $employment->manager_email }}</p>
        <p>Manager Phone: {{ $employment->manager_phone }}</p>
        <p>Start Date: {{ $employment->start_date }}</p>

        <!-- Action Buttons -->
        <div class="mt-3">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
</div>

@endsection
