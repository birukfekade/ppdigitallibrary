<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>
    <style>
    #pdf-viewer {
        width: 100%;
        height: 800px;
        margin: 0 auto;
        border: 1px solid #ccc;
    }
    .loader {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $document->title ?? 'Document Viewer' }}</h5>
                    <a href="{{ route('mydocuments') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                        @if(strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)) === 'pdf')
                        <iframe 
                            src="{{ asset('pdfjs/web/viewer.html?file=' . urlencode(asset('uploads/Documents/'.$document->file_path))) }}"
                            id="pdf-viewer"
                            frameborder="0">
                        </iframe>
                    @else
                        <div class="alert alert-info">
                            This file type cannot be previewed. 
                            <a href="{{ asset('uploads/Documents/'.$document->file_path) }}" class="btn btn-primary btn-sm ms-2">
                                Download File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>    
</body>
