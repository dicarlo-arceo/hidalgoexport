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
$(document).ready( function () {
    $('#tbProfReceipts').DataTable({
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

function guardarCliente()
{
    var email = $("#email").val();
    var password = $("#password").val();

    var name = $("#name").val();
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();

    var cellphone = $("#cellphone").val();
    var route = "client";

    var fk_enterprise = $("#selectEnterprise").val();

    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'email':email,
        'password':password,
        'name':name,
        'firstname':firstname,
        'lastname':lastname,
        'cellphone':cellphone,
        'fk_profile':61,
        'fk_enterprise':fk_enterprise,
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
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
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
            $("#email1").val(result.data.email);
            $("#password1").val(result.data.password);
            $("#name1").val(result.data.name);
            $("#firstname1").val(result.data.firstname);
            $("#lastname1").val(result.data.lastname);
            $("#cellphone1").val(result.data.cellphone);
            $("#selectEnterprise1").val(result.data.fk_enterprise);
            $("#myModaledit").modal('show');

        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}

function cancelarCliente()
{
    $("#myModaledit").modal('hide');

}
function actualizarCliente()
{
    var email = $("#email1").val();
    var password = $("#password1").val();

    var name = $("#name1").val();
    var firstname = $("#firstname1").val();
    var lastname = $("#lastname1").val();

    var cellphone = $("#cellphone1").val();
    var fk_enterprise = $("#selectEnterprise1").val();

    var route = "client/"+idupdate;
    var data = {
        'id':idupdate,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'email':email,
        'password':password,
        'name':name,
        'firstname':firstname,
        'lastname':lastname,
        'cellphone':cellphone,
        'fk_enterprise':fk_enterprise,
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
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
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
                },
                error:function(result,error,errorTrown)
                {
                    alertify.error(errorTrown);
                }
            })
            alertify.success('Eliminado');
        },
        function(){
            alertify.error('Cancelado');
    });
}
idOrder = 0;
function nuevaOrden(id)
{
    idOrder=id;
    $("#orderModal").modal('show');
}
function cerrarOrden()
{
    $("#orderModal").modal('hide');
}
function guardarOrden()
{
    var order_number = $("#order").val();
    var fk_project = $("#selectProject").val();
    var address = $("#address").val();
    var designer = $("#designer").val();

    var route = baseUrl + '/SaveOrder';
    // alert(route);
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'order_number':order_number,
        'fk_project':fk_project,
        'fk_user':idOrder,
        'address':address,
        'designer':designer,
    };
    // console.log(data);
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
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
idReceipt = 0; // id del cliente
idRecp = 0; // id de asignacion
imgName = "";
function verRecibos(id)
{
    idReceipt = id;
    var route = baseUrl + '/GetInfoReceipts/'+id;
    var table = $('#tbProfReceipts').DataTable();
    // alert(route);
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            table.clear();
            result.data.forEach( function(valor, indice, array) {
                btnTrash = '<button type="button" class="btn btn-primary"'+'onclick="ViewReceipt('+valor.id+')">Ver</button> <button type="button" class="btn btn-danger"'+'onclick="deleteReceipt('+valor.id+')"><i class="fa fa-trash"></i></button>';
                table.row.add([valor.orders,valor.tr,btnTrash]).node().id = valor.id;
            });
            table.draw(false);
            $("#myModalOpenReceipts").modal('show');
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function cancelarAbrirRecibos()
{
    $("#myModalOpenReceipts").modal('hide');
}
function ViewReceipt(id)
{
    idRecp = id;
    var imgShow = document.getElementById("imgShow");
    var fileInput = document.getElementById("fileInput");
    var route = baseUrl+'/GetImg/'+id;
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result){
            if(result.data.receipt != null)
            {
                fileInput.style.display = "none";
                imgShow.style.display = "";
                $("#statusImg").attr("src","/img/receipts/"+result.data.receipt);
                $("#dwnldImg").attr("href","/img/receipts/"+result.data.receipt);
                $("#dwnldImg").attr("download",result.data.receipt);
                imgName = result.data.receipt;
            }
            else
            {
                fileInput.style.display = "";
                imgShow.style.display = "none";
            }
            $("#myModalViewReceipts").modal('show');
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function cancelarVerRecibos()
{
    $("#myModalViewReceipts").modal('hide');
}
function deleteReceipt(id)
{
    var route = baseUrl + '/DeleteReceipt';
    var data = {
        'id':id,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    alertify.confirm("Eliminar Recibo","¿Desea borrar el Recibo?",
        function(){
            jQuery.ajax({
                url:route,
                data: data,
                type:'post',
                dataType:'json',
                success:function(result)
                {
                    alertify.success(result.message);
                    $("#myModalOpenReceipts").modal('hide');
                },
                error:function(result,error,errorTrown)
                {
                    alertify.error(errorTrown);
                }
            })
        },
        function(){
            alertify.error('Cancelado');
    });
}
function deleteFile()
{
    var fileInput = document.getElementById("fileInput");
    var imgShow = document.getElementById("imgShow");
    var route = baseUrl + '/deleteFile';
    var data = {
        'id':idRecp,
        'imgName':imgName,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            fileInput.style.display = "";
            imgShow.style.display = "none";
            alertify.success(result.message);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function saveFile()
{
    var formData = new FormData();
    var files = $('input[type=file]');
    for (var i = 0; i < files.length; i++) {
        if (files[i].value == "" || files[i].value == null)
        {
        }
        else
        {
            formData.append(files[i].name, files[i].files[0]);
        }
    }
    var formSerializeArray = $("#Form").serializeArray();
    for (var i = 0; i < formSerializeArray.length; i++) {
        formData.append(formSerializeArray[i].name, formSerializeArray[i].value)
    }

    formData.append('_token', $("meta[name='csrf-token']").attr("content"));
    formData.append('id', idRecp);

    var route = baseUrl+"/saveFile";

    jQuery.ajax({
        url:route,
        type:'post',
        data:formData,
        contentType: false,
        processData: false,
        cache: false,
        success:function(result)
        {
            alertify.success(result.message);
            $("#myModalViewReceipts").modal('hide');

        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
