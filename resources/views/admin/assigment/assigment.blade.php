@extends('home')
@section('content')
    <div class="text-center"><h1>Asignaciones</h1></div>
    <div style="max-width: 1200px; margin: auto;">
        {{-- modal| --}}
        <div id="assigmentModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="gridModalLabek">Asignaciones</h4>
                        <button type="button" class="close" onclick="cerrarmodal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid bd-example-row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">Clientes</label>
                                            <select name="selectAgent" id="selectAgent" class="form-select">
                                                @if ($clients != null)
                                                    <option hidden selected>Selecciona una opción</option>
                                                    @foreach ($clients as $id => $client)
                                                        <option value='{{ $id }}'>{{ $client }}</option>
                                                    @endforeach
                                                @else
                                                    <option selected>Selecciona una opción</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
                                        <table class="table table-strped table-hover text-center" id="tableClients">
                                            <thead>
                                                <th class="text-center">id</th>
                                                <th class="text-center">Cliente</th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secundary" onclick="cerrarmodal()">Cancelar</button>
                        <button type="button" onclick="assignclient()" class="btn btn-primary">Asignar</button>
                    </div>

                </div>
            </div>
        </div>
        {{-- fin modal| --}}
        {{-- Inicia pantalla de inicio --}}
        <div class="tab-content" id="mytabcontent">
            <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
                <table class="table table-striped table-hover text-center" id="tbClient">
                    <thead>
                        <th class="text-center">Nombre del Agente</th>
                        <th class="text-center">Correo</th>
                        @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                            <th class="text-center">Opciones</th>
                        @endif
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr id="{{$user->id}}">
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                                    <td>
                                        @if ($perm_btn['modify']==1)
                                            <a href="#|" class="btn btn-primary" onclick="assignment({{$user->id}})" >Asignar Cliente</a>
                                            {{-- <a href="#|" class="btn btn-warning" onclick="editarCliente({{$client->id}})" >Editar</a> --}}
                                        @endif
                                        @if ($perm_btn['erase']==1)
                                            {{-- <a href="#|" class="btn btn-danger" onclick="eliminarCliente({{$client->id}})">Eliminar</a> --}}
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
    <script src="{{URL::asset('js/admin/assigment.js')}}"></script>
@endpush
