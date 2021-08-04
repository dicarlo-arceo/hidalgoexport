@extends('home')
@section('content')
    <div class="text-center"><h1>Iniciales</h1></div>
        {{-- modal| --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="gridModalLabek">Registro de Iniciales</h4>
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
                                    <label for="">Cliente</label>
                                    <input type="text" id="client" name="client" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">RFC</label>
                                    <input type="text" id="rfc" name="rfc" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Fecha de promotoria</label>
                                    <input type="date" id="promoter" name="promoter" class="form-control" placeholder="Fecha de Promotoria">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Fecha de Sistema</label>
                                    <input type="date" id="system" name="system" class="form-control" placeholder="Fecha de Sistema">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Tipo Solicitud:</label>
                                    <select name="selectAppli" id="selectAppli" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        @foreach ($applications as $id => $appli)
                                            <option value='{{ $id }}'>{{ $appli }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Mes ingresado:</label>
                                    <select name="selectMonth" id="selectMonth" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        <option value="Enero">Enero</option>
                                        <option value="Febrero">Febrero</option>
                                        <option value="Marzo">Marzo</option>
                                        <option value="Abril">Abril</option>
                                        <option value="Mayo">Mayo</option>
                                        <option value="Junio">Junio</option>
                                        <option value="Julio">Julio</option>
                                        <option value="Agosto">Agosto</option>
                                        <option value="Septiembre">Septiembre</option>
                                        <option value="Octubre">Octubre</option>
                                        <option value="Noviembre">Noviembre</option>
                                        <option value="Diciembre">Diciembre</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Folio</label>
                                    <input type="text" id="folio" name="folio" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">PNA</label>
                                    <input type="text" id="pna" name="pna" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Forma de Pago:</label>
                                    <select name="selectPaymentform" id="selectPaymentform" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        @foreach ($paymentForms as $id => $payment_form)
                                            <option value='{{ $id }}'>{{ $payment_form }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Moneda:</label>
                                    <select name="selectCurrency" id="selectCurrency" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        @foreach ($currencies as $id => $currency)
                                            <option value='{{ $id }}'>{{ $currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Conducto de cobro:</label>
                                    <select name="selectCharge" id="selectCharge" class="form-control">
                                        <option hidden selected>Selecciona una opción</option>
                                        @foreach ($charges as $id => $charge)
                                            <option value='{{ $id }}'>{{ $charge }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secundary" data-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="guardarInicial()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- fin modal| --}}
    @include('processes.OT.Initials.initialEdit')
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
                {{-- <th class="text-center">Fecha</th> --}}
                <th class="text-center">Agente</th>
                <th class="text-center">Cliente</th>
                <th class="text-center">RFC</th>
                <th class="text-center">Aseguradora</th>
                <th class="text-center">Ramo</th>
                <th class="text-center">Estatus</th>
                @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                    <th class="text-center">Opciones</th>
                @endif
            </thead>

            <tbody>
                @foreach ($initials as $initial)
                    <tr id="{{$initial->id}}">
                        {{-- <td>{{$initial->date}}</td> --}}
                        <td>{{$initial->agent}}</td>
                        <td>{{$initial->client}}</td>
                        <td>{{$initial->rfc}}</td>
                        <td>{{$initial->insurance}}</td>
                        <td>{{$initial->branch}}</td>
                        <td>
                            <button class="btn btn-info" style="background-color: #{{$initial->color}}; border-color: #{{$initial->color}}" onclick="opcionesEstatus({{$initial->id}},{{$initial->statId}})">{{$initial->name}}</button>
                        </td>                            
                        {{-- <td>{{$initial->client}}</td> --}}
                        @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                            <td>
                                @if ($perm_btn['modify']==1)
                                    <a href="#|" class="btn btn-warning" onclick="editarInicial({{$initial->id}})" >Editar</a>
                                @endif
                                @if ($perm_btn['erase']==1)
                                    <a href="#|" class="btn btn-danger" onclick="eliminarInicial({{$initial->id}})">Eliminar</a>
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
    <script src="{{URL::asset('js/processes/initials.js')}}"></script>
@endpush
