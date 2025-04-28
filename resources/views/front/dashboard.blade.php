@extends('layouts.admin')

@section('title', 'User Dashboard')

@section('content')
<div class="page-heading">
    <h3>Welcome, {{ auth()->user()->name }}</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="row">
                <!-- Documents Statistics -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">የርስዎ ሰነዶች</h6>
                                    <h6 class="font-extrabold mb-0">{{ $documents->count() }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Location Info -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="bi bi-map"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">አድራሻ</h6>
                                    <h6 class="font-extrabold mb-0">{{ auth()->user()->city->CityName ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Access Level -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon red">
                                        <i class="bi bi-key"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">እርከን</h6>
                                    <h6 class="font-extrabold mb-0">{{ auth()->user()->accessLevel->LevelName ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recent Documents</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>ሰነድ</th>
                                            <th>ዝርዝር</th>
                                            <th>ድርጊት</th>
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
                                               
                                                    <a href="{{ route('documents.show', $document->id) }}" 
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
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Quick Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="buttons">
                                <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                                    <i class="bi bi-person"></i> Edit Profile
                                </a>
                                <a href="{{ route('mydocuments') }}" class="btn btn-info">
                                    <i class="bi bi-files"></i> View All Documents
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection