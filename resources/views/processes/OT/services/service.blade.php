@extends('home')
@section('content')
    <div class="text-center"><h1>Servicios</h1></div>
        {{-- modal| --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="gridModalLabek">Registro de Servicios</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid bd-example-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Agente:</label>
                                    <select name="selectAgent" id="selectAgent" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        @foreach ($agents as $id => $agent)
                                            <option value='{{ $id }}'>{{ $agent }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Fecha de Ingreso</label>
                                    <input type="date" id="entry_date" name="entry_date" class="form-control" placeholder="Fecha de Sistema">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Póliza</label>
                                    <input type="text" id="policy" name="policy" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tipo de Servicio</label>
                                    <input type="text" id="type" name="type" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Folio</label>
                                    <input type="text" id="folio" name="folio" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nombre del Contratante</label>
                                    <input type="text" id="name" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Expediente:</label>
                                    <select name="selectRecord" id="selectRecord" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        <option value="Fisico">Fisico</option>
                                        <option value="Digital">Digital</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Aseguradora:</label>
                                    <select name="selectInsurance" id="selectInsurance" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        @foreach ($insurances as $id => $insurance)
                                            <option value='{{ $id }}'>{{ $insurance }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ramo:</label>
                                    <select name="selectBranch" id="selectBranch" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        @foreach ($branches as $id => $branch)
                                            <option value='{{ $id }}'>{{ $branch }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Fecha de Respuesta</label>
                                    <input type="date" id="response_date" name="response_date" class="form-control" placeholder="Fecha de Promotoria">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Descargado</label>
                                    <select name="selectDownload" id="selectDownload" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="guardarServicio()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- fin modal| --}}
    @include('processes.OT.services.serviceEdit')
    @include('processes.OT.status.status')
    {{-- Inicia pantalla de inicio --}}
    <div class="bd-example bd-example-padded-bottom">
        @if ($perm_btn['addition']==1)
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Nuevo</button>
        @endif
    </div>
    <br><br>
        <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
        <table class="table table-striped table-hover text-center" id="tbProf">
            <thead>
                <th class="text-center">Agente</th>
                <th class="text-center">Cliente</th>
                <th class="text-center">Folio</th>
                <th class="text-center">Tipo de Servicio</th>
                <th class="text-center">Aseguradora</th>
                <th class="text-center">Ramo</th>
                <th class="text-center">Estatus</th>
                @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                    <th class="text-center">Opciones</th>
                @endif
            </thead>

            <tbody>
                @foreach ($services as $service)
                    <tr id="{{$service->id}}">
                        <td>{{$service->agent}}</td>
                        <td>{{$service->name}}</td>
                        <td>{{$service->folio}}</td>
                        <td>{{$service->type}}</td>
                        <td>{{$service->insurance}}</td>
                        <td>{{$service->branch}}</td>
                        <td>
                            <button class="btn btn-info" style="background-color: #{{$service->color}}; border-color: #{{$service->color}}" onclick="opcionesEstatus({{$service->id}},{{$service->statId}})">{{$service->statName}}</button>
                        </td>
                        {{-- <td>{{$initial->client}}</td> --}}
                        @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                            <td>
                                @if ($perm_btn['modify']==1)
                                    <a href="#|" class="btn btn-warning" onclick="editarServicio({{$service->id}})" >Editar</a>
                                @endif
                                @if ($perm_btn['erase']==1)
                                    <a href="#|" class="btn btn-danger" onclick="eliminarServicio({{$service->id}})">Eliminar</a>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('head')
    <script src="{{URL::asset('js/processes/services.js')}}"></script>
@endpush
