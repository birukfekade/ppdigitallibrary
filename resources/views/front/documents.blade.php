@extends('layouts.admin')
@section('title', 'ሰነዶች')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><i class="bi bi-files me-2"></i>ሰነዶች</h3>
                    <p class="text-muted">የእርስዎ ሰነዶች</p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0"><i class="bi bi-list-ul me-2"></i>የሰነዶች ዝርዝር</h4>
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

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                <th><i class="bi bi-file-text me-2"></i>የሰነድ ስም</th>
                                <th><i class="bi bi-building me-2"></i>የሰነድ ምድብ</th>
                                    <th><i class="bi bi-info-circle me-2"></i>ተጨማሪ</th>
                                    <th><i class="bi bi-gear me-2"></i>ድርጊት</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-ruled"></i>
                                            <span>{{ $document->title ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-folder"></i>
                                            <span>{{ $document->category->name ?? '-' }}</span>
                                        </div>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $document->upload_date->format('M d, Y H:i') }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-hdd me-1"></i>
                                                {{ $document->file_type }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if($document->isAccessibleToUser(auth()->user()))
                                               
                                                <a href="{{ route('mydocuments.show', $document->id) }}" 
                                                   class="btn btn-sm btn-primary"
                                                   data-bs-toggle="tooltip"
                                                   title="View Document">
                                                   <i class="bi bi-eye-fill"></i>
                                                </a>
                                                @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-2 mb-3 d-block"></i>
                                            <p class="mb-0">ምንም ሰነድ አልተገኘም</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                   
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