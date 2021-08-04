<div id="modalEditEnterprise" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Actualizar Persona Moral</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Razón Social</label>
                                    <input type="text" id="business_name1" name="business_name1" class="form-control" placeholder="Razón Social">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Fecha de creación</label>
                                    <input type="date" id="date1" name="date1" class="form-control" placeholder="Fecha de creación">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">RFC</label>
                                    <input type="text" id="erfc1" name="erfc1" class="form-control" placeholder="RFC">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Calle</label>
                                    <input type="text" id="estreet1" name="estreet1" class="form-control" placeholder="Calle">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="">Número Exterior</label>
                                    <input type="text" id="ee_num1" name="ee_num1" class="form-control" placeholder="Número Exterior">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="">Número Interior</label>
                                    <input type="text" id="ei_num1" name="ei_num1" class="form-control" placeholder="Número Interior">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="">Código Postal</label>
                                    <input type="text" id="epc1" name="epc1" class="form-control" placeholder="Código Postal">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="">Colonia</label>
                                    <input type="text" id="esuburb1" name="esuburb1" class="form-control" placeholder="Colonia">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="">Municipio</label>
                                    <input type="text" id="ecity1" name="ecity1" class="form-control" placeholder="Municipio">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="">Estado</label>
                                    <input type="text" id="estate1" name="estate1" class="form-control" placeholder="Estado">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="">País</label>
                                    <input type="text" id="ecountry1" name="ecountry1" class="form-control" placeholder="País">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Celular</label>
                                    <input type="text" id="ecellphone1" name="ecellphone1" class="form-control" placeholder="Celular">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Correo</label>
                                    <input type="text" id="eemail1" name="eemail1" class="form-control" placeholder="Correo">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Nombre del contacto</label>
                                    <input type="text" id="name_contact1" name="name_contact1" class="form-control" placeholder="Nombre Completo">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Celular de contacto</label>
                                    <input type="text" id="phone_contact1" name="phone_contact1" class="form-control" placeholder="Celular de contacto">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cancelarEmpresa()" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="actualizarEmpresa()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
