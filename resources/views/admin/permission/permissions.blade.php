@extends('home')
@section('content')

<div class="panel panel-primary">
    <div class="panel-heading">
        <h1 class="panel-title text-center">Permisos</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-4">
                <label for="idPerfil">Perfil: </label>
                <select name="selectProfile" id="selectProfile" class="form-control" onchange="permprofile()">
                    <option hidden selected>Selecciona una opción</option>
                    @foreach ($profiles as $id => $profile)
                        <option value='{{ $id }}'>{{ $profile }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-8">
                <p style="color:red">Recuerde seleccionar un perfil antes de modificar los permisos</p>
            </div>
        </div>
        {{-- inicia panel de permisos  --}}
{{-- {!! Form::open(['route'=> ['admin.permission.update_store',':USER_ID'],'method'=>'POST', 'id'=>'form-update_store']) !!} --}}

        <div class="panel-group" id="accordion">
            @foreach($padre as $padres )

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$padres->id}}" data-padre="{{$padre = $padres->id}}">{{$padres->section}}</a>
                        </h4>
                    </div>
                    <div id="collapse{{$padres->id}}" class="panel-collapse collapse ">
                        <div class="panel-body">

                            <div class="table-responsive" style="margin: 10px auto; max-width: 1200px">
                                <table class="table table-striped table-hover text-center">
                                    <thead >
                                    <th class="text-center">Modulo</th>
                                    <th class="text-center">Ver</th>
                                    <th class="text-center">Agregar</th>
                                    <th class="text-center">Editar</th>
                                    <th class="text-center">Eliminar</th>
                                    </thead>
                                    <tbody>
                                    @foreach($hijos as $hijo )

                                        @if($hijo->reference == $padres->id)

                                            <tr data-hijo="{{$hijo->id}}" data-reference="{{$hijo->reference}}">
                                                <td class="text-left">
                                                    <strong>
                                                        {{$hijo->section}}
                                                    </strong>
                                                </td>
                                                <!--VALIDAR-->
                                                @if($hijo->hijo==0)
                                                    <td>
                                                        <input type="checkbox"   id="view_{{$hijo->id}}" disabled="disabled" class="view_id" onclick="clickView({{$hijo->id}},{{$hijo->reference}})">
                                                    </td>
                                                    <td>
                                                        @if($hijo->haveAdd==1)
                                                            <input type="checkbox" id="add_{{$hijo->id}}" disabled="disabled" class="add_id" onclick="clickAdd({{$hijo->id}},{{$hijo->reference}})">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($hijo->haveUpdate==1)
                                                            <input type="checkbox" id="update_{{$hijo->id}}" disabled="disabled" class="update_id" onclick="clickUpdate({{$hijo->id}},{{$hijo->reference}})">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($hijo->haveDelete==1)
                                                            <input type="checkbox" id="delete_{{$hijo->id}}" disabled="disabled" class="delete_id" onclick="clickDelete({{$hijo->id}},{{$hijo->reference}})">
                                                        @endif
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                            </tr>
                                            @endif
                                            <!--/VALIDAR-->

                                            @foreach($hijos as $hijo2 )

                                                @if($hijo2->reference == $hijo->id)
                                                    <tr data-hijo="{{$hijo2->id}}" data-reference="{{$hijo2->reference}}">
                                                        <td class="text-left">
                                                                   <span style="padding-left: 20px;">
                                                                       {{$hijo2->section}}
                                                                   </span>
                                                        </td>
                                                        <td >
                                                            <input type="checkbox" id="view_{{$hijo2->id}}" disabled="disabled" class="view_id" onclick="clickView({{$hijo2->id}},{{$hijo->reference}})">
                                                        </td>
                                                        <td>
                                                            @if($hijo2->haveAdd==1)
                                                                <input type="checkbox" id="add_{{$hijo2->id}}" disabled="disabled"  class="add_id" onclick="clickAdd({{$hijo2->id}},{{$hijo->reference}})">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($hijo2->haveUpdate==1)
                                                                <input type="checkbox" id="update_{{$hijo2->id}}" disabled="disabled" class="update_id" onclick="clickUpdate({{$hijo2->id}},{{$hijo->reference}})">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($hijo2->haveDelete==1)
                                                                <input type="checkbox" id="delete_{{$hijo2->id}}" disabled="disabled" class="delete_id" onclick="clickDelete({{$hijo2->id}},{{$hijo->reference}})">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif

                                            @endforeach

                                        @endif

                                    @endforeach

                                    </tbody>
                                </table>

                                {{-- {!! Form::close() !!} --}}

                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>
</div>

@endsection
@push('head')
    <script src="{{URL::asset('js/admin/permission.js')}}"></script>
@endpush
