{{-- inicia modal abrir items --}}
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Items</h4>
                <button type="button" class="close" aria-label="Close" onclick="cancelarItem()"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-lg-12">
                        {{-- <div class="row align-items-center"> --}}
                        <br>
                        <div class="row">
                            @if ($perm_btn['modify']==1)
                                <div class = "col-lg-2">
                                    <div class="form-group">
                                        <input class="form-check-input" type="checkbox" onclick="chkAll()" id="chkAll">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="form-check-label">
                                            Seleccionar Todos
                                        </label>
                                    </div>
                                </div>
                                <div class = "col-lg-2">
                                    <div class="form-group">
                                        <button type="button" id="btnChangeAll" class="btn btn-primary" onclick="abrirEstatusTodos()" disabled>Editar Selección</a>
                                    </div>
                                </div>
                                <div class = "col-lg-2">
                                    <div class="form-group">
                                        <button type="button" id="btnBOAll" class="btn btn-primary" onclick="moverBO()" disabled>BO -> Exist.</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
                                    <table class="table table-striped table-hover text-center" id="tbProf1">
                                        <thead>
                                            @if($perm_btn['modify']==1) <th class="text-center"></th> @endif
                                            <th class="text-center">Tienda</th>
                                            <th class="text-center"># de Item</th>
                                            <th class="text-center">Descripción</th>
                                            <th class="text-center">Back Order</th>
                                            <th class="text-center">Existencia</th>
                                            <th class="text-center">TR</th>
                                            <th class="text-center">Estatus</th>
                                            <th class="text-center">Precio Neto</th>
                                            <th class="text-center">Precio Total</th>
                                            @if ($perm_btn['erase']==1)
                                                <th class="text-center">Opciones</th>
                                            @endif
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class = "col-lg-12">
                        <div class = "row">
                            <div class = "col-lg-3">
                                <div class="form-group">
                                    <label for="">Tipo de Cambio</label>
                                    <input type="text" id="dlls" name="dlls" class="form-control" onchange = "calculoDlls()" @if($perm_btn['modify']!=1) disabled @endif>
                                    {{-- <input type="text" id="dlls" name="dlls" class="form-control"> --}}
                                </div>
                            </div>
                            <div class = "col-lg-3">
                                <div class="form-group">
                                    <label for="">Porcentaje</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="percent" name="percent" class="form-control" onchange = "calculoPerc()" @if($perm_btn['modify']!=1) disabled @endif>
                                        {{-- <input type="text" id="percent" name="percent" class="form-control"> --}}
                                        <div class="input-group-append">
                                          <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="">Paquetería</label>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">$</div>
                                        </div>
                                        <input type="text" id="exp" name="exp" class="form-control" onchange = "calculoExp()" @if($perm_btn['modify']!=1) disabled @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="">Moneda</label> <br>
                                    <input id = "onoffCurrency" type="checkbox" data-toggle="toggle" data-on = "USD" data-off="MXN" data-width="80" onchange=calculoCurrency({{$profile}})>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="">Redondeo a 100</label> <br>
                                    <input id = "onoffRound" type="checkbox" data-toggle="toggle" data-on = "SI" data-off="NO" data-width="80" onchange=calculoRound({{$profile}})>
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-lg-3">
                                <div class="form-group">
                                    <label for="">Bodega</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="cellar" name="cellar" class="form-control" disabled>
                                        <div class="input-group-append">
                                            <span class="input-group-text">USD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-lg-3">
                                <div class="form-group">
                                    <label for="">Importación</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="comition" name="comition" class="form-control" disabled>
                                        <div class="input-group-append">
                                            <span class="input-group-text">USD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-lg-3">
                                <div class="form-group">
                                    <label for="">Broker</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="text" id="broker" name="broker" class="form-control" onchange=calculoBroker()>
                                        <div class="input-group-append">
                                          <span class="input-group-text">USD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-lg-3">
                                <div class="form-group">
                                    <label for="">Total a pagar</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="paytotal" name="paytotal" class="form-control" disabled>
                                        <div class="input-group-append">
                                          <span class="input-group-text">USD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-lg-4">
                                <div class="form-group">
                                    <label for="">Total en pesos</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="net_mxn" name="net_mxn" class="form-control" disabled>
                                        <div class="input-group-append">
                                          <span class="input-group-text">MXN</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-lg-4">
                                <div class="form-group">
                                    <label for="">IVA</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="iva" name="iva" class="form-control" disabled>
                                        <div class="input-group-append">
                                          <span class="input-group-text">MXN</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "col-lg-4">
                                <div class="form-group">
                                    <label for="">Precio a facturar</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="total_invoice" name="total_invoice" class="form-control" disabled>
                                        <div class="input-group-append">
                                          <span class="input-group-text">MXN</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secundary" onclick="cancelarItem()">Cancelar</button>
                @if ($perm_btn['addition']==1)
                    <button type="button" id="btnNewItem" style="display: none;" onclick="newItem()" class="btn btn-primary">Agregar Item</button>
                    <button type="button" id="btnHojaCobro" onclick="abrirPDF()" class="btn btn-primary">Hoja de Cobro</button>
                @endif
                <button type="button" id="btnDescargarItem" onclick="abrirPDFItems()" class="btn btn-primary">Descargar Items en PDF</button>
                {{-- <button type="button" onclick="guardarperfil()" class="btn btn-primary">Exportar PDF</button> --}}
            </div>
        </div>
    </div>
</div>
{{-- fin modal --}}
{{-- modal nuevo item --}}
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Registro de Item</h4>
                <button type="button" class="close" onclick="cancelarNewItem()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tienda</label>
                                    <input type="text" id="store" name="store" class="form-control" placeholder="Tienda">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""># de Item</label>
                                    <input type="text" id="item_number" name="item_number" class="form-control" placeholder="# de item">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Descripción</label>
                                    <input type="text" id="description" name="description" class="form-control" placeholder="Descripción">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Back Order</label>
                                    <input type="text" id="back_order" name="back_order" class="form-control" placeholder="Back Order">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Existencias</label>
                                    <input type="text" id="existence" name="existence" class="form-control" placeholder="Existencias" onchange="calculo()">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Precio Neto</label>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">$</div>
                                        </div>
                                        <input type="text" id="net_price" name="net_price" class="form-control" placeholder="Precio Neto" onchange="calculo()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Precio Total</label>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">$</div>
                                        </div>
                                        <input type="text" id="total_price" name="total_price" class="form-control" placeholder="Precio Total" data-type="currency" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secundary" onclick="cancelarNewItem()">Cancelar</button>
                <button type="button" onclick="guardarItem()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
{{-- fin modal| --}}
{{-- modal editar item --}}
<div id="myModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Registro de Item</h4>
                <button type="button" class="close" onclick="cancelarEditItem()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tienda</label>
                                    <input type="text" id="store1" name="store1" class="form-control" placeholder="Tienda">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""># de Item</label>
                                    <input type="text" id="item_number1" name="item_number1" class="form-control" placeholder="# de item">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Descripción</label>
                                    <input type="text" id="description1" name="description1" class="form-control" placeholder="Descripción">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Back Order</label>
                                    <input type="text" id="back_order1" name="back_order1" class="form-control" placeholder="Back Order">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Existencias</label>
                                    <input type="text" id="existence1" name="existence1" class="form-control" placeholder="Existencias" onchange="calculo1()">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Precio Neto</label>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">$</div>
                                        </div>
                                        <input type="text" id="net_price1" name="net_price1" class="form-control" placeholder="Precio Neto" onchange="calculo1()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Precio Total</label>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">$</div>
                                        </div>
                                        <input type="text" id="total_price1" name="total_price1" class="form-control" placeholder="Precio Total" data-type="currency" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secundary" onclick="cancelarEditItem()">Cancelar</button>
                <button type="button" onclick="actualizarItem()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
{{-- fin modal| --}}
{{-- modal editar orden --}}
<div id="orderModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Editar orden</h4>
                <button type="button" class="close" onclick="cerrarOrden()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Número de Orden</label>
                                    <input type="text" id="order" name="order" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Proyecto: </label>
                                <select name="selectProject" id="selectProject" class="form-select">
                                    <option hidden selected>Selecciona una opción</option>
                                    @foreach ($projects as $id => $project)
                                        <option value='{{ $id }}'>{{ $project }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Diseñador</label>
                                    <input type="text" id="designer" name="designer" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label for="">Dirección: </label>
                                    <textarea name="address" id="address" class = "form-control", rows = "3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secundary" onclick="cerrarOrden()">Cancelar</button>
                <button type="button" onclick="actualizarOrden()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
{{-- fin modal --}}
{{-- modal edit TR --}}
<div id="myModalTR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Trámite</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Número de Trámite</label>
                                    <input type="text" id="tr" name="tr" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cancelarTR()" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="guardarTR()" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
{{-- fin modal| --}}
{{-- inicia modal selector de tr --}}
<div id="mySelectTRModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        {{-- <form action="orders/updateStatus" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="text" id="id" name="id" hidden> --}}

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Trámite</h4>
                <button type="button" onclick="cerrarSelectTR()" class="close" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row" id = "sTR" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Seleccionar el número de trámite:</label>
                                    <select name="selectTR" id="selectTR" class="form-select">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="lvl" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>No hay trámites disponibles</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarSelectTR()" class="btn btn-secundary">Cancelar</button>
                <button type="button" id="btnAcceptTr" style="display: none;" onclick="abrirSeleccionTR()" class="btn btn-primary">Aceptar</button>
            </div>
        {{-- </form> --}}
        </div>
    </div>
</div>
{{-- fin modal --}}
{{-- modal PDF --}}
<div id="myModalPDF" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">PDF</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Fecha</label>
                                    <input type="date" name="datePDF" id="datePDF" class = "form-control", rows = "3">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Número de Bultos</label>
                                    <input type="text" id="pkgs" name="pkgs" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class = "col-lg-12">
                                <div class="form-group">
                                    <label for="">¿Incluir datos de pago?  </label>
                                    <input id = "paymntDetails" type="checkbox" data-toggle="toggle" data-on = "Si" data-off="No" data-width="180">
                                    {{-- <input type="text" id="dlls" name="dlls" class="form-control"> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarPDF()" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="dwnldPDF()" class="btn btn-primary">Descargar</button>
            </div>
        </div>
    </div>
</div>
{{-- fin modal| --}}
{{-- modal modal descargar PDF --}}
<div id="myModalPDFItems" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">PDF</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class = "col-lg-12">
                                <div class="form-group">
                                    <label for="">¿Incluir datos de pago?  </label>
                                    <input id = "paymntDetailsItems" type="checkbox" data-toggle="toggle" data-on = "Si" data-off="No" data-width="180" onchange="showInvoice()">
                                    {{-- <input type="text" id="dlls" name="dlls" class="form-control"> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row" id="invoiceRow" style="display: none;">
                            <div class = "col-lg-12">
                                <div class="form-group">
                                    <label for="">¿Incluir datos de facturación?  </label>
                                    <input id = "invoiceDetailsItems" type="checkbox" data-toggle="toggle" data-on = "Si" data-off="No" data-width="180">
                                    {{-- <input type="text" id="dlls" name="dlls" class="form-control"> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarPDFItems()" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="ItemsPDF()" class="btn btn-primary">Descargar</button>
            </div>
        </div>
    </div>
</div>
{{-- fin modal| --}}
{{-- inicia modal selector de tr y cobro para hoja cobro --}}
<div id="mySelectHojaC" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        {{-- <form action="orders/updateStatus" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="text" id="id" name="id" hidden> --}}

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Trámite</h4>
                <button type="button" onclick="cerrarSelectTR()" class="close" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row" id = "sTROrder" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Seleccionar el número de trámite:</label>
                                        <select name="selectTROrder" id="selectTROrder" class="form-select">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Seleccionar la dirección:</label>
                                        <select name="selectAddressOrder" id="selectAddressOrder" class="form-select">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Fecha</label>
                                        <input type="date" name="datePDFOrder" id="datePDFOrder" class = "form-control", rows = "3">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Número de Bultos</label>
                                        <input type="text" id="pkgsOrder" name="pkgs" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class = "col-lg-12">
                                    <div class="form-group">
                                        <label for="">¿Incluir datos de pago?  </label>
                                        <input id = "paymntDetailsOrder" type="checkbox" data-toggle="toggle" data-on = "Si" data-off="No" data-width="180">
                                        {{-- <input type="text" id="dlls" name="dlls" class="form-control"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="lvlOrder" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>No hay trámites disponibles</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarSelectTROrder()" class="btn btn-secundary">Cancelar</button>
                <button type="button" id="btnAcceptOrder" style="display: none;" onclick="DwnldHojaCobroTodos()" class="btn btn-primary">Aceptar</button>
            </div>
        {{-- </form> --}}
        </div>
    </div>
</div>
{{-- fin modal --}}
{{-- modal modal descargar PDF de varias ordenes --}}
<div id="myModalPDFItemsAll" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">PDF</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row" id="sTRItems" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Seleccionar el número de trámite:</label>
                                        <select name="selectTRItems" id="selectTRItems" class="form-select">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Estatus:</label>
                                        <select name="selectStatusItemsAll" id="selectStatusItemsAll" class="form-select" @if($perm_btn['modify']!=1) disabled @endif>
                                            <option hidden selected value="0">Selecciona una opción</option>
                                            @foreach ($cmbStatus as $id => $status)
                                                <option value='{{ $id }}'>{{ $status }}</option>
                                            @endforeach
                                            <option value="0">Todos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class = "col-lg-12">
                                    <div class="form-group">
                                        <label for="">¿Incluir datos de pago?  </label>
                                        <input id = "paymntDetailsItemsAll" type="checkbox" data-toggle="toggle" data-on = "Si" data-off="No" data-width="180" onchange="showInvoiceAll()">
                                        {{-- <input type="text" id="dlls" name="dlls" class="form-control"> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="invoiceRowAll" style="display: none;">
                                <div class = "col-lg-12">
                                    <div class="form-group">
                                        <label for="">¿Incluir datos de facturación?  </label>
                                        <input id = "invoiceDetailsItemsAll" type="checkbox" data-toggle="toggle" data-on = "Si" data-off="No" data-width="180">
                                        {{-- <input type="text" id="dlls" name="dlls" class="form-control"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="lvlItems" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>No hay trámites disponibles</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarPDFItemsAll()" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnAcceptItems" style="display: none;" onclick="DwnldItemsTodos()" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>
{{-- fin modal| --}}
{{-- inicia modal selector de tr para guardar recibos --}}
<div id="myReciptTR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        {{-- <form action="orders/updateStatus" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="text" id="id" name="id" hidden> --}}

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Trámite</h4>
                <button type="button" onclick="cerrarSelectTRRecipt()" class="close" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row" id = "sTRRecipt" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Seleccionar el número de trámite:</label>
                                        <select name="selectTRRecipt" id="selectTRRecipt" class="form-select">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <label for="">Recibo: </label>
                                        <input type="file" name="receipt" id="receipt" class="form-control" accept="image/*"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="lvlRecipt" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>No hay trámites disponibles</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarSelectTRRecipt()" class="btn btn-secundary">Cancelar</button>
                <button type="button" id="btnAcceptRecipt" style="display: none;" onclick="SaveAssign()" class="btn btn-primary">Aceptar</button>
            </div>
        {{-- </form> --}}
        </div>
    </div>
</div>
{{-- fin modal --}}
{{-- modal ver items varias ordenes --}}
<div id="myModalViewItemsAll" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Items</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Seleccionar el número de trámite:</label>
                                        <select name="selectTRViewItems" id="selectTRViewItems" class="form-select">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Estatus:</label>
                                        <select name="selectStatusViewItemsAll" id="selectStatusViewItemsAll" class="form-select" @if($perm_btn['modify']!=1) disabled @endif>
                                            <option hidden selected value="0">Selecciona una opción</option>
                                            @foreach ($cmbStatus as $id => $status)
                                                <option value='{{ $id }}'>{{ $status }}</option>
                                            @endforeach
                                            <option value="0">Todos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarViewItemsAll()" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="VerItemsTodos()" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>
{{-- fin modal| --}}
