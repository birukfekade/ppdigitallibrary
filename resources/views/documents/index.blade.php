@extends('layouts.admin')
@section('title', 'ሰነዶች')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><i class="bi bi-files me-2"></i>ሰነዶች</h3>
                    <p class="text-muted">የእርስዎን ዲጂታል ሰነዶች ያቀናብሩ እንዲሁም ያደራጁ</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <a href="{{ route('documents.upload') }}" class="btn btn-primary">
                            <i class="bi bi-cloud-upload me-2"></i>አዲስ ለመጨመር
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0"><i class="bi bi-list-ul me-2"></i>የሰነድ ዝርዝር</h4>
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
                                    <th><i class="bi bi-person me-2"></i>ክፍል</th>
                                    <th><i class="bi bi-person me-2"></i>ከተማ</th>
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
                                    <!-- <td>
                                            <div class="d-flex align-items-center">
                                                {{ $document->user->name ?? '-'}}
                                            </div>
                                        </td> -->
                                    <td>
                                        <span class="badge bg-primary">{{ $document->category->name ?? '-' }} ({{ $document->category->accessLevels->pluck('LevelName')->implode(', ') }})</span>
                                    </td>
                                    <td>
                                        @if($document->departments->isEmpty())
                                            Unassigned
                                        @else
                                            {{ $document->departments->pluck('DepartmentName')->join(', ') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($document->city)
                                        {{ $document->city->CityName ?? '-' }}
                                        @if($document->subcity && !$document->is_accessible_to_all_subcities)
                                        <br><small class="text-muted">{{ $document->subcity->SubCityName ?? '-' }}</small>
                                        @elseif($document->is_accessible_to_all_subcities)
                                        <br><small class="badge bg-info">ሁሉም ክ/ከተሞች</small>
                                        @endif
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $document->upload_date->format('M d, Y H:i') }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-hdd me-1"></i>
                                                {{ number_format($document->file_size / 1024, 1) }} KB
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if($document->isAccessibleToUser(auth()->user()))

                                            <a href="{{ route('documents.verify', $document) }}"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="tooltip"
                                                title="View Document">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            @endif
                                            <form action="{{ route('documents.destroy', $document->id) }}"
                                                method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Document"
                                                    onclick="return confirm('እርግጠኛ ነዎት ይህን ሰነድ መሰረዝ ይፈልጋሉ? ሰነድ ከተሰረዘ መመለስ አይቻልም');">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-2 mb-3 d-block"></i>
                                            <p class="mb-0">ምንም ሰነዶች አልተገኙም።</p>
                                            <small>ለመጀመር የመጀመሪያ ሰነድዎን ይስቀሉ።</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $documents->links() }}
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