@extends('layouts.admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('title', 'Dashboard')
@section('content')
<div class="page-content">
    <section class="row">
        <!-- Key Statistics Cards -->
        <div class="row mb-4">
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon blue">
                                <i class="bi bi-file-text"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted">Total Documents</h6>
                                <h4 class="mb-0">{{ \App\Models\Document::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon green">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted">Total Users</h6>
                                <h4 class="mb-0">{{ \App\Models\User::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon purple">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted">Cities</h6>
                                <h4 class="mb-0">{{ \App\Models\City::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon red">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted">Departments</h6>
                                <h4 class="mb-0">{{ \App\Models\Department::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Document Trends -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Document Upload Trends</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="documentTrendsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Access Level Distribution -->
            <div class="col-12 col-xl-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Document Access Levels</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="accessLevelChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Department Distribution -->
            <div class="col-12 col-xl-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Documents by Department</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Geographic Distribution -->
            <div class="col-12 col-xl-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Geographic Distribution</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="geographicChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>የቅርብ ሰነዶች</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ስም</th>
                                        <th>ክፍል</th>
                                        <th>የሰነድ ምድብ</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Document::with(['departments', 'category'])->latest()->take(5)->get() as $document)
                                    <tr>
                                        <td>{{ $document->title }}</td>
                                        <td>{{ $document->departments->pluck('DepartmentName')->join(', ') ?: 'Unassigned' }}</td>
                                        <td>{{ $document->category->name ?? '-' }}</td>
                                        <td>{{ $document->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    // Document Trends Chart - Last 6 months
    const trendsCtx = document.getElementById('documentTrendsChart').getContext('2d');
    const trendsChart = new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(
                \App\Models\Document::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
                    ->groupBy('month')
                    ->orderBy('month', 'DESC')
                    ->take(6)
                    ->pluck('month')
                    ->reverse()
            ) !!},
            datasets: [{
                label: 'Documents Uploaded',
                data: {!! json_encode(
                    \App\Models\Document::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                        ->groupBy('month')
                        ->orderBy('month', 'DESC')
                        ->take(6)
                        ->pluck('count')
                        ->reverse()
                ) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Document Category Distribution
    const accessCtx = document.getElementById('accessLevelChart').getContext('2d');
    const accessChart = new Chart(accessCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(\App\Models\DocumentCategory::pluck('name')) !!},
            datasets: [{
                data: {!! json_encode(\App\Models\Document::selectRaw('count(*) as count, document_category_id')
                    ->groupBy('document_category_id')
                    ->pluck('count')
                    ->toArray()) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Department Distribution
    const deptCtx = document.getElementById('departmentChart').getContext('2d');
    const deptChart = new Chart(deptCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(\App\Models\Department::pluck('DepartmentName')) !!},
            datasets: [{
                label: 'Number of Documents',
                data: {!! json_encode(\App\Models\Department::withCount('documents')->pluck('documents_count')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Geographic Distribution
    const geoCtx = document.getElementById('geographicChart').getContext('2d');
    const geoChart = new Chart(geoCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(\App\Models\City::pluck('CityName')) !!},
            datasets: [{
                data: {!! json_encode(\App\Models\City::withCount('documents')->pluck('documents_count')) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

@endsection
