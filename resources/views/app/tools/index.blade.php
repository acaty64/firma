@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">CONVERTIR PDF A JPG</div>

                <div class="card-body">
                    <form action="{{ route('pdf2jpg') }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" >
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{ $user_id }}" name="user_id">
                            Seleccione el archivo PDF:
                            <input type="file" name="archivo" id="archivo" class="btn btn-success ml-3" required accept="application/pdf">
                        </div>
                        <br>
                        <div class="row">
                            <button type="submit" class="btn btn-primary">Procesar</button>
                        </div>
                    </form>
                </div>
            </div>

            <br>
            <div class="card">
                <div class="card-header">RESIZE JPG to A4</div>

                <div class="card-body">
                    <form action="{{ route('resizejpg') }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" >
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{ $user_id }}" name="user_id">
                            Seleccione el archivo JPG a redimensionar:
                            <input type="file" name="file_jpg" id="file_jpg" class="btn btn-success ml-3" required accept="image/jpeg">
                        </div>
                        <br>
                        <div class="row">
                            <button type="submit" class="btn btn-primary">Procesar</button>
                        </div>
                    </form>
                </div>
            </div>








            </div>
        </div>
    </div>
</div>
@endsection
