@extends('home')
{{-- @section('title','Órdenes') --}}
@section('content')
    <div class="text-center" id="ordTitle"><h1>{{$title}}</h1></div>
    <div style="max-width: 100%; margin: auto;">

    @include('processes.order.items')
    @include('processes.status.status')
        {{-- Inicia pantalla de inicio --}}
        <div class="col-lg-12">
            <div class="row">
                @if ($perm_btn['modify']==1)
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" id="btnOrderChangeAll" class="btn btn-primary" onclick="HojaCobroTodos()">Hoja de Cobro</button>
                            <button type="button" id="btnItemsAll" class="btn btn-primary" onclick="HojaItemsTodos()">Items en pdf</button>
                            <button type="button" id="btnCloseOrd" class="btn btn-primary" onclick="CloseOrders()" @if ($flagClosed == 1) hidden @endif>Marcar como Cerrada</button>
                            <button type="button" id="btnOpenOrd" class="btn btn-primary" onclick="OpenOrders()" @if ($flagClosed == 0) hidden @endif>Marcar como Abierta</button>
                            <button type="button" id="btnAssignRecp" class="btn btn-primary" onclick="AsignarRecibo()" >Asignar Recibo</button>
                            <button type="button" id="btnViewItms" class="btn btn-primary" onclick="VerItems()" >Ver Items</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="table-responsive" style="margin-bottom: 10px; max-width: 100%; margin: auto;">
            <table class="table table-striped table-hover text-center" id="tbProf">
                <thead>
                    <th class="text-center"></th>
                    <th class="text-center"># de orden</th>
                    <th class="text-center">Proyecto</th>
                    <th class="text-center">Cliente</th>
                    <th class="text-center">Opciones</th>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr id="{{$order->id}}">
                            <td>{{$order->id}}</td>
                            <td>{{$order->order_number}}</td>
                            <td>{{$order->project}}</td>
                            <td>{{$order->name}}</td>
                            <td>
                                <a href="#|" class="btn btn-primary" onclick="verEntregados({{$order->id}},{{$profile}})" >Items Entregados</a>
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
