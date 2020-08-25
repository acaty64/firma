<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <img src="{{ asset($file) }}" width="100%" height="100%" />
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
        size: 21cm 29.7cm;
        margin: 0;
    }
    page-break-after:always;
</style>