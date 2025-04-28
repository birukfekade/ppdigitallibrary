@extends('layouts.admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
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
        <div class="col-md-8">
                <div class="card">
                    
                    <div class="card-body">
                        <div id="dailyDocumentsChart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
            <!-- Files by Access Level -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div id="accessLevelChart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div id="monthlyDocumentsChart" style="height: 400px;"></div>
        </div>
    </div>
</div>


            <!-- Files by City -->
<div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <div id="cityChart" style="height: 400px;"></div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <div id="departmentChart" style="height: 400px;"></div>
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

    Highcharts.chart('accessLevelChart', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Files by Access Level'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}'
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Files',
            colorByPoint: true,
            data: {!! json_encode($accessLevelData) !!}
        }]
    });

    Highcharts.chart('dailyDocumentsChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Documents Uploaded by Day'
        },
        subtitle: {
            text: 'Last 30 Days'
        },
        xAxis: {
            categories: {!! json_encode($dailyLabels) !!},
            title: {
                text: 'Date'
            }
        },
        yAxis: {
            title: {
                text: 'Number of Documents'
            },
            min: 0
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true
            }
        },
        series: [{
            name: 'Documents',
            data: {!! json_encode($dailyData) !!},
            color: '#4bc0c0'
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
    Highcharts.chart('cityChart', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Files by City'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}'
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Files',
            colorByPoint: true,
            data: {!! json_encode($cityData) !!}
        }]
    });
    Highcharts.chart('departmentChart', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Files by Department'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}'
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Files',
            colorByPoint: true,
            data: {!! json_encode($departmentChartData) !!}
        }]
    });
    Highcharts.chart('monthlyDocumentsChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Documents Uploaded by Month'
        },
        subtitle: {
            text: '{{ now()->year }}'
        },
        xAxis: {
            categories: {!! json_encode($monthlyLabels) !!},
            title: {
                text: 'Month'
            }
        },
        yAxis: {
            title: {
                text: 'Number of Documents'
            },
            min: 0
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true
            }
        },
        series: [{
            name: 'Documents',
            data: {!! json_encode($monthlyData) !!},
            color: '#4bc0c0'
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>

@endsection
