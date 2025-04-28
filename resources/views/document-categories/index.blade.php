@extends('layouts.admin')
@section('title', 'የሰነድ ምድቦች')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-folder"></i> የሰነድ ምድቦች</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('document-categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> አዲስ ለመጨመር
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ተ.ቁ.</th>
                            <th>ስም</th>
                            <th>መግለጫ</th>
                            <th>የሰንድ እርከኖች</th>
                            <th>ሰነዶች</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                @foreach($category->accessLevels as $level)
                                    <span class="badge bg-info">{{ $level->LevelName }}</span>
                                @endforeach
                            </td>
                            <td>{{ $category->documents->count() }}</td>
                            <td>
                                <a href="{{ route('document-categories.edit', $category) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                @if($category->documents->count() === 0)
                                <form action="{{ route('document-categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection