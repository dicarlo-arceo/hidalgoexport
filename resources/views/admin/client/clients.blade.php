@extends('home')
@section('content')
    <div class="text-center"><h1>Catálogo de Clientes</h1></div>
    <div style="max-width: 1200px; margin: auto;">
        @include('admin.client.clientnew')
        @include('admin.client.clientedit')
        @include('admin.client.enterprisenew')
        @include('admin.client.enterpriseedit')
        {{-- Inicia pantalla de inicio --}}
        <div class="bd-example bd-example-padded-bottom">
            @if ($perm_btn['addition']==1)
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewClient">Persona Física</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewEnterprise">Persona Moral</button>
            @endif
        </div>
        <br><br>
        <ul class="nav nav-tabs" id="mytab" role="tablist">
            <li class="nav-item">
                <a class="active nav-link active" id="prueba1-tab" data-toggle="tab" href="#prueba1" role="tab" aria-controls="prueba1"
                 aria-selected="true">Persona Física</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="prueba2" data-toggle="tab" href="#pruebas2" role="tab" aria-controls="pruebas2"
                 aria-selected="false">Persona Moral</a>
            </li>
        </ul>
        <div class="tab-content" id="mytabcontent">

            <div class="tab-pane active " id="prueba1" role="tabpanel" aria-labelledby="prueba1-tab">
                <div class="container-fluid">
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
            <div class="tab-pane " id="pruebas2" role="tabpanel" aria-labelledby="prueba2">
                <div class="container-fluid">
                    <div class="table-responsive" style="margin-bottom: 10px">
                        <table class="table table-striped table-hover text-center" id="tbEnterprise">
                            <thead>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">RFC</th>
                                @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                                    <th class="text-center">Opciones</th>
                                @endif
                            </thead>

                            <tbody>
                                @foreach ($enterprises as $enterprise)
                                    <tr id="{{$enterprise->id}}">
                                        <td>{{$enterprise->business_name}}</td>
                                        <td>{{$enterprise->rfc}}</td>
                                        @if ($perm_btn['modify']==1 || $perm_btn['erase']==1)
                                            <td>
                                                @if ($perm_btn['modify']==1)
                                                    <a href="#|" class="btn btn-warning" onclick="editarEmpresa({{$enterprise->id}})" >Editar</a>
                                                @endif
                                                @if ($perm_btn['erase']==1)
                                                    <a href="#|" class="btn btn-danger" onclick="eliminarEmpresa({{$enterprise->id}})">Eliminar</a>
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

        </div>
    </div>
@endsection
@push('head')
    <script src="{{URL::asset('js/admin/client.js')}}"></script>
@endpush
