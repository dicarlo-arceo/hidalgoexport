@extends('home')
@section('content')
    <div class="text-center"><h1>Catálogo de Clientes</h1></div>
    <div style="max-width: 1200px; margin: auto;">
        @include('admin.client.clientnew')
        @include('admin.client.clientedit')
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
