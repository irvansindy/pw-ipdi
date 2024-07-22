<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        .qr-code {
            position: absolute;
            bottom: 10px;
            left: 10px;
        }
        .pdf-content {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="pdf-content">
        @php
            dd($data)
        @endphp
        {{-- {!! $pdfContent !!} --}}
        <img src="data:image/png;base64,{{ $writer }}" class="qr-code">
    </div>
</body>
</html>
