@extends('layouts.admin')
@section('title', 'ሰነድ ለመጨመር')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><i class="bi bi-cloud-upload me-2"></i>ሰነድ ለመጨመር</h3>
                    <p class="text-muted">ሰነዶችን ይጨምሩ እና ከዲፓርትመንቶች ፣ የመዳረሻ ደረጃዎች ፣ከተሞች እና ንዑስ ከተሞች ጋር ያካፍሉ።</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> ዳሽቦርድ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('documents.index') }}"><i class="bi bi-files"></i> ሰነዶች</a></li>
                            <li class="breadcrumb-item active" aria-current="page">አዲስ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0"><i class="bi bi-file-earmark-text me-2"></i>የሰነድ መረጃ</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('documents.upload.post') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>እነዚህን ያስተካክሉ:
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="title" class="form-label fw-bold">
                                        <i class="bi bi-type-h1 me-1"></i>የሰነድ ስም
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" required
                                        placeholder="Enter a descriptive title">
                                    <div class="form-text">በቀላሉ ለመለየት ግልጽ እና ገላጭ ርዕስ ይምረጡ</div>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="document" class="form-label fw-bold">
                                        <i class="bi bi-file-earmark-arrow-up me-1"></i>ሰነድ ጨምር
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control @error('file_name') is-invalid @enderror"
                                        id="file_name" name="file_name"
                                        accept=".pdf,.doc,.docx,.xlsx,.ppt,.pptx" required>
                                    <div class="form-text">
                                        ተቀባይነት ያላቸው ፋይሎች: PDF, DOC, DOCX, XLSX, PPT, PPTX (Max size: 10MB)
                                    </div>
                                    @error('file_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="bi bi-text-paragraph me-1"></i>መግለጫ
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="3"
                                        placeholder="Enter a detailed description">{{ old('description') }}</textarea>
                                    <div class="form-text">ስለ ሰነዱ ተጨማሪ ዝርዝሮችን ይስጡ</div>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="access_level_id" class="form-label fw-bold">
                                        <i class="bi bi-shield-lock me-1"></i>የሰነድ ምድብ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="document_category_id" id="document_category_id" class="form-control" required>
                                        <option value="">Select a category</option>
                                        @foreach($documentCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">ይህን ሰነድ ማን መድረስ እንደሚችል ይቆጣጠሩ</div>
                                    @error('access_level_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="department_ids[]" class="form-label fw-bold">
                                        <i class="bi bi-diagram-3 me-1"></i>ክፍሎች
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('department_ids') is-invalid @enderror"
                                        id="department_ids" name="department_ids[]" multiple required>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ in_array($department->id, old('department_ids', [])) ? 'selected' : '' }}>{{ $department->DepartmentName }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">አንድ ወይም ከዚያ በላይ ክፍሎችን ይምረጡ (Ctrl/Cmd + Click)</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="city_ids" class="form-label fw-bold">
                                        <i class="bi bi-geo-alt me-1"></i>ከተማዎች
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('city_ids') is-invalid @enderror" 
                                        id="city_ids" name="city_ids[]" multiple required>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ in_array($city->id, old('city_ids', [])) ? 'selected' : '' }}>
                                            {{ $city->CityName }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('city_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="subcity_ids[]" class="form-label fw-bold">
                                        <i class="bi bi-geo me-1"></i>ክፍለ ከተማዎች
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('subcity_ids') is-invalid @enderror"
                                        id="subcity_ids" name="subcity_ids[]" multiple required>
                                        @foreach($subCities as $subcity)
                                            <option value="{{ $subcity->id }}" {{ in_array($subcity->id, old('subcity_ids', [])) ? 'selected' : '' }}>
                                                {{ $subcity->SubCityName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">አንድ ወይም ከዚያ በላይ ክፍለ ከተማዎችን ይምረጡ (Ctrl/Cmd + Click)</div>
                                    @error('subcity_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="access_code" class="form-label fw-bold">
                                        <i class="bi bi-key me-1"></i>የማግኘት ኮድ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('access_code') is-invalid @enderror"
                                        id="access_code" name="access_code" value="{{ old('access_code') }}"
                                        required minlength="6" placeholder="የማግኘት ኮድን ያስገቡ">
                                    <div class="form-text">በዳግም ብቻ ስድስት ፊደል ይሰጡ</div>
                                    @error('access_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('documents.index') }}" class="btn btn-light">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-upload me-1"></i>ሰነድ ጨምር
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize select2 for multiple selects
        $('#department_ids').select2({
            placeholder: 'ክፍሎችን ይምረጡ',
            allowClear: true
        });

        // Initialize select2 for city select
        $('#city_id').select2({
            placeholder: 'ከተማ ይምረጡ',
            allowClear: true
        });

        // Initialize select2 for subcity select
        $('#subcity_ids').select2({
            placeholder: 'ክፍለ ከተማ ይምረጡ',
            allowClear: true
        });

        // File input validation
        $('#file_name').on('change', function() {
            const file = this.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB

            if (file && file.size > maxSize) {
                alert('File size exceeds 10MB limit. Please choose a smaller file.');
                this.value = '';
            }
        });

        // Form submission loading state
        $('#uploadForm').on('submit', function() {
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true)
                   .html('<span class="spinner-border spinner-border-sm me-2"></span>Uploading...');
        });
    });
</script>

<style>
    .form-label {
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .loading {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cpath d='M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z'/%3E%3Cpath fill='%23000' d='M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0C22.32,8.481,24.301,9.057,26.013,10.047z'%3E%3CanimateTransform attributeType='xml' attributeName='transform' type='rotate' from='0 20 20' to='360 20 20' dur='0.5s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 20px;
    }

    .card {
        transition: box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const citySelect = document.getElementById('city_id');
        const subcitySelect = document.getElementById('subcity_ids');

        // Function to load subcities
        async function loadSubcities(cityId) {
            if (!cityId) {
                subcitySelect.innerHTML = '';
                subcitySelect.disabled = true;
                return;
            }

            try {
                const response = await fetch(`/api/cities/${cityId}/subcities`);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const subcities = await response.json();
                
                subcitySelect.innerHTML = '';
                subcities.forEach(subcity => {
                    const option = new Option(subcity.SubCityName, subcity.id);
                    subcitySelect.add(option);
                });
                
                subcitySelect.disabled = false;
            } catch (error) {
                console.error('Error loading subcities:', error);
                alert('Error loading subcities. Please try again.');
            }
        }

        // Handle city selection change
        citySelect.addEventListener('change', function() {
            loadSubcities(this.value);
        });

        // Load subcities if city is already selected (e.g., on form validation error)
        if (citySelect.value) {
            loadSubcities(citySelect.value);
        }
    });
</script>
@endpush