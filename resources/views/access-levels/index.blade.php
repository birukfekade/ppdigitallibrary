@extends('layouts.admin')
@section('title', 'የሰነድ እርከኖች')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>የሰነድ እርከኖች</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">ዳሽ ቦርድ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">የሰነድ እርከኖች</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('access-levels.create') }}" class="btn btn-primary">አዲስ ለመጨመር</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ተ.ቁ.</th>
                                <th>ስም</th>
                                <th>ድርጊት</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accessLevels as $accessLevel)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $accessLevel->LevelName }}</td>
                                    <td>
                                        <a href="{{ route('access-levels.edit', $accessLevel) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('access-levels.destroy', $accessLevel) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('እርግጠኛ ነዎት ይህን የሰነድ እርከን መሰረዝ ይፈልጋሉ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
