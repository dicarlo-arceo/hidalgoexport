@extends('home')
{{-- @section('title','Perfiles') --}}
@section('content')
    <div class="text-center"><h1>Fondo Mensual</h1></div>
    <div style="max-width: 1200px; margin: auto;">
        {{-- inicia modal --}}
        <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="gridModalLabek">Movimientos</h4>
                        <button type="button" class="close" aria-label="Close" onclick="cancelarMovimiento()"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid bd-example-row">
                            <div class="col-lg-12">
                                {{-- <div class="row align-items-center"> --}}
                                <div class="row align-items-end">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="">Monto</label>
                                            <input type="text" id="amount" name="amount" placeholder="Ingresa el monto" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="">Moneda:</label>
                                            <select name="selectCurrency" id="selectCurrency" class="form-select">
                                                <option hidden selected>Selecciona una opción</option>
                                                <option>MXN</option>
                                                <option>USD</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="">Movimiento:</label>
                                            <select name="selectCurrency" id="selectCurrency" class="form-select">
                                                <option hidden selected>Selecciona una opción</option>
                                                <option>Apertura</option>
                                                <option>Reinversion</option>
                                                <option>Abono</option>
                                                <option>Retiro parcial</option>
                                                <option>Retiro total</option>
                                                <option>Ajuste</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="d-grid gap-2 col-12 mx-auto">
                                                <button type="button" class="btn btn-primary">Aplicar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
                                            <table class="table table-striped table-hover text-center" id="tbProf1">
                                                <thead>
                                                    <th class="text-center">Fecha</th>
                                                    <th class="text-center">Moneda</th>
                                                    <th class="text-center">Monto</th>
                                                    <th class="text-center">Tipo</th>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secundary" onclick="cancelarMovimiento()">Cancelar</button>
                        <button type="button" onclick="guardarperfil()" class="btn btn-primary">Exportar Excel</button>
                        <button type="button" onclick="guardarperfil()" class="btn btn-primary">Exportar PDF</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- termina modal --}}
        {{-- Inicia pantalla de inicio --}}
        <br><br>
        <div class="table-responsive" style="margin-bottom: 10px; max-width: 1200px; margin: auto;">
            <table class="table table-striped table-hover text-center" id="tbProf">
                <thead>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">NUC</th>
                    @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                        <th class="text-center">Opciones</th>
                    @endif
                </thead>

                <tbody>
                    @foreach ($nucs as $nuc)
                        <tr id="{{$nuc->id}}">
                            <td>{{$nuc->name}}</td>
                            <td>{{$nuc->nuc}}</td>
                            @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                                <td>
                                    @if ($perm_btn['modify']==1)
                                        <a href="#|" class="btn btn-primary" onclick="nuevoMovimiento({{$nuc->id}})" >Movimientos</a>
                                        {{-- <a href="#|" class="btn btn-primary" data-toggle="modal" data-target="#myModal2" >Movimientos</a> --}}
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
    <script src="{{URL::asset('js/funds/monthfund.js')}}"></script>
@endpush
