@extends('layouts.admin')
@section('title', 'ሰነዶች')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><i class="bi bi-files me-2"></i>የሰነድ አክሰስ ኮድ ያስገቡ</h3>
                    <p class="text-muted">የሰነድ አክሰስ ኮድ ያስገቡ</p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0"><i class="bi bi-list-ul me-2"></i>የሰነድ አክሰስ ኮድ ያስገቡ</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif



                    <form action="{{ route('mydocuments.verifyCode', $document) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="document_code" class="form-label">የሰነድ አክሰስ ኮድ ያስገቡ</label>
                                    <input type="text" class="form-control @error('document_code') is-invalid @enderror" id="document_code" name="document_code" value="{{ old('document_code') }}" placeholder="Enter the access code" required>
                                    @error('document_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div >
                            <button type="submit" class="btn btn-primary">አስገባ</button>
                        </div>
                    </form>
                    <hr>
                   
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Add hover effect to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseover', () => {
                row.style.backgroundColor = '#f8f9fa';
                row.style.cursor = 'pointer';
            });
            row.addEventListener('mouseout', () => {
                row.style.backgroundColor = '';
                row.style.cursor = 'default';
            });
        });
    });
</script>
@endpush

<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .badge {
        font-weight: 500;
    }

    .btn-group .btn {
        border-radius: 4px;
        margin: 0 2px;
    }

    .card {
        transition: box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>

@endsection