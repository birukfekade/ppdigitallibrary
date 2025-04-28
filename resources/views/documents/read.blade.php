<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Document - {{ $document->title }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #f4f4f4;
            width: 100vw;
            height: 100vh;
        }

        .document-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .document-content {
            width: 100%;
            height: 100%;
        }

        .document-content iframe,
        .document-content img {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            color: black;
            font-size: 120px;
            font-family: Arial, Helvetica, sans-serif;
            opacity: 0.3;
            pointer-events: none;
            user-select: none;
            text-align: center;
            white-space: nowrap;
            z-index: 10;
        }
    </style>

</head>

<body>
    <div class="document-container">
        <div class="document-content">
            @if ($mimeType === 'application/pdf')
            <iframe src="{{ $streamUrl }}" title="{{ $document->title }}"></iframe>
            @elseif (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif']))
            <img src="{{ $streamUrl }}" alt="{{ $document->title }}">
            @else
            <div class="alert alert-warning text-center">
                Unsupported file type for inline display.
            </div>
            @endif
        </div>
        <div class="watermark">{{ $userName }}</div>
    </div>
    <script>
        // Prevent right-click to reduce downloading (not foolproof)
        document.addEventListener('contextmenu', e => e.preventDefault());

        // Reposition watermark on scroll for multi-page PDFs
        window.addEventListener('scroll', () => {
            const watermark = document.querySelector('.watermark');
            watermark.style.top = `${window.innerHeight / 2 + window.scrollY}px`;
        });
    </script>
</body>

</html>