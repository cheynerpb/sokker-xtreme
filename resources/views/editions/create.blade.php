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
            <div class="card">
                <div class="card-header">Crear Edición
                </div>
                {{ Form::open( ['id' => 'create_new', 'class' => 'form', 'route' => 'editions.store'] ) }}

                <div class="card-body">
                   <!-- Contenido Aqui -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label( 'name', 'Nombre')}}
                                {{ Form::text( 'name', null, ['id' => 'new_name', 'class' => 'form-control', 'required'] ) }}
                                @if ( $errors->get( 'name' ) )
                                    @foreach ( $errors->get( 'name' ) as $error )
                                        <div class="text-danger">{{ $error }}</div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('active', 'Activa:') !!}
                                <div class="custom-control custom-checkbox">
                                    {!! Form::checkbox('active',null,null, array('class' => 'custom-control-input', 'id'=>"defaultUnchecked")) !!}
                                    <label class="custom-control-label" for="defaultUnchecked">  Activa</label>
                                </div>
                                @if ( $errors->get( 'active' ) )
                                    @foreach ( $errors->get( 'active' ) as $error )
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
                            <a class="btn btn-light" href="{{route('editions.index')}}" >Atrás</a>
                        </div>
                        <div class="col-md-1 offset-5">
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
