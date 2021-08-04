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

function guardarReembolso()
{
    var agent = $("#selectAgent").val();
    var folio = $("#folio").val();
    var contractor = $("#contractor").val();
    var insurance = $("#selectInsurance").val();
    var entry_date = $("#entry_date").val();
    var policy = $("#policy").val();
    var insured = $("#insured").val();
    var sinister = $("#sinister").val();
    var amount = $("#amount").val();
    var payment_form = $("#selectPayment").val();
    var route = "refunds";
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'agent':agent,
        'folio':folio,
        'contractor':contractor,
        'fk_insurance':insurance,
        'entry_date':entry_date,
        'policy':policy,
        'insured':insured,
        'sinister':sinister,
        'amount':amount,
        'payment_form':payment_form
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
function editarReembolso(id)
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
           $("#folio1").val(result.data.folio);
           $("#contractor1").val(result.data.contractor);
           $("#selectInsurance1").val(result.data.fk_insurance);
           $("#entry_date1").val(result.data.entry_date);
           $("#policy1").val(result.data.policy);
           $("#insured1").val(result.data.insured);
           $("#sinister1").val(result.data.sinister);
           $("#amount1").val(result.data.amount);
           $("#selectPayment1").val(result.data.payment_form);
           $("#myModaledit").modal('show');
        }
    })
}
function cancelarEditar()
{
    $("#myModaledit").modal('hide');
}
function actualizarReembolso()
{
    var agent = $("#selectAgent1").val();
    var folio = $("#folio1").val();
    var contractor = $("#contractor1").val();
    var insurance = $("#selectInsurance1").val();
    var entry_date = $("#entry_date1").val();
    var policy = $("#policy1").val();
    var insured = $("#insured1").val();
    var sinister = $("#sinister1").val();
    var amount = $("#amount1").val();
    var payment_form = $("#selectPayment1").val();
    var route = "refunds/"+idupdate;
    var data = {
        'id':idupdate,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'agent':agent,
        'folio':folio,
        'contractor':contractor,
        'fk_insurance':insurance,
        'entry_date':entry_date,
        'policy':policy,
        'insured':insured,
        'sinister':sinister,
        'amount':amount,
        'payment_form':payment_form
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

function eliminarReembolso(id)
{
    var route = "refunds/"+id;
    var data = {
            'id':id,
            "_token": $("meta[name='csrf-token']").attr("content"),
    };
    alertify.confirm("Eliminar Reembolso","¿Desea borrar el Reembolso?",
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
