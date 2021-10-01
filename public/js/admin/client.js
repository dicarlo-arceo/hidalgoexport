var ruta = window.location;
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + getUrl.pathname;

$(document).ready( function () {
    $('#tbClient').DataTable({
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


// persona física
function guardarCliente()
{
    var name = $("#name").val();
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();

    var birth_date = $("#birth_date").val();
    var rfc = $("#rfc").val();
    var curp =$("#curp").val();

    var gender = $("#gender").val();
    var marital_status = $("#marital_status").val();

    var street = $("#street").val();
    var e_num = $("#e_num").val();
    var i_num = $("#i_num").val();
    var pc = $("#pc").val();

    var suburb = $("#suburb").val();
    var country = $("#country").val();
    var state = $("#state").val();
    var city = $("#city").val();

    var cellphone = $("#cellphone").val();
    var email = $("#email").val();

    var route = "client";

    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'name':name,
        'firstname':firstname,
        'lastname':lastname,
        'birth_date':birth_date,
        'rfc':rfc,
        'curp':curp,
        'gender':gender,
        'marital_status':marital_status,
        'street':street,
        'e_num':e_num,
        'i_num':i_num,
        'pc':pc,
        'suburb':suburb,
        'country':country,
        'state':state,
        'city':city,
        'cellphone':cellphone,
        'email':email,
    };

    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            alertify.success(result.message);
            $("#modalNewClient").modal('hide');
            window.location.reload(true);
        }
    })
}
var idupdate = 0;
function editarCliente(id)
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
            $("#name1").val(result.data.name);
            $("#firstname1").val(result.data.firstname);
            $("#lastname1").val(result.data.lastname);
            $("#birth_date1").val(result.data.birth_date);
            $("#rfc1").val(result.data.rfc);
            $("#curp1").val(result.data.curp);
            $("#gender1").val(result.data.gender);
            $("#marital_status1").val(result.data.marital_status);
            $("#street1").val(result.data.street);
            $("#e_num1").val(result.data.e_num);
            $("#i_num1").val(result.data.i_num);
            $("#pc1").val(result.data.pc);
            $("#suburb1").val(result.data.suburb);
            $("#country1").val(result.data.country);
            $("#state1").val(result.data.state);
            $("#city1").val(result.data.city);
            $("#cellphone1").val(result.data.cellphone);
            $("#email1").val(result.data.email);

            $("#modalEditClient").modal('show');
        }
    })
}

function cancelarEditar()
{
    $("#modalEditClient").modal('hide');

}
function actualizarCliente()
{
    var name = $("#name1").val();
    var firstname = $("#firstname1").val();
    var lastname = $("#lastname1").val();

    var birth_date = $("#birth_date1").val();
    var rfc = $("#rfc1").val();
    var curp =$("#curp1").val();

    var gender = $("#gender1").val();
    var marital_status = $("#marital_status1").val();

    var street = $("#street1").val();
    var e_num = $("#e_num1").val();
    var i_num = $("#i_num1").val();
    var pc = $("#pc1").val();

    var suburb = $("#suburb1").val();
    var country = $("#country1").val();
    var state = $("#state1").val();
    var city = $("#city1").val();

    var cellphone = $("#cellphone1").val();
    var email = $("#email1").val();

    var route = "client/"+idupdate;

    var data = {
        'id':idupdate,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'name':name,
        'firstname':firstname,
        'lastname':lastname,
        'birth_date':birth_date,
        'rfc':rfc,
        'curp':curp,
        'gender':gender,
        'marital_status':marital_status,
        'street':street,
        'e_num':e_num,
        'i_num':i_num,
        'pc':pc,
        'suburb':suburb,
        'country':country,
        'state':state,
        'city':city,
        'cellphone':cellphone,
        'email':email,
    };
    jQuery.ajax({
        url:route,
        type:'put',
        data:data,
        dataType:'json',
        success:function(result)
        {
            alertify.success(result.message);
            $("#modalEditClient").modal('hide');
            window.location.reload(true);
        }
    })
}
function eliminarCliente(id)
{
    var route = "client/"+id;
    var data = {
        'id':id,
        "_token": $("meta[name='csrf-token']").attr("content"),
    };
    alertify.confirm("Eliminar Cliente","¿Desea borrar el Cliente?",
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
var idnuc = 0;
function nuevoNuc(id)
{
    idnuc=id;
    $("#nucModal").modal('show');
}
function cerrarNuc()
{
    $("#nucModal").modal('hide');
}
function guardarNuc()
{
    var onoff = document.getElementById("onoff");
    var checked = onoff.checked;
    var selectCurrency = $("#selectCurrency").val();
    var nuc = $("#nuc").val();
    var reinvest = 2;
    if(checked)
    {
        reinvest = 1;
    }
    var route = baseUrl + '/SaveNuc';
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'nuc':nuc,
        'selectCurrency':selectCurrency,
        'fk_client':idnuc,
        'estatus':reinvest,
    };
    console.log(data);
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

