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
                <div class="card-header">Resultado
                </div>
                {{ Form::open( ['id' => 'insert_Data', 'class' => 'form', 'route' => 'get_data'] ) }}

                <div class="card-body">

                    @if(isset($message))
                        <div class="alert alert-{{(isset($error) && $error == true) ? 'danger' : 'success' }}">
                            {{$message}}
                        </div>
                    @endif
                 
                   <!-- Contenido Aqui -->
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label( 'response', $result)}}
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
@endsection
@section('scripts')
<script>
    $(document).ready(function(){

    });
</script>

@endsection
