@extends('layouts.admin')

@section('title', 'Create City')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>አዲስ ከተማ ለመጨመር</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">ዳሽቦርድ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('cities.index') }}">ከተሞች</a></li>
                            <li class="breadcrumb-item active" aria-current="page">አዲስ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('cities.store') }}" method="POST">
                        @csrf
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="name">የከተማ ስም</label>
                            <input type="text" class="form-control @error('CityName') is-invalid @enderror" id="CityName" name="CityName" value="{{ old('CityName') }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">ጨምር</button>
                            <a href="{{ route('cities.index') }}" class="btn btn-secondary">ይቅር</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
