<div id="myModaledit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Registro de Servicios</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Agente:</label>
                                <select name="selectAgent1" id="selectAgent1" class="form-control">
                                    <option hidden selected>Selecciona una opción</option>
                                    @foreach ($agents as $id => $agent)
                                        <option value='{{ $id }}'>{{ $agent }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fecha de Ingreso</label>
                                <input type="date" id="entry_date1" name="entry_date1" class="form-control" placeholder="Fecha de Sistema">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Póliza</label>
                                <input type="text" id="policy1" name="policy1" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fecha de Respuesta</label>
                                <input type="date" id="response_date1" name="response_date1" class="form-control" placeholder="Fecha de Promotoria">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Descargado:</label>
                                <select name="selectDownload1" id="selectDownload1" class="form-control">
                                    <option hidden selected>Selecciona una opción</option>
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tipo de Servicio</label>
                                <input type="text" id="type1" name="type1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Folio</label>
                                <input type="text" id="folio1" name="folio1" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nombre del Contratante</label>
                                <input type="text" id="name1" name="name1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Expediente:</label>
                                <select name="selectRecord1" id="selectRecord1" class="form-control">
                                    <option hidden selected>Selecciona una opción</option>
                                    <option value="Fisico">Fisico</option>
                                    <option value="Digital">Digital</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Aseguradora:</label>
                                <select name="selectInsurance1" id="selectInsurance1" class="form-control">
                                    <option hidden selected>Selecciona una opción</option>
                                    @foreach ($insurances as $id => $insurance)
                                        <option value='{{ $id }}'>{{ $insurance }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Ramo:</label>
                                <select name="selectBranch1" id="selectBranch1" class="form-control">
                                    <option hidden selected>Selecciona una opción</option>
                                    @foreach ($branches as $id => $branch)
                                        <option value='{{ $id }}'>{{ $branch }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cancelarEditar()" class="btn btn-secundary">Cancelar</button>
                <button type="button" onclick="actualizarServicio()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
