@extends('layouts.app')

@section('content')
<div class="container">
    @include('modals.download-confirmation-modal')
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if ( $errors )
                @foreach ( $errors->all() as $error )
                    <div class="alert alert-danger" role="alert">
                        {{$error}}
                    </div>
                @endforeach
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="row">
                         <div class="col-md-6">
                            <h3>{{$view_data['user']->team->team_name}}</h3>
                        </div>
                        <div class="col-md-6 ">
                            <button type="button" .
                                    class="btn btn-primary float-right" 
                                    data-toggle="modal"
                                    data-backdrop="static"
                                    data-keyboard="false"
                                    data-target="#download-confirmation-modal"
                                    >
                                Actualizar
                           </button>
                        </div>
                    </div>
                </div>

                <div class="card-body" style="display: block;">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Probando                    
                </div>
                <div class="card-footer text-center" style="display: block;">
                    Footer
                </div>
            </div>
        </div>
       
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jugadores</h3>
                </div>

                <div class="card-body table-responsive-md" style="display: block;">
                    <table class="table table-sm table-hover">
                        <thead>
                            <th>Nombre</th>
                            <th>Nacionalidad</th>
                            <th>Edad</th>
                            <th>Forma</th>
                            <th>Condición</th>
                            <th>Rapidez</th>
                            <th>Técnica</th>
                            <th>Pases</th>
                            <th>Portería</th>
                            <th>Defensa</th>
                            <th>Creación</th>
                            <th>Anotación</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @if($view_data['players']->count() == 0)
                                <tr class="table-danger text-center">
                                    <td colspan="13">No hay datos de los jugadores del equipo</td>
                                </tr>
                            @else
                                @foreach($view_data['players'] as $player)
                                    <tr>
                                        <td>{{$player->fullName()}}</td>
                                        <td>{{$player->country->name}}</td>
                                        <td>{{$player->age}}</td>
                                        <td>{{$player->lastRecord()->skillForm}}</td>
                                        <td>{{$player->lastRecord()->skillStamina}}</td>
                                        <td>{{$player->lastRecord()->skillPace}}</td>
                                        <td>{{$player->lastRecord()->skillTechnique}}</td>
                                        <td>{{$player->lastRecord()->skillPassing}}</td>
                                        <td>{{$player->lastRecord()->skillKeeper}}</td>
                                        <td>{{$player->lastRecord()->skillDefending}}</td>
                                        <td>{{$player->lastRecord()->skillPlaymaking}}</td>
                                        <td>{{$player->lastRecord()->skillScoring}}</td>
                                        <td>
                                            <button type="submit" class="btn btn-success btn-sm position-relative">
                                                Detalles
                                           </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>                  
                </div>
                <div class="card-footer text-center" style="display: block;">
                    Footer
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('input[name="ipassword"]').on('keyup', function(){
            if($(this).val() == ''){
                $('#download_button').prop('disabled', true);
            } else {
                $('#download_button').prop('disabled', false);
            }
        })

        $('#download-confirmation-modal').on('hide.bs.modal', function(){
            $('input[name="ipassword"]').val('');
            $('#download_button').prop('disabled', true);
        })
    });
</script>>
@endsection
