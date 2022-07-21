@extends('home')
{{-- @section('title','Órdenes') --}}
@section('content')
    <div class="text-center"><h1>Órdenes</h1></div>
    <div style="max-width: 1200px; margin: auto;">

    @include('processes.order.items')
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
