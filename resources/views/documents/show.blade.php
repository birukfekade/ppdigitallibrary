@extends('layouts.admin')
@section('title', $document->title)
@section('content')
<style>
    #pdf-viewer {
        width: 100%;
        height: 800px;
        margin: 0 auto;
        border: 1px solid #ccc;
        background: #fff;
    }
    .document-info {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    .document-info p {
        margin-bottom: 0.5rem;
    }
    .document-info i {
        width: 20px;
    }
</style>
<div class="page-content">
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><i class="bi bi-file-text me-2"></i>{{ $document->title }}</h3>
                    <p class="text-muted">{{ $document->description }}</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> ዳሽቦርድ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('documents.index') }}"><i class="bi bi-files"></i> ሰነዶች</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($document->title, 30) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Document Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="document-info">
                                <p><i class="bi bi-person me-2"></i>Uploaded by: {{ $document->user->name }}</p>
                                <p><i class="bi bi-calendar me-2"></i>Upload date: {{ $document->created_at->format('M d, Y') }}</p>
                                <p><i class="bi bi-building me-2"></i>Department: {{ $document->departments->first()->DepartmentName ?? 'Unassigned' }}</p>
                                <p><i class="bi bi-tag me-2"></i>Category: {{ $document->category->name }}</p>
                                <p><i class="bi bi-geo-alt me-2"></i>City: {{ $document->city->CityName }}</p>
                                <p><i class="bi bi-geo me-2"></i>Sub-city: {{ $document->subCity->SubCityName }}</p>
                                <p><i class="bi bi-shield-lock me-2"></i>Access Level: {{ $document->accessLevel->LevelName }}</p>
                                <p><i class="bi bi-file me-2"></i>File size: {{ number_format($document->file_size / 1024, 1) }} KB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0"><i class="bi bi-eye me-2"></i>Document Preview</h4>
                            @if($document->canBeEditedByUser(auth()->user()))
                            <div class="btn-group">
                                <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            @if(strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)) === 'pdf')
                                <iframe 
                                    src="{{ route('documents.view', $document->id) }}"
                                    id="pdf-viewer"
                                    frameborder="0">
                                </iframe>
                            @else
                                <div class="alert alert-info m-3">
                                    <i class="bi bi-info-circle me-2"></i>This file type cannot be previewed.
                                    @if($document->canBeDownloadedByUser(auth()->user()))
                                        <a href="{{ route('documents.download', $document->id) }}" class="btn btn-primary btn-sm ms-2">
                                            <i class="bi bi-download me-1"></i>Download File
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
