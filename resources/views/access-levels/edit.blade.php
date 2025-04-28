@extends('layouts.admin')
@section('title', 'የሰነድ እርከን አዘምን')

@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>የሰነድ እርከን አዘምን</h3>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('access-levels.update', $accessLevel) }}" method="POST">
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
                            <label for="LevelName">የእርከን ስም</label>
                            <input type="text" class="form-control @error('LevelName') is-invalid @enderror" id="LevelName" name="LevelName" value="{{ old('LevelName', $accessLevel->LevelName) }}" required>
                            @error('LevelName')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">የሰነድ እርከን አዘምን</button>
                            <a href="{{ route('access-levels.index') }}" class="btn btn-secondary">ይቅር</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection