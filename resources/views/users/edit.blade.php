@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">ተጠቃሚ ማስተካከያ: {{ $user->name }}</h2>
                </div>

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

                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">ስም</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">ኢሜል</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">የተጠቃሚ ስም</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">የይለፍ ቃል</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" placeholder="አሁን ያለውን ይቆዩ ይህን ባዶ ይተዉ">
                            <div class="form-text">አሁን ያለውን ይቆዩ ይህን ባዶ ይተዉ።</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">ስልክ ቁጥር</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">ሚና</label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                                <option value="">ሚና ይምረጡ</option>
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" 
                                        {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="AccessLevelID" class="form-label">የሰነድ መዳረሻ ደረጃ</label>
                            <select class="form-select @error('AccessLevelID') is-invalid @enderror" 
                                id="AccessLevelID" name="AccessLevelID" required>
                                <option value="">መዳረሻ ደረጃ ይምረጡ</option>
                                @foreach($accessLevels as $level)
                                    <option value="{{ $level->id }}" 
                                        {{ old('AccessLevelID', $user->AccessLevelID) == $level->id ? 'selected' : '' }}>
                                        {{ $level->LevelName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('AccessLevelID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="DepartmentID" class="form-label">የመስሪያ ክፍል</label>
                            <select class="form-select @error('DepartmentID') is-invalid @enderror" 
                                id="DepartmentID" name="DepartmentID" required>
                                <option value="">የመስሪያ ክፍል ይምረጡ</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" 
                                        {{ old('DepartmentID', $user->DepartmentID) == $department->id ? 'selected' : '' }}>
                                        {{ $department->DepartmentName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('DepartmentID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="CityID" class="form-label">ከተማ</label>
                            <select class="form-select @error('CityID') is-invalid @enderror" 
                                id="CityID" name="CityID" required>
                                <option value="">ከተማ ይምረጡ</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" 
                                        {{ old('CityID', $user->CityID) == $city->id ? 'selected' : '' }}>
                                        {{ $city->CityName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('CityID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="SubCityID" class="form-label">ክፍለ ከተማ</label>
                            <select class="form-select @error('SubCityID') is-invalid @enderror" 
                                id="SubCityID" name="SubCityID" required>
                                <option value="">ክፍለ ከተማ ይምረጡ</option>
                                @foreach($subCities as $subCity)
                                    <option value="{{ $subCity->id }}" 
                                        {{ old('SubCityID', $user->SubCityID) == $subCity->id ? 'selected' : '' }}>
                                        {{ $subCity->SubCityName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('SubCityID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">ሰርዝ</a>
                            <button type="submit" class="btn btn-primary">ተጠቃሚን አዘምን</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
