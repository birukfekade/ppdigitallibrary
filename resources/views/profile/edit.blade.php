@extends('layouts.admin')

@section('title', 'መገለጫ ማሻሻያ')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>የመገለጫ ቅንብሮች</h3>
            </div>
        </div>
    </div>

    <section class="section">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- የመገለጫ መረጃ -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>የመገለጫ መረጃን አዘምን</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="name">ስም</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">ኢሜይል</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="username">የተጠቃሚ ስም</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                    id="username" name="username" value="{{ old('username', $user->username) }}">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">ስልክ ቁጥር</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">መገለጫ አዘምን</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- የይለፍ ቃል ለውጥ -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>የይለፍ ቃል ለውጥ</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="current_password">የአሁኑ ይለፍ ቃል</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                    id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">አዲስ ይለፍ ቃል</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">አዲሱን ይለፍ ቃል ይረጋግጡ</label>
                                <input type="password" class="form-control" 
                                    id="password_confirmation" name="password_confirmation">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">ይለፍ ቃል ለውጥ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
