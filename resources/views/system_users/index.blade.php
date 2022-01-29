@extends('layouts.app')

<style>
    .change-status {
        cursor: pointer;
    }
</style>

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(isset($message_id))
                <div class="alert alert-{{(isset($error) && $error == true) ? 'danger' : 'success' }}">
                    {{$message_id}}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    Usuarios
                </div>

                <div class="card-body">

                   <!-- Contenido Aqui -->
                    <table class="table table-hover">
                        <thead>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @foreach ($view_data['system_users'] as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{!!$user->active == true ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>'!!}</td>
                                <td>
                                    {!! Form::open(['method' => 'POST','id'=>'active-user','route' => ['active_user', $user->id],'style'=>'display:inline']) !!}
                                        {!! Form::hidden('active', $user->active) !!}
                                        <button type="submit" class="btn btn-primary btn-sm position-relative">
                                            @if ($user->active)
                                                Inactivar
                                            @else
                                                Activar
                                            @endif
                                        </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    {{$view_data['system_users']->render()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

