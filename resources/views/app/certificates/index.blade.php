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
                        <input type="hidden" value="{{ $user_id }}" name="user_id">
                        Sube el archivo de certificado:
                        <input type="file" name="archivo" id="archivo">
                        <br>
                        Sube el archivo de fotografia:
                        <input type="file" name="photo" id="photo">
                        <br>
                        <button type="submit" class="btn-primary">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
