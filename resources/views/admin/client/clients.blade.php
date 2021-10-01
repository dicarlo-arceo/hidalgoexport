@extends('home')
@section('content')
    <div class="text-center"><h1>Catálogo de Clientes</h1></div>
    <div style="max-width: 1200px; margin: auto;">
        @include('admin.client.clientnew')
        @include('admin.client.clientedit')
        {{-- modal| --}}
        <div id="nucModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="gridModalLabek">Nuevo nuc</h4>
                        <button type="button" class="close" onclick="cerrarNuc()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid bd-example-row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Nuc</label>
                                            <input type="text" id="nuc" name="nuc" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Moneda:</label>
                                        <select name="selectCurrency" id="selectCurrency" class="form-select">
                                            <option hidden selected>Selecciona una opción</option>
                                            <option>MXN</option>
                                            <option>USD</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Estatus:</label>
                                            &nbsp;&nbsp;
                                            <input id = "onoff" type="checkbox" checked data-toggle="toggle" data-on = "Reinversión" data-off="Depósito" data-width="120" data-offstyle="secondary">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secundary" onclick="cerrarNuc()">Cancelar</button>
                        <button type="button" onclick="guardarNuc()" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- fin modal| --}}
        {{-- Inicia pantalla de inicio --}}
        <div class="bd-example bd-example-padded-bottom">
            @if ($perm_btn['addition']==1)
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewClient">Nuevo</button>
            @endif
        </div>
        <br><br>
        <div class="tab-content" id="mytabcontent">
            <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
                <table class="table table-striped table-hover text-center" id="tbClient">
                    <thead>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Apellidos</th>
                        <th class="text-center">RFC</th>
                        @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                            <th class="text-center">Opciones</th>
                        @endif
                    </thead>

                    <tbody>
                        @foreach ($clients as $client)
                            <tr id="{{$client->id}}">
                                <td>{{$client->name}}</td>
                                <td>{{$client->firstname}} {{$client->lastname}}</td>
                                <td>{{$client->rfc}}</td>
                                @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                                    <td>
                                        @if ($perm_btn['modify']==1)
                                            <a href="#|" class="btn btn-primary" onclick="nuevoNuc({{$client->id}})" >Nuevo nuc</a>
                                            <a href="#|" class="btn btn-warning" onclick="editarCliente({{$client->id}})" >Editar</a>
                                        @endif
                                        @if ($perm_btn['erase']==1)
                                            <a href="#|" class="btn btn-danger" onclick="eliminarCliente({{$client->id}})">Eliminar</a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('head')
    <script src="{{URL::asset('js/admin/client.js')}}"></script>
@endpush
