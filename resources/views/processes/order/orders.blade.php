@extends('home')
{{-- @section('title','Órdenes') --}}
@section('content')
    <div class="text-center"><h1>Órdenes</h1></div>
    <div style="max-width: 1200px; margin: auto;">
        {{-- inicia modal --}}
        <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="gridModalLabek">Movimientos</h4>
                        <button type="button" class="close" aria-label="Close" onclick="cancelarItem()"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid bd-example-row">
                            <div class="col-lg-12">
                                {{-- <div class="row align-items-center"> --}}
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
                                            <table class="table table-striped table-hover text-center" id="tbProf1">
                                                <thead>
                                                    <th class="text-center">Tienda</th>
                                                    <th class="text-center"># de Item</th>
                                                    <th class="text-center">Descripción</th>
                                                    <th class="text-center">Back Order</th>
                                                    <th class="text-center">Existencia</th>
                                                    <th class="text-center">Daños</th>
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
                                    <div class = "col-lg-4">
                                        <div class="form-group">
                                            <label for="">Tipo de Cambio</label>
                                            <input type="text" id="dlls" name="dlls" class="form-control" value = "20.12" onchange = "calculoTotales()">
                                        </div>
                                    </div>
                                    <div class = "col-lg-4">
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
                                    <div class = "col-lg-4">
                                        <div class="form-group">
                                            <label for="">Comisión</label>
                                            <div class="input-group mb-3">
                                                <input type="text" id="comition" name="comition" class="form-control" disabled>
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
                            <button type="button" onclick="newItem()" class="btn btn-primary">Agregar Item</button>
                        @endif
                        {{-- <button type="button" onclick="guardarperfil()" class="btn btn-primary">Exportar PDF</button> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- fin modal --}}
        {{-- modal nuevo --}}
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
        {{-- modal edit --}}
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
        {{-- modal orden --}}
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

    @include('processes.status.status')
        {{-- Inicia pantalla de inicio --}}
        <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
            <table class="table table-striped table-hover text-center" id="tbProf">
                <thead>
                    <th class="text-center"># de orden</th>
                    <th class="text-center">Proyecto</th>
                    <th class="text-center">Cliente</th>
                    <th class="text-center">Opciones</th>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr id="{{$order->id}}">
                            <td>{{$order->order_number}}</td>
                            <td>{{$order->project}}</td>
                            <td>{{$order->name}}</td>
                            <td>
                                <a href="#|" class="btn btn-primary" onclick="nuevoItem({{$order->id}},{{$profile}})" >Items</a>
                                @if ($perm_btn['erase']==1 || $perm_btn['modify']==1)
                                        @if ($perm_btn['modify']==1)
                                            <a href="#|" class="btn btn-warning" onclick="editarOrden({{$order->id}})" >Editar</a>
                                        @endif
                                        @if ($perm_btn['erase']==1)
                                            <a href="#|" class="btn btn-danger" onclick="eliminarOrden({{$order->id}})">Eliminar</a>
                                        @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('head')
    <script src="{{URL::asset('js/processes/order.js')}}"></script>
@endpush
