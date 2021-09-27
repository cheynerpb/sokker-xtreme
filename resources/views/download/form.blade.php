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
                <div class="card-header">Descargar datos
                </div>
                {{ Form::open( ['id' => 'insert_Data', 'class' => 'form', 'route' => 'download_data'] ) }}

                <div class="card-body">

                    @if(isset($message))
                        <div class="alert alert-{{$class}}">
                            {{$message}}
                        </div>
                    @endif

                   <!-- Contenido Aqui -->
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label( 'ilogin', 'Usuario')}}
                                {{ Form::text( 'ilogin', '', ['id' => 'ilogin', 'class' => 'form-control', 'required'] ) }}
                                @if ( $errors->get( 'ilogin' ) )
                                    @foreach ( $errors->get( 'ilogin' ) as $error )
                                        <div class="text-danger">{{ $error }}</div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label( 'ipassword', 'Contraseña')}}
                                {{ Form::password( 'ipassword', ['id' => 'ipassword', 'class' => 'form-control', 'required'] ) }}
                                @if ( $errors->get( 'ipassword' ) )
                                    @foreach ( $errors->get( 'ipassword' ) as $error )
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
                            <button class="btn btn-primary" id="save" type="submit" >Download</button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        /*$('#save').on('click', function(){
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: 'https://sokker.org/start.php?session=xml',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        ilogin: $('input[name="ilogin"]').val(),
                        ipassword: $('input[name="ipassword"]').val()
                    },
                    beforeSend: function(){
                        alert('Consultar')
                    },
                    success: function(response){
                        console.log(response);
                    },
                    error: function(response){
                        alert('error')
                        console.log(response)
                    },
                    complete: function(){
                        alert('Terminado')
                    }
                });
        })*/
    });
</script>
@endsection
