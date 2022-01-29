@extends('layouts.app')
@section('styles')
    <style>
        td input {
            max-width: 30px;
        }
    </style>
@endsection
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
                    Progreso del jugador {{$view_data['data'][0]->player_name}}
                    @if ($view_data['data'][0]->active)
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                    @if (\Auth::guard('system_users')->check())
                        <div class="float-right">
                            {!! Form::open(['method' => 'DELETE','id'=>'form-delete','route' => ['delete_all', $view_data['data'][0]->sk_player_id],'style'=>'display:inline','onclick' => "return confirm('Seguro de eliminar?')"]) !!}

                                <button type="submit" class="btn btn-danger btn-sm position-relative">
                                    Eliminar jugador
                                </button>
                        {!! Form::close() !!}
                            <!-- <a class="btn btn-danger" href="{{route('delete_all', ['sokker_id' => $view_data['data'][0]->sk_player_id])}}">Eliminar jugador</a> -->
                        </div>
                        <div class="float-right  mr-5">
                            {!! Form::open(['method' => 'POST','id'=>'form-change','route' => ['change_active', $view_data['data'][0]->sk_player_id],'style'=>'display:inline','onclick' => "return confirm('Seguro de cambiar el estado del jugador dentro del concurso?')"]) !!}
                                {!! Form::hidden('active', $view_data['data'][0]->active) !!}
                                <button type="submit" class="btn btn-primary btn-sm position-relative">
                                    @if ($view_data['data'][0]->active)
                                        Inactivar
                                    @else
                                        Activar
                                    @endif
                                </button>
                            {!! Form::close() !!}
                        </div>
                    @endif

                </div>

                <div class="card-body">

                    @if(isset($message))
                        <div class="alert alert-success">
                            {{$message}}
                        </div>
                    @endif

                   <!-- Contenido Aqui -->
                   <table class="table table-hover table-sm text-center" id="ranking_all">
                      <thead class="thead-dark">
                        <tr >
                          <th scope="col">Edad</th>
                          <th scope="col">Condición</th>
                          <th scope="col">Rapidez</th>
                          <th scope="col">Técnica</th>
                          <th scope="col">Pases</th>
                          <th scope="col">Defensa</th>
                          <th scope="col">Creación</th>
                          <th scope="col">Anotación</th>
                          <th scope="col">Portería</th>
                          <th scope="col">Fecha de actualización</th>
                          <th scope="col">Puntuación</th>
                          <th scope="col">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach($view_data['data'] as $key => $data)
                            <tr>
                              <td>{{ $data->player_age }}</td>
                              <td>{{\Abilities::getAbility($data->stamina)}} [<strong>{{ $data->stamina }}</strong>]</td>
                              <td>{{\Abilities::getAbility($data->pace)}} [<strong>{{ $data->pace }}</strong>]</td>
                              <td>{{\Abilities::getAbility($data->technique)}} [<strong>{{ $data->technique }}</strong>]</td>
                              <td>{{\Abilities::getAbility($data->passing)}} [<strong>{{ $data->passing }}</strong>]</td>
                              <td>{{\Abilities::getAbility($data->defender)}} [<strong>{{ $data->defender }}</strong>]</td>
                              <td>{{\Abilities::getAbility($data->playmaker)}} [<strong>{{ $data->playmaker }}</strong>]</td>
                              <td>{{\Abilities::getAbility($data->striker)}} [<strong>{{ $data->striker }}</strong>]</td>
                              <td>{{\Abilities::getAbility($data->keeper)}} [<strong>{{ $data->keeper }}</strong>]</td>
                              <td>{{ \Carbon\Carbon::parse($data->created_at)->toDateString() }}</td>
                              <td>{{ number_format($data->score, 1) }}</td>
                              <td class="d-flex justify-content-around">
                                @if (\Auth::guard('system_users')->check())
                                {!! Form::open(['method' => 'DELETE','id'=>'form-delete','route' => ['delete_player', $data->id],'style'=>'display:inline','onclick' => "return confirm('Está seguro de eliminar el registro?')"]) !!}
                                        <button type="submit" class="btn btn-danger btn-sm position-relative">
                                            Eliminar
                                        </button>
                                {!! Form::close() !!}
                                @endif
                              </td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){

    });
</script>

@endsection
