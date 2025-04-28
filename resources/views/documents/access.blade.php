@extends('layouts.admin')
@section('title', 'የሰነድ መዳረሻ')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><i class="bi bi-shield-lock me-2"></i>የሰነድ መዳረሻ</h3>
                    <p class="text-muted">ሰነዱን ለማየት የመዳረሻ ኮድ ያስገቡ።</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> ዳሽቦርድ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('documents.index') }}"><i class="bi bi-files"></i> ሰነዶች</a></li>
                            <li class="breadcrumb-item active" aria-current="page">መዳረሻ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0"><i class="bi bi-shield-lock me-2"></i>የመዳረሻ ኮድ ማረጋገጫ</h4>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('documents.show', $document) }}" id="accessForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="access_code" class="form-label fw-bold">
                                        <i class="bi bi-key me-1"></i>የመዳረሻ ኮድ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('access_code') is-invalid @enderror"
                                        id="access_code" name="access_code" required autocomplete="off" autofocus
                                        placeholder="የመዳረሻ ኮድ ያስገቡ">
                                    <div class="form-text">ሰነዱን ለማየት የተሰጠዎትን የመዳረሻ ኮድ ያስገቡ</div>
                                    @error('access_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-unlock me-2"></i>ሰነድ ክፈት
                                </button>
                                <a href="{{ route('documents.index') }}" class="btn btn-light">
                                    <i class="bi bi-arrow-left me-2"></i>ተመለስ
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    .form-label {
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .card {
        transition: box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('accessForm');
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>በመፈተሽ ላይ...';
        });
    });
</script>
@endpush
@endsection