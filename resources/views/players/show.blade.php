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
            <div class="card m">
                <div class="card-header">Insertar ID del jugador
                    <div class="float-right">
                        {{ Form::open( ['id' => 'update_all', 'class' => 'form', 'route' => 'update_all'] ) }}
                            <button class="btn btn-secondary" type="submit">Actualizar todos</button>
                        {{ Form::close() }}
                    </div>
                </div>
                {{ Form::open( ['id' => 'insert_ID', 'class' => 'form', 'route' => 'store_player_by_id'] ) }}

                <div class="card-body">

                    @if(isset($message_id))
                        <div class="alert alert-{{(isset($error) && $error == true) ? 'danger' : 'success' }}">
                            {{$message_id}}
                        </div>
                    @endif

                   <!-- Contenido Aqui -->
                     <div class="row">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                             <!--  <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2"> -->
                                {{ Form::text( 'player_id', '', ['id' => 'player_id', 'class' => 'form-control', 'required', 'aria-describedby' => "button-addon2"] ) }}

                              <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Salvar</button>
                                </div>
                            </div>
                            @if ( $errors->get( 'player_id' ) )
                                @foreach ( $errors->get( 'player_id' ) as $error )
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                   </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="row">
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="card">
                <div class="card-header">Insertar datos del jugador manualmente
                </div>
                {{ Form::open( ['id' => 'insert_manual', 'class' => 'form', 'route' => 'store_player_manually'] ) }}

                <div class="card-body">

                    @if(isset($message))
                        <div class="alert alert-{{(isset($error) && $error == true) ? 'danger' : 'success' }}">
                            {{$message}}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'player_name', 'Nombre')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::text( 'player_name', null ,['class' => 'form-control', 'required'] ) }}
                                        @if ( $errors->get( 'player_name' ) )
                                            @foreach ( $errors->get( 'player_name' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'player_age', 'Edad')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::number( 'player_age', null ,['class' => 'form-control', 'required', 'min' => 16, 'step' => 1, 'max' => 20] ) }}
                                        @if ( $errors->get( 'player_age' ) )
                                            @foreach ( $errors->get( 'player_age' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'sk_player_id', 'Sokker Player ID')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::number( 'sk_player_id', null ,['class' => 'form-control', 'required', 'min' => 0, 'step' => 1] ) }}
                                        @if ( $errors->get( 'sk_player_id' ) )
                                            @foreach ( $errors->get( 'sk_player_id' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'team', 'Equipo')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::text( 'team', null ,['class' => 'form-control', 'required'] ) }}
                                        @if ( $errors->get( 'team' ) )
                                            @foreach ( $errors->get( 'team' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                   <!-- Contenido Aqui -->
                     <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'stamina', 'Condición')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select( 'stamina', \Abilities::allAbilities(), null ,['class' => 'form-control', 'required', 'placeholder' => 'Seleccione la habilidad'] ) }}
                                        @if ( $errors->get( 'stamina' ) )
                                            @foreach ( $errors->get( 'stamina' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'keeper', 'Portería')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select( 'keeper', \Abilities::allAbilities(), null ,['class' => 'form-control', 'required', 'placeholder' => 'Seleccione la habilidad'] ) }}
                                        @if ( $errors->get( 'keeper' ) )
                                            @foreach ( $errors->get( 'keeper' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'pace', 'Rapidez')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select( 'pace', \Abilities::allAbilities(), null ,['class' => 'form-control', 'required', 'placeholder' => 'Seleccione la habilidad'] ) }}
                                        @if ( $errors->get( 'pace' ) )
                                            @foreach ( $errors->get( 'pace' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'defender', 'Defensa')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select( 'defender', \Abilities::allAbilities(), null ,['class' => 'form-control', 'required', 'placeholder' => 'Seleccione la habilidad'] ) }}
                                        @if ( $errors->get( 'defender' ) )
                                            @foreach ( $errors->get( 'defender' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'technique', 'Técnica')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select( 'technique', \Abilities::allAbilities(), null ,['class' => 'form-control', 'required', 'placeholder' => 'Seleccione la habilidad'] ) }}
                                        @if ( $errors->get( 'technique' ) )
                                            @foreach ( $errors->get( 'technique' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'playmaker', 'Creación')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select( 'playmaker', \Abilities::allAbilities(), null ,['class' => 'form-control', 'required', 'placeholder' => 'Seleccione la habilidad'] ) }}
                                        @if ( $errors->get( 'playmaker' ) )
                                            @foreach ( $errors->get( 'playmaker' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'passing', 'Pases')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select( 'passing', \Abilities::allAbilities(), null ,['class' => 'form-control', 'required', 'placeholder' => 'Seleccione la habilidad'] ) }}
                                        @if ( $errors->get( 'passing' ) )
                                            @foreach ( $errors->get( 'passing' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label( 'striker', 'Anotación')}}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select( 'striker', \Abilities::allAbilities(), null ,['class' => 'form-control', 'required', 'placeholder' => 'Seleccione la habilidad'] ) }}
                                        @if ( $errors->get( 'striker' ) )
                                            @foreach ( $errors->get( 'striker' ) as $error )
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                   </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary" id="save" type="submit" >Salvar</button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="card">
                <div class="card-header">Insertar datos del jugador (según plantilla usada por el Osokker)
                </div>
                {{ Form::open( ['id' => 'insert_Data', 'class' => 'form', 'route' => 'store_player'] ) }}

                <div class="card-body">

                    @if(isset($message))
                        <div class="alert alert-{{(isset($error) && $error == true) ? 'danger' : 'success' }}">
                            {{$message}}
                        </div>
                    @endif

                   <!-- Contenido Aqui -->
                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label( 'data', 'Datos')}}
                                {{ Form::textarea( 'data', '', ['id' => 'data', 'class' => 'form-control', 'required'] ) }}
                                @if ( $errors->get( 'data' ) )
                                    @foreach ( $errors->get( 'data' ) as $error )
                                        <div class="text-danger">{{ $error }}</div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                   </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary" id="save" type="submit" >Salvar</button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
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
