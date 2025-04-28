@extends('layouts.admin')
@section('title', 'ክፍሎች')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>ክፍሎች</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">ዳሽቦርድ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ክፍሎች</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('departments.create') }}" class="btn btn-primary">አዲስ ለመጨመር</a>
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
                            @foreach($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->DepartmentName }}</td>
                                    <td>
                                        <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('እርግጠኛ ነዎት ይህን ክፍል መሰረዝ ይፈልጋሉ?')">
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