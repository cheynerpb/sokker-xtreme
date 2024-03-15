@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    Listado de Ediciones
                    <div class="float-right">
                        <a class="btn btn-secondary" href="{{route('editions.create')}}">Nueva</a>
                    </div>
                </div>

                <div class="card-body">
                   <!-- Contenido Aqui -->
                    <table class="table table-hover">
                        <thead>
                            <th>Nombre</th>
                            <th>Ganador</th>
                            <th>Equipo</th>
                            <th>Activa</th>
                            <th>Fecha de creación</th>
                            <th class="text-center">Opciones</th>
                        </thead>
                        <tbody>
                            @foreach ($view_data['editions'] as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->winner}}</td>
                                <td>{{$item->team}}</td>

                                <td>{!!$item->active==1?'<span class="badge badge-primary">Activa</span>':'<span class="badge badge-danger">Inactiva</span>'!!}</td>
                                <td>{{\Carbon\Carbon::parse($item->created_at)->toDateString()}}</td>
                                <td class="d-flex justify-content-around">
                                    <a class="btn btn-primary btn-sm position-relative mr-2" href="{{route('editions.edit',$item->id)}}">Editar</a>
                                    {!! Form::open(['method' => 'DELETE','id'=>'form-delete','route' => ['editions.destroy', $item->id],'style'=>'display:inline','onclick' => "return confirm('Seguro de eliminar?')"]) !!}

                                    <button type="submit" class="btn btn-danger btn-sm position-relative">
                                        Eliminar
                                   </button>
                                   {!! Form::close() !!}
                                </td>
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
