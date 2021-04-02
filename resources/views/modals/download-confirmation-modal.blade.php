<div class="modal fade" id="download-confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="download-confirmation-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(['route'=>'download_players']) }}
            <div class="modal-header">
                <h5 class="modal-title" id="download-confirmation-modal-title">Actualizar datos del equipo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-12">
                        <p>Teniendo en cuenta que el sistema guarda su contraseña encriptada, es necesario que ingrese nuevamente su contraseña para actualizar los datos de sus jugadores</p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                {{ Form::label('name', "Usuario Sokker") }}
                            </div>
                            <div class="col-8">
                                {{ Form::text('name', auth()->user()->name, ['class'=>'form-control acm-input-text', 'required', 'readonly']) }}
                                <span id="name-error-msg" class="error-message modal-error-msg" hidden></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                {{ Form::label( 'ipassword', 'Contraseña')}}
                            </div>
                            <div class="col-8">
                                {{ Form::password( 'ipassword', ['id' => 'ipassword', 'class' => 'form-control', 'required'] ) }}
                                <span id="ipassword-error-msg" class="error-message modal-error-msg" hidden></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="download_button" type="submit" class="btn btn-primary" disabled>Actualizar</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>