@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">DESCARGA DE CONSTANCIA DE PRIMERA MATRICULA</div>
                <div class="card-body">
                    <div class="row">
                        <a href="{{ $file }}" download class="btn btn-primary">
                          Descargar Archivo
                        </a>
                    </div>
                    <br>
                    <iframe src="{{ $file }}" frameborder="0" style="width: 100%; height:50vw; position: relative; allowfullscreen;"></iframe>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style type="text/css">
    html, body, div#content { margin:0; padding:0; height:100%; }
    iframe { display:block; width:100%; border:none; }
</style>
@endsection

@section('view','app/c1m/download.blade.php')