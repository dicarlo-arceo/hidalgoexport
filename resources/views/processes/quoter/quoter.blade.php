@extends('home')
{{-- @section('title','Órdenes') --}}
@section('content')
    <div class="text-center" id="ordTitle"><h1>Cotizador</h1></div>
    <div style="max-width: 1200px; margin: auto;">
    {{-- modal orden --}}
    <div id="orderModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="gridModalLabek">Nuevo orden</h4>
                    <button type="button" class="close" onclick="closeModal('#orderModal')" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid bd-example-row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Cotización</label>
                                        <input type="text" id="quote_number" name="quote_number" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Cliente: </label>
                                        <select name="selectClient" id="selectClient" class="form-select">
                                            <option hidden value=0 selected>Selecciona una opción</option>
                                            @foreach ($clients as $id => $client)
                                                <option value='{{ $id }}'>{{ $client }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Proyecto: </label>
                                        <select name="selectProject" id="selectProject" class="form-select">
                                            <option hidden value=0 selected>Selecciona una opción</option>
                                            @foreach ($projects as $id => $project)
                                                <option value='{{ $id }}'>{{ $project }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
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
                                        <label for="">Destino: </label>
                                        <input type="text" name="destiny" id="destiny" class = "form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Fecha de cotización</label>
                                        <input type="date" id="quote_date" name="quote_date" class="form-control" placeholder="Fecha de cotización">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secundary" onclick="closeModal('#orderModal')">Cancelar</button>
                    <button type="button" onclick="guardarOrden()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- fin modal --}}
    {{-- inicia modal abrir items --}}
    <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="gridModalLabek">Items</h4>
                    <button type="button" class="close" aria-label="Close" onclick="closeModal('#myModal2')"><span aria-hidden="true">&times;</span></button>
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
                                                <th class="text-center">Cantidad</th>
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
                                        <div class="form-group">
                                            <label for="">Total mercancía</label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">$</div>
                                                </div>
                                                <input type="text" id="totalmerch" name="totalmerch" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class = "col-lg-3">
                                    <div class="form-group">
                                        <label for="">Porcentaje</label>
                                        <div class="input-group mb-3">
                                            <input type="text" id="percent" name="percent" class="form-control" onchange = "calculoPerc()" @if($perm_btn['modify']!=1) disabled @endif>
                                            <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="">Honorarios</label>
                                        <div class="input-group mb-2 mr-sm-2">
                                            <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                            </div>
                                            <input type="text" id="pay" name="pay" class="form-control" disabled>
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
                                            <input type="text" id="broker" name="broker" class="form-control" onchange = "calculoBroker()" @if($perm_btn['modify']!=1) disabled @endif>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "col-lg-4">
                                    <div class="form-group">
                                        <label for="">IVA</label>
                                        <div class="input-group mb-2 mr-sm-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">$</div>
                                            </div>
                                            <input type="text" id="iva" name="iva" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class = "col-lg-4">
                                    <div class="form-group">
                                        <label for="">Total</label>
                                        <div class="input-group mb-2 mr-sm-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">$</div>
                                            </div>
                                            <input type="text" id="paytotal" name="paytotal" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secundary" onclick="closeModal('#myModal2')">Cancelar</button>
                    @if ($perm_btn['addition']==1)
                        <button type="button" id="btnNewItem" onclick="newItem()" class="btn btn-primary">Agregar Item</button>
                    @endif
                    <button type="button" id="btnDescargarItem" onclick="GetPDFQuote()" class="btn btn-primary">Descargar Cotización</button>
                    {{-- <button type="button" onclick="guardarperfil()" class="btn btn-primary">Exportar PDF</button> --}}
                </div>
            </div>
        </div>
    </div>
    {{-- fin modal --}}
    {{-- modal nuevo item --}}
    <div id="myModalNewItem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="gridModalLabek">Registro de Item</h4>
                    <button type="button" class="close" onclick="closeModal('#myModalNewItem')" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Descripción</label>
                                        <input type="text" id="description" name="description" class="form-control" placeholder="Descripción">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Cantidad</label>
                                        <input type="text" id="back_order" name="back_order" class="form-control" placeholder="Cantidad" onchange="calculo()">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
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
                                <div class="col-lg-6">
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
                    <button type="button" class="btn btn-secundary" onclick="closeModal('#myModalNewItem')">Cancelar</button>
                    <button type="button" onclick="guardarItem()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- fin modal| --}}
    {{-- modal editar item --}}
    <div id="myModalEditItem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="gridModalLabek">Registro de Item</h4>
                    <button type="button" class="close" onclick="closeModal('#myModalEditItem')" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Descripción</label>
                                        <input type="text" id="description1" name="description1" class="form-control" placeholder="Descripción">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Cantidad</label>
                                        <input type="text" id="back_order1" name="back_order1" class="form-control" placeholder="Cantidad" onchange="calculo1()">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
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
                                <div class="col-lg-6">
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
                    <button type="button" class="btn btn-secundary" onclick="closeModal('#myModalEditItem')">Cancelar</button>
                    <button type="button" onclick="actualizarItem()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- fin modal| --}}
    {{-- modal orden --}}
    <div id="finishOrderModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="gridModalLabek">Nuevo orden</h4>
                    <button type="button" class="close" onclick="closeModal('#finishOrderModal')" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid bd-example-row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Número de Orden</label>
                                        <input type="text" id="order_number" name="order_number" class="form-control">
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
                    <button type="button" class="btn btn-secundary" onclick="closeModal('#finishOrderModal')">Cancelar</button>
                    <button type="button" onclick="crearOrden()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- fin modal --}}

    @include('processes.status.status')
        {{-- Inicia pantalla de inicio --}}
        <div class="bd-example bd-example-padded-bottom">
            @if($perm_btn['addition']==1)
                <button type="button" class="btn btn-primary" onclick="openNew()">Nuevo</button>
            @endif
        </div>
        <br><br>
        <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
            <table class="table table-striped table-hover text-center" id="tbProf">
                <thead>
                    <th class="text-center">Cotización</th>
                    <th class="text-center">Proyecto</th>
                    <th class="text-center">Cliente</th>
                    <th class="text-center">Opciones</th>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr id="{{$order->id}}">
                            <td>{{$order->quote_number}}</td>
                            <td>{{$order->project}}</td>
                            <td>{{$order->name}}</td>
                            <td>
                                <a href="#|" class="btn btn-primary" onclick="terminarOrden({{$order->id}})" >Crear Orden</a>
                                <a href="#|" class="btn btn-primary" onclick="nuevoItem({{$order->id}},{{$profile}})" >Items</a>
                                @if ($perm_btn['erase']==1 || $perm_btn['modify']==1)
                                        @if ($perm_btn['modify']==1)
                                            <a href="#|" class="btn btn-warning" onclick="editarOrden({{$order->id}})" ><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if ($perm_btn['erase']==1)
                                            <a href="#|" class="btn btn-danger" onclick="eliminarOrden({{$order->id}})"><i class="fa fa-trash"></i></a>
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
    {{-- <script src="{{URL::asset('js/processes/order.js')}}"></script> --}}
    <script src="{{URL::asset('js/processes/quoter.js')}}"></script>
@endpush
