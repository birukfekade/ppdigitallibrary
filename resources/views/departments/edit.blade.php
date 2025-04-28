@extends('layouts.admin')
@section('title', 'ክፍል ለማዘመን<')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>ክፍል ለማዘመን</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">ዳሽቦርድ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">ክፍሎች</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ለማዘመን</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('departments.update', $department) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">የክፍል ስም</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $department->DepartmentName) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">አዘምን</button>
                            <a href="{{ route('departments.index') }}" class="btn btn-secondary">ይቅር</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection 