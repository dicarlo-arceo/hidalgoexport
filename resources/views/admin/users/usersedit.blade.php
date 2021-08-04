<div id="myModaledit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Registro de Usuarios</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" id="email1" name="email1" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Contraseña</label>
                                    <input type="text" id="password1" name="password1" class="form-control" placeholder="Contraseña">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Nombre</label>
                                    <input type="text" id="name1" name="name1" class="form-control" placeholder="Nombre">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Apellido paterno</label>
                                    <input type="text" id="firstname1" name="firstname1" class="form-control" placeholder="Apellido">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Apellido materno</label>
                                    <input type="text" id="lastname1" name="lastname1" class="form-control" placeholder="Apellido">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Celular</label>
                                    <input type="text" id="cellphone1" name="cellphone1" class="form-control" placeholder="Celular">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Perfil:</label>
                                    <select name="selectProfile1" id="selectProfile1" class="form-control" onchange="showimpEdit()">
                                        <option hidden selected>Selecciona una opción</option>
                                        @foreach ($profiles as $id => $profile)
                                            <option value='{{ $id }}'>{{ $profile }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="" style="display: none" id="etiqueta1">Clave de agente</label>
                                        <input type="text" id="code1" name="code1" class="form-control" style="display: none;">
                                        <br>
                                        <button type="button" id="agregarcol1" class="btn btn-primary" onclick="agregarcodigo1()" style="display: none;">Agregar</button>
                                    </div>
                                </div>
                            </div>
                            {{-- inicio tabla --}}
                            <div class="table-responsive">
                                <table class="table table-stripped table-hover text-center" id="tbcodes1" style="display: none">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Clave de agente</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-codigo1"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cancelarUsuario()" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="actualizarUsuario()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
