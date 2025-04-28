@extends('layouts.admin')
@section('title', 'ተጠቃሚዎች')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3">የተጠቃሚ አስተዳደር</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> አዲስ ተጠቃሚ ፍጠር
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ስም</th>
                            <th>የተጠቃሚ ስም</th>
                            <th>ኢሜይል</th>
                            <th>ስልክ ቁጥር</th>
                            <th>ሚና</th>
                            <th>ሁኔታ</th>
                            <th>እርከን</th>
                            <th>ክፍል</th>
                            <th>ከተማ</th>
                            <th>ክፍለ ከተማ</th>
                            <th>ተግባራት</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'manager' ? 'warning' : 'info') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->accessLevel->LevelName ?? '-'}}</td>
                                <td>{{ $user->department->DepartmentName ?? '-'}}</td>
                                <td>{{ $user->city->CityName ?? '-'}}</td>
                                <td>{{ $user->subCity->SubCityName ?? '-'}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> አስተካክል
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('ይህን ተጠቃሚ ማጥፋት ይፈልጋሉ?')">
                                                    <i class="fas fa-trash"></i> ሰርዝ
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">ምንም ተጠቃሚ አልተገኘም።</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
