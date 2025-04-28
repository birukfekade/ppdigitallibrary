@extends('layouts.admin')

@section('title', 'Edit Sub City')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Sub City</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('sub-cities.index') }}">Sub Cities</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('sub-cities.update', $subCity) }}" method="POST">
                        @csrf
                        @method('PUT')
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
                            <label for="CityID">City</label>
                            <select class="form-control @error('CityID') is-invalid @enderror" id="CityID" name="CityID" required>
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ (old('CityID', $subCity->CityID) == $city->id) ? 'selected' : '' }}>
                                        {{ $city->CityName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('CityID')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="SubCityName">Sub City Name</label>
                            <input type="text" class="form-control @error('SubCityName') is-invalid @enderror" id="SubCityName" name="SubCityName" value="{{ old('SubCityName', $subCity->SubCityName) }}" required>
                            @error('SubCityName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update Sub City</button>
                            <a href="{{ route('sub-cities.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
