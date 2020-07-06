<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>{{ $originalName }}</title>
    </head>
    <body>
        @foreach($images as $image)
            <img src="{{ asset($image) }}" width="100%" height="100%" />
        @endforeach
    </body>
</html>
<style>
    body {
      margin: 0;
      margin-top: 0;
      padding: 0;
    }
    img {
        margin:0;
        padding: 0;
    }
    @page {
        size: 21cm 29.9cm;
        margin: 0;
    }
    page-break-after:always;
</style>