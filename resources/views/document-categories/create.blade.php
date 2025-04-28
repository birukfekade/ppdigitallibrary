@extends('layouts.admin')
@section('title', 'አዲስ ለመጨመር')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-folder-plus"></i> አዲስ የሰነድ ምድብ</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('document-categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">የሰነድ ምድብ ስም</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                        id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">የሰነድ ምድብ መግለጫ</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">የምድብ ሰነድ እርከኖች</label>
                    <div class="border rounded p-3">
                        @foreach($accessLevels as $level)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input"
                                name="access_levels[]" value="{{ $level->id }}"
                                id="level_{{ $level->id }}"
                                {{ (old('access_levels') && in_array($level->id, old('access_levels'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="level_{{ $level->id }}">
                                {{ $level->LevelName }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('access_levels')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('document-categories.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> ይቅር
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> ጨምር
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection