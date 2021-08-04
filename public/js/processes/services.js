var ruta = window.location;
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + getUrl.pathname;

$(document).ready( function () {
    $('#tbProf').DataTable({
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

function guardarServicio()
{
    var agent = $("#selectAgent").val();
    var entry_date = $("#entry_date").val();
    var policy = $("#policy").val();
    var response_date = $("#response_date").val();
    var download = $("#selectDownload").val();
    var type = $("#type").val();
    var folio = $("#folio").val();
    var name = $("#name").val();
    var record = $("#selectRecord").val();
    var insurance = $("#selectInsurance").val();
    var branch = $("#selectBranch").val();
    var route = "service";
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'agent':agent,
        'entry_date':entry_date,
        'policy':policy,
        'response_date':response_date,
        'download':download,
        'type':type,
        'folio':folio,
        'name':name,
        'record':record,
        'insurance':insurance,
        'branch':branch
    };
    jQuery.ajax({
        url:route,
        type:"post",
        data: data,
        dataType: 'json',
        success:function(result)
        {
            alertify.success(result.message);
            $("#myModal").modal('hide');
            window.location.reload(true);
        }
    })
}
var idupdate = 0;
function editarServicio(id)
{
    idupdate=id;

    var route = baseUrl + '/GetInfo/'+ id;

    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
           $("#selectAgent1").val(result.data.fk_agent);
           $("#entry_date1").val(result.data.entry_date);
           $("#policy1").val(result.data.policy);
           $("#response_date1").val(result.data.response_date);
           $("#selectDownload1").val(result.data.download);
           $("#type1").val(result.data.type);
           $("#folio1").val(result.data.folio);
           $("#name1").val(result.data.name);
           $("#selectRecord1").val(result.data.record);
           $("#selectInsurance1").val(result.data.fk_insurance);
           $("#selectBranch1").val(result.data.fk_branch);
           $("#myModaledit").modal('show');
        }
    })
}
function cancelarEditar()
{
    $("#myModaledit").modal('hide');
}
function actualizarServicio()
{
    var agent = $("#selectAgent1").val();
    var entry_date = $("#entry_date1").val();
    var policy = $("#policy1").val();
    var response_date = $("#response_date1").val();
    var download = $("#selectDownload1").val();
    var type = $("#type1").val();
    var folio = $("#folio1").val();
    var name = $("#name1").val();
    var record = $("#selectRecord1").val();
    var insurance = $("#selectInsurance1").val();
    var branch = $("#selectBranch1").val();
    var route = "service/"+idupdate;
    var data = {
        'id':idupdate,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'agent':agent,
        'entry_date':entry_date,
        'policy':policy,
        'response_date':response_date,
        'download':download,
        'type':type,
        'folio':folio,
        'name':name,
        'record':record,
        'insurance':insurance,
        'branch':branch
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

function eliminarServicio(id)
{
    var route = "service/"+id;
    var data = {
            'id':id,
            "_token": $("meta[name='csrf-token']").attr("content"),
    };
    alertify.confirm("Eliminar Servicio","¿Desea borrar el Servicio?",
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
var id_service = 0;

function opcionesEstatus(serviceId,statusId)
{
    id_service=serviceId;
    $("#selectStatus").val(statusId);
    $("#myEstatusModal").modal('show');
}

function actualizarEstatus()
{
    var status = $("#selectStatus").val();
    var route = baseUrl+"/updateStatus";
    console.log(route);
    var data = {
        'id':id_service,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'status':status
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            alertify.success(result.message);
            $("#myEstatusModal").modal('hide');
            window.location.reload(true);
        }
    })
}
function cerrarmodal()
{
    $("#myEstatusModal").modal('hide');
    $("#comentary").val("");

}
