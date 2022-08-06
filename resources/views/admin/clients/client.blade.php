@extends('home')
@section('content')
    <div class="text-center"><h1>Catálogo de Clientes</h1></div>
    <div style="max-width: 1200px; margin: auto;">
        {{-- modal nuevo --}}
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="gridModalLabek">Registro de Clientes</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid bd-example-row">
                            <div class="col-lg-12">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="text" id="email" name="email" class="form-control" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Contraseña</label>
                                            <input type="text" id="password" name="password" class="form-control" placeholder="Contraseña">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">Nombre</label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Nombre">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">Apellido paterno</label>
                                            <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Apellido">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">Apellido materno</label>
                                            <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Apellido">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Celular</label>
                                            <input type="text" id="cellphone" name="cellphone" class="form-control" placeholder="Celular">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Empresa:</label>
                                                <select name="selectEnterprise" id="selectEnterprise" class="form-select">
                                                    <option hidden value = '{{0}}' selected>Selecciona una opción</option>
                                                    @foreach ($enterprises as $id => $enterprise)
                                                        <option value='{{ $id }}'>{{ $enterprise }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                        <button type="button" onclick="guardarCliente()" class="btn btn-primary">Guardar</button>
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
                        <h4 class="modal-title" id="gridModalLabek">Nuevo orden</h4>
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
                                            <option hidden value='0' selected>Selecciona una opción</option>
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
                        <button type="button" onclick="guardarOrden()" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- fin modal --}}
        @include('admin.clients.clientsedit')
        {{-- Inicia pantalla de inicio --}}
        <div class="bd-example bd-example-padded-bottom">
            @if($perm_btn['addition']==1)
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Nuevo</button>
            @endif
        </div>
        <br><br>
          <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
            <table class="table table-striped table-hover text-center" id="tbUsers">
                <thead>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Apellido</th>
                    @if ($perm_btn['erase']==1 || $perm_btn['modify']==1)
                        <th class="text-center">Opciones</th>
                    @endif
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr id="{{$user->id}}">
                            <td>{{$user->name}}</td>
                            <td>{{$user->firstname}}</td>
                            @if ($perm_btn['erase']==1 || $perm_btn['modify']==1)
                                <td>
                                    @if ($perm_btn['modify']==1)
                                        <a href="#|" class="btn btn-primary" onclick="nuevaOrden({{$user->id}})" >Nueva Orden</a>
                                        <a href="#|" class="btn btn-warning" onclick="editarCliente({{$user->id}})" >Editar</a>
                                    @endif
                                    @if ($perm_btn['erase']==1)
                                        <a href="#|" class="btn btn-danger" onclick="eliminarCliente({{$user->id}})">Eliminar</a>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('head')
    <script src="{{URL::asset('js/admin/clients.js')}}"></script>
@endpush



