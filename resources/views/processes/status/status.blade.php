<div id="myEstatusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        {{-- <form action="orders/updateStatus" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="text" id="id" name="id" hidden> --}}

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="gridModalLabek">Estatus:</h4>
                <button type="button" onclick="cerrarEstatus()" class="close" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Estatus:</label>
                                    <select name="selectStatus" id="selectStatus" class="form-select" @if($perm_btn['modify']!=1) disabled @endif>
                                        <option hidden selected value="">Selecciona una opción</option>
                                        @foreach ($cmbStatus as $id => $status)
                                            <option value='{{ $id }}'>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label for="">Fecha aproximada de llegada: </label>
                                    <input type="date" name="date" id="date" class = "form-control", rows = "3" @if($perm_btn['modify']!=1) disabled @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label for="">Comentario: </label>
                                    <textarea name="commentary" id="commentary" class = "form-control", rows = "3" @if($perm_btn['modify']!=1) disabled @endif></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row" id = "fileInput" style = "display: none;">
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label for="">Imagen: </label>
                                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" @if($perm_btn['modify']!=1) disabled @endif/>
                                </div>
                            </div>
                        </div>
                        <div id = "imgShow" style = "display: none;">
                            <div class="row">
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <a id = "dwnldImg" title="ImageName">
                                            <img id = "statusImg" class="img-thumbnail"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        @if($perm_btn['modify']==1)
                                            <button type="button" onclick="deleteFile()" class="btn btn-danger">Eliminar Imagen</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarEstatus()" class="btn btn-secundary">Cancelar</button>
                @if($perm_btn['modify']==1)
                    {{-- <button type="submit" class="btn btn-primary">Guardar</button> --}}
                    <button type="button" onclick="actualizarEstatus()" class="btn btn-primary">Guardar</button>
                @endif
            </div>
        {{-- </form> --}}
        </div>
    </div>
</div>
