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
        'codes':codigos
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
    codigos=[];
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
            // codigos

            // codigoseditar=[];
            var tableEdit = $("#tbody-codigo1");
            tableEdit.empty();
            for( var i=0; i<result.data.agent_codes.length; i++)
            {
                agregarcodigo1(result.data.agent_codes[i].code)
            }
            showimpEdit();
            $("#myModaledit").modal('show');
        }
    })
}

function cancelarUsuario()
{
    // $("#tbody-codigo1").empty();
    codigoseditar=[];
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
        'codigoseditar':codigoseditar
    };
    console.log(codigoseditar);
    jQuery.ajax({
        url:route,
        type:'put',
        data:data,
        dataType:'json',
        success:function(result)
        {
            // $("#tbody-codigo1").empty();
            alertify.success(result.message);
            $("#myModaledit").modal('hide');
            window.location.reload(true);
        }
    })
    codigoseditar=[];
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



// codigos agentes
function showimp()
{
   var get_value = document.getElementById("selectProfile");
   var valor = get_value.value;
//    alert(valor);
    if(valor == "12")
    {
        document.getElementById("etiqueta").hidden = false;
        document.getElementById("etiqueta").style.display = "block";
        document.getElementById("code").hidden = false;
        document.getElementById("code").style.display = "block";
        document.getElementById("agregarcol").hidden = false;
        document.getElementById("agregarcol").style.display = "block";
        document.getElementById("tbcodes").hidden = false;
        document.getElementById("tbcodes").style.display = "block";
        document.getElementById("tbody-codigo").hidden = false;
        document.getElementById("tbody-codigo").style.display = "block";

    }
    else
    {
        document.getElementById("etiqueta").hidden = true;
        document.getElementById("code").hidden = true;
        document.getElementById("agregarcol").hidden = true;
        document.getElementById("tbcodes").hidden = true;
        document.getElementById("tbody-codigo").hidden = true;

    }
}
var array = [];
var codigos=[];
var codigoseditar=[];
function agregarcodigo()
{
    var codigo = $("#code").val();
    var table = $("#tbody-codigo");
    var str_row = '<tr id = "'+parseFloat(array.length+1)+'"><td><input type=text name="codigo[]" value="'+codigo+'"/></td><td><button type="button" class="btn btn-danger" onclick="delete_code(this)"><i class="fa fa-trash mr-2"></i></button></td></tr>';
    table.append(str_row);
    $("#code").val("");
    codigos.push({
        'id':array.length+1,
        'code':codigo
    });
}
function agregarcodigo1(codigo)
{
    if(codigo == undefined)
        codigo = $("#code1").val();
    var table = $("#tbody-codigo1");
    var str_row = '<tr id = "'+parseFloat(array.length+1)+'"><td><input type=text name="codigo[]" value="'+codigo+'"/></td><td><button type="button" class="btn btn-danger" onclick="delete_code_edit(this)"><i class="fa fa-trash mr-2"></i></button></td></tr>';
    table.append(str_row);
    $("#code1").val("");
    codigoseditar.push({
        'id':array.length+1,
        'code':codigo
    });
}
function delete_code(row)
{
    var index = 0;
    var id = $(row).parent().parent().attr('id');
    $(row).parent().parent().remove();
    for(var i = 0; i<codigos.length; ++i)
    {
        if(codigos[i].id == id)
        {
            index=0;
        }
    }
    codigos.splice(index,1);
}
function delete_code_edit(row)
{
    var index = 0;
    var id = $(row).parent().parent().attr('id');
    $(row).parent().parent().remove();
    for(var i = 0; i<codigoseditar.length; ++i)
    {
        if(codigoseditar[i].id == id)
        {
            index=0;
        }
    }
    codigoseditar.splice(index,1);
}
// editar codigos agentes
function showimpEdit()
{
   var get_value = document.getElementById("selectProfile1");
   var valor = get_value.value;
    if(valor == "12")
    {
        document.getElementById("etiqueta1").hidden = false;
        document.getElementById("etiqueta1").style.display = "block";
        document.getElementById("code1").hidden = false;
        document.getElementById("code1").style.display = "block";
        document.getElementById("agregarcol1").hidden = false;
        document.getElementById("agregarcol1").style.display = "block";
        document.getElementById("tbcodes1").hidden = false;
        document.getElementById("tbcodes1").style.display = "block";
        document.getElementById("tbody-codigo1").hidden = false;
        document.getElementById("tbody-codigo1").style.display = "block";

    }
    else
    {
        document.getElementById("etiqueta1").hidden = true;
        document.getElementById("code1").hidden = true;
        document.getElementById("agregarcol1").hidden = true;
        document.getElementById("tbcodes1").hidden = true;
        document.getElementById("tbody-codigo1").hidden = true;

    }
}

