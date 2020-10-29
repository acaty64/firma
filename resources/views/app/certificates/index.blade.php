@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">CERTIFICADOS DE ESTUDIOS</div>

                <div class="card-body">
                    <form action="{{ route('merge') }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" >
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{ $user_id }}" name="user_id">
                            Seleccione el archivo de certificado:
                            <input type="file" name="archivo" id="archivo" class="btn btn-success ml-3" required accept="application/pdf">
                        </div>
                        <br>
                        <div class="row">
                            Seleccione el archivo de fotografía:
                            <input type="file" name="photo" id="photo" class="btn btn-success ml-4" required accept="image/jpeg">
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
@endsection
