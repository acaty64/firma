@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ACCESOS AUTORIZADOS AL MÓDULO</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('access.store') }}">
                        @csrf
                        <div class="form-group">
                            <select name="newuser" id="newuser" class="form-control" > 
                                <option value="" selected disabled>Seleccione un nuevo usuario</option>
                                @foreach($no_users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Agregar</button>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Apellidos y Nombres</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                  <td>{{ $user->id }}</td>
                                  <td>{{ $user->name }}</td>
                                  <td><a href="{{ route('access.destroy', $user->access->id) }}" class="btn btn-danger" role="button">Eliminar</a>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection