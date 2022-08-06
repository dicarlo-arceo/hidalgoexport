var ruta = window.location;
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + getUrl.pathname;

$(document).ready( function () {
    $('#tbProfProject').DataTable({
        language : {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
} );
$(document).ready( function () {
    $('#tbProfOrders').DataTable({
        language : {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
} );

function guardarproyecto(permisos)
{
    var name = $("#name").val();
    var table = $('#tbProfProject').DataTable();
    var route = "projects";
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'name':name
    };
    jQuery.ajax({
        url:route,
        type:"post",
        data: data,
        dataType: 'json',
        success:function(result)
        {
            alertify.success(result.message);
            // $("#myModal").modal('hide');
            table.clear();
            $("[data-dismiss=modal]").trigger({ type: "click" });
            // for(var x = 0; x < result.data.lenght; x++)
            // {
            //     table.row.add([result.data.name,'<a href="#|" class="btn btn-warning" onclick="editarproyecto('+result.data.id+')" >Editar</a>']).node().id = result.data.id;
            // }
            // table.draw(false);
            result.data.forEach( function(valor, indice, array) {
                table.row.add([valor.name,'<button href="#|" class="btn btn-warning" onclick="editarproyecto('+valor.id+')" >Editar</button>&nbsp<button href="#|" class="btn btn-danger" onclick="eliminarproyecto('+valor.id+')">Eliminar</button>']).node().id = valor.id;
            });
            table.draw(false);
            // window.location.reload(true);
        }
    })

}
var idupdate = 0;
function editarproyecto(id)
{
    idupdate=id;

    var route = baseUrl + '/GetInfo/'+ id;

    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            $("#name1").val(result.data.name);
            $("#myModaledit").modal('show');
        }
    })
}
function cancelareditar()
{
    $("#name1").val("");
    $("#myModaledit").modal('hide');
}
function actualizarproyecto()
{
    var name = $("#name1").val();
    var route = "projects/"+idupdate;
    var data = {
        'id':idupdate,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'name':name
    };
    jQuery.ajax({
        url:route,
        type:'put',
        data:data,
        dataType:'json',
        success:function(result)
        {
            alertify.success(result.message);
            $("#myModaledit").modal('hide');
            window.location.reload(true);
        }
    })
}

function eliminarproyecto(id)
{
    var route = "projects/"+id;
    var data = {
            'id':id,
            "_token": $("meta[name='csrf-token']").attr("content"),
    };
    alertify.confirm("Eliminar Perfil","¿Desea borrar el proyecto?",
        function(){
            jQuery.ajax({
                url:route,
                data: data,
                type:'delete',
                dataType:'json',
                success:function(result)
                {
                    window.location.reload(true);
                }
            })
            alertify.success('Eliminado');
        },
        function(){
            alertify.error('Cancelado');
    });
}
function abrirOrden(id,profile)
{
    idProject = id;
    var table = $('#tbProfOrders').DataTable();
    var route = baseUrl + '/GetInfoOrder/'+ id;

    table.clear();

    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            result.data.forEach( function(valor, indice, array) {
                btnTrash = '<button href="#|" class="btn btn-primary" onclick="verEntregados('+valor.id+','+profile+')" title = "Items Entregados"><i class="fas fa-clipboard-check"></i></button> <button href="#|" class="btn btn-primary" onclick="nuevoItem('+valor.id+','+profile+')" title = "Items Pendientes"><i class="fas fa-clipboard-list"></i></button> <button type="button" class="btn btn-warning"'+'onclick="editarOrden('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="eliminarOrden('+valor.id+')"><i class="fa fa-trash"></i></button>';
                // console.log(valor.project);
                table.row.add([valor.order_number,valor.name,btnTrash]).node().id = valor.id;
            });
            table.draw(false);
        }
    })
    $("#myModalOpenOrders").modal('show');
}
function cancelarAbrirOrden()
{
    $("#myModalOpenOrders").modal('hide');
}
