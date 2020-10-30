@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">CONSTANCIA DE PRIMERA MATRICULA</div>

                <div class="card-body">
                    <form action="{{ route('c1m.merge') }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" >
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{ $user_id }}" name="user_id">
                            Seleccione el archivo de constancia:
                            <input type="file" name="archivo" id="archivo" class="btn btn-success ml-3" required accept="application/pdf">
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
