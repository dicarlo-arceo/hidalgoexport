var rutaAsignacion = window.location;
var getUrlAsignacion = window.location;
var baseUrlAsignacion = getUrlAsignacion .protocol + "//" + getUrlAsignacion.host + getUrlAsignacion.pathname;
$(document).ready( function () {
    $('#tableClients').DataTable({
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
var idAgent = 0;
var button = "";

function assignment(id)
{
    idAgent=id;
    var route = baseUrlAsignacion + '/Viewclients/'+id;
    console.log(route);
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result){
            
            var table = $("#tableClients").DataTable();
            table.clear();
            result.data.forEach(function(valor, indice, array){
                button = '<button type="button" class="btn btn-danger"'+
                'onclick="delete_code_edit(this)"><i class="fa fa-trash mr-2"></i></button>';
                table.row.add([valor.id, valor.name, button]).node().id=valor.id;
            })
            table.draw(false);
        }
    })
    $("#assigmentModal").modal('show');
}
function cerrarmodal()
{
    $("#assigmentModal").modal('hide');
}

function assignclient()
{
    var client = $("#selectAgent").val();
    // alert(client);
    var route = baseUrlAsignacion+'/updateClient';
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        "id":idAgent,
        "client":client
    }
    jQuery.ajax({
        url:route,
        data: data,
        type:'post',
        dataType:'json',
        success:function(result){
            alertify.success(result.message);
            $("#assigmentModal").modal('hide');
            window.location.reload(true);
        }
    })
}