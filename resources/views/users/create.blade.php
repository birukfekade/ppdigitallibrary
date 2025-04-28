@extends('layouts.admin')

@section('title', 'አዲስ ተጠቃሚ ፍጠር')

@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>አዲስ ተጠቃሚ ፍጠር</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">ዳሽቦርድ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">ተጠቃሚዎች</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ፍጠር</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">ስም</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">ኢሜይል</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">የተጠቃሚ ስም</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                            id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">የሚስጥር ቃል</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">ስልክ ቁጥር</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                            id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">ሚና</label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                            id="role" name="role" required>
                            <option value="">ሚና ይምረጡ</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="AccessLevelID">የሰነድ እርከን</label>
                        <select class="form-select @error('AccessLevelID') is-invalid @enderror" 
                            id="AccessLevelID" name="AccessLevelID" required>
                            <option value="">የመዳረሻ ደረጃ ይምረጡ</option>
                            @foreach($accessLevels as $level)
                                <option value="{{ $level->id }}" {{ old('AccessLevelID') == $level->id ? 'selected' : '' }}>
                                    {{ $level->LevelName }}
                                </option>
                            @endforeach
                        </select>
                        @error('AccessLevelID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="DepartmentID">የመስሪያ ቤት</label>
                        <select class="form-select @error('DepartmentID') is-invalid @enderror" 
                            id="DepartmentID" name="DepartmentID" required>
                            <option value="">የመስሪያ ቤት ይምረጡ</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('DepartmentID') == $department->id ? 'selected' : '' }}>
                                    {{ $department->DepartmentName }}
                                </option>
                            @endforeach
                        </select>
                        @error('DepartmentID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="CityID">ከተማ</label>
                        <select class="form-select @error('CityID') is-invalid @enderror" 
                            id="CityID" name="CityID" required>
                            <option value="">ከተማ ይምረጡ</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('CityID') == $city->id ? 'selected' : '' }}>
                                    {{ $city->CityName }}
                                </option>
                            @endforeach
                        </select>
                        @error('CityID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="SubCityID">ክፍለ ከተማ</label>
                        <select class="form-select @error('SubCityID') is-invalid @enderror" 
                            id="SubCityID" name="SubCityID" required>
                            <option value="">ክፍለ ከተማ ይምረጡ</option>
                            @foreach($subCities as $subCity)
                                <option value="{{ $subCity->id }}" {{ old('SubCityID') == $subCity->id ? 'selected' : '' }}>
                                    {{ $subCity->SubCityName }}
                                </option>
                            @endforeach
                        </select>
                        @error('SubCityID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">ተጠቃሚ ፍጠር</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">ይቅር</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
