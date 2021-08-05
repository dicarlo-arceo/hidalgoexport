var ruta = window.location;
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + getUrl.pathname;

$(document).ready( function () {
    $('#tbUsers').DataTable({
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

function guardarUsuario()
{
    var email = $("#email").val();
    var password = $("#password").val();

    var name = $("#name").val();
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();

    var cellphone = $("#cellphone").val();
    var fk_profile = $("#selectProfile").val();
    var route = "user";
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'email':email,
        'password':password,
        'name':name,
        'firstname':firstname,
        'lastname':lastname,
        'cellphone':cellphone,
        'fk_profile':fk_profile,
    };

    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            alertify.success(result.message);
            $("#myModal").modal('hide');
            window.location.reload(true);
        }
    })
}
var idupdate = 0;
function editarUsuario(id)
{
    idupdate=id;

    var route = baseUrl + '/GetInfo/'+id;
    // alert(route);
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            $("#email1").val(result.data.email);
            $("#password1").val(result.data.password);
            $("#name1").val(result.data.name);
            $("#firstname1").val(result.data.firstname);
            $("#lastname1").val(result.data.lastname);
            $("#cellphone1").val(result.data.cellphone);
            $("#selectProfile1").val(result.data.fk_profile);
            $("#myModaledit").modal('show');
        }
    })
}

function cancelarUsuario()
{
    $("#myModaledit").modal('hide');

}
function actualizarUsuario()
{
    var email = $("#email1").val();
    var password = $("#password1").val();

    var name = $("#name1").val();
    var firstname = $("#firstname1").val();
    var lastname = $("#lastname1").val();

    var cellphone = $("#cellphone1").val();
    var fk_profile = $("#selectProfile1").val();
    var route = "user/"+idupdate;
    var data = {
        'id':idupdate,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'email':email,
        'password':password,
        'name':name,
        'firstname':firstname,
        'lastname':lastname,
        'cellphone':cellphone,
        'fk_profile':fk_profile,
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
function eliminarUsuario(id)
{
    var route = "user/"+id;
    var data = {
        'id':id,
        "_token": $("meta[name='csrf-token']").attr("content"),
    };
    alertify.confirm("Eliminar Usuario","¿Desea borrar el Usuario?",
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
