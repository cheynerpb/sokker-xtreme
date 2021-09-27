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
                            <button class="btn btn-secondary" type="submit">Actualizar todos</a>
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
