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
$(document).ready( function () {
    $('#tbProf1').DataTable({
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
        },
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1,

    });
} );
var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  });
function formatToCurrency(amount){
    return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

idOrder = 0;
profileOrder = 0;
cellar = 0;

function refreshTable(result)
{
    var table = $('#tbProf1').DataTable();
    table.clear();
    cellar = 0;
    if(profileOrder != 61)
    {
        result.data.forEach( function(valor, indice, array) {
            btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
            if(valor.tr == 0) valTR = "-"; else valTR = valor.tr;
            btnTR = '<button class="btn btn-info" style="background-color: #FFFFFF !important; border-color: #000000 !important; color: #000000 !important;" onclick="editTR('+ valor.id +','+ valor.tr +')">'+ valTR +'</button>'
            btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
            table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnTR,btnStatus,
                formatter.format(valor.net_price),formatter.format(valor.total_price),btnTrash]).node().id = valor.id;
            cellar += parseFloat(valor.total_price);
            console.log(cellar,valor.total_price);
        });
    }
    else
    {
        result.data.forEach( function(valor, indice, array) {
            btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
            table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,valor.tr,btnStatus,
                formatter.format(valor.net_price),formatter.format(valor.total_price)]).node().id = valor.id;
            cellar += valor.total_price;
        });
    }
    $("#dlls").val(result.data[0].exc_rate);
    $("#percent").val(result.data[0].percentage);
    $("#exp").val(result.data[0].expenses);
    $("#onoffCurrency").prop('disabled', false);
    if(result.data[0].currency == 1)
        $("#onoffCurrency").bootstrapToggle('on');
    else
        $("#onoffCurrency").bootstrapToggle('off');
    if(profileOrder == 61)
        $("#onoffCurrency").prop('disabled', true);
    calculoTotales();
    table.draw(false);
}

function nuevoItem(id,profile)
{
    idOrder = id;
    profileOrder = profile
    var table = $('#tbProf1').DataTable();
    var route = baseUrl + '/GetInfo/'+ id;
    var btnTrash;

    $("#amount").val("");
    table.clear();

    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            refreshTable(result);
        }
    })
    $("#myModal2").modal('show');
}

function cancelarItem()
{
    $("#myModal2").modal('hide');
}
function newItem()
{
    $("#myModal").modal('show');
}
function cancelarNewItem()
{
    $("#myModal").modal('hide');
}
idTR = 0;
function editTR(id, tr)
{
    idTR = id;
    $("#tr").val(tr);
    $("#myModalTR").modal('show');
}
function cancelarTR()
{
    $("#myModalTR").modal('hide');
}
function guardarTR()
{
    var tr = $("#tr").val();
    var route = baseUrl + '/updateTR';
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'tr':tr,
        'id':idTR,
        'fk_order':idOrder
    };
    jQuery.ajax({
        url:route,
        type:"post",
        data: data,
        dataType: 'json',
        success:function(result)
        {
            refreshTable(result);
            $("#myModalTR").modal('hide');
            alertify.success(result.message);
        }
    })
}
function guardarItem()
{
    var store = $("#store").val();
    var item_number = $("#item_number").val();
    var description = $("#description").val();
    var back_order = $("#back_order").val();
    var existence = $("#existence").val();
    var table = $('#tbProf1').DataTable();
    var net_price = parseFloat($("#net_price").val());
    var total_price = Number($("#total_price").val().replace(/[^0-9.-]+/g,""));

    var route = "orders";

    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'fk_order':idOrder,
        'store':store,
        'item_number':item_number,
        'description':description,
        'back_order':back_order,
        'existence':existence,
        'net_price':net_price,
        'total_price':total_price,
    };

    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            refreshTable(result);
            $("#myModal").modal('hide');
            alertify.success(result.message);
        }
    })
}
function calculo(){
    var existence = $("#existence").val();
    var net_price = $("#net_price").val();

    if(existence != ""){
        existence = parseFloat(existence);
    }
    else{
        existence = 0;
    }
    if(net_price != ""){
        net_price = parseFloat(net_price);
    }
    else{
        net_price = 0;
    }

    var temp = existence * net_price;

    $("#total_price").val(formatToCurrency(temp));
}

idupdate = 0;

function editarOrden(id)
{
    idupdate=id;

    var route = baseUrl + '/GetInfoOrder/'+id;
    // alert(route);
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            $("#order").val(result.data.order_number);
            $("#selectProject").val(result.data.fk_project);
            $("#address").val(result.data.address);
            $("#orderModal").modal('show');

        }
    })
}
function cerrarOrden()
{
    $("#orderModal").modal('hide');
}
function actualizarOrden()
{
    var order_number = $("#order").val();
    var fk_project = $("#selectProject").val();
    var address = $("#address").val();

    var route = "orders/"+idupdate;
    var data = {
        'id':idupdate,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'order_number':order_number,
        'fk_project':fk_project,
        'address':address,
    };
    jQuery.ajax({
        url:route,
        type:'put',
        data:data,
        dataType:'json',
        success:function(result)
        {
            alertify.success(result.message);
            $("#orderModal").modal('hide');
            window.location.reload(true);
        }
    })
}
function eliminarOrden(id)
{
    var route = "orders/"+id;
    var data = {
        'id':id,
        "_token": $("meta[name='csrf-token']").attr("content"),
    };
    alertify.confirm("Eliminar Orden","¿Desea borrar la orden?",
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
function deleteItem(id)
{
    var table = $('#tbProf1').DataTable();
    var route = baseUrl + '/DeleteItem/' + id + "/" + idOrder;
    var data = {
        'id':id,
        "_token": $("meta[name='csrf-token']").attr("content"),
    };
    alertify.confirm("Eliminar Item","¿Desea borrar el Item?",
        function(){
            jQuery.ajax({
                url:route,
                data: data,
                type:'delete',
                dataType:'json',
                success:function(result)
                {
                    refreshTable(result);
                    alertify.success(result.message);
                    $("#myModal").modal('hide');
                }
            })
        },
        function(){
            alertify.error('Cancelado');
    });
}
function calculoDlls()
{
    if($("#dlls").val() == "")
        $("#dlls").val(0);

    var exc_rate = parseFloat($("#dlls").val());

    var route = baseUrl+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'exc_rate':exc_rate,
        'flag':1,
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
        }
    })

    calculoTotales();
}
function calculoPerc()
{
    if($("#percent").val() == "")
        $("#percent").val(0);

    var percent = parseFloat($("#percent").val());

    var route = baseUrl+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'percentage':percent,
        'flag':2,
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
        }
    })

    calculoTotales();
}
function calculoExp()
{
    if($("#exp").val() == "")
        $("#exp").val(0);

    var expenses = parseFloat($("#exp").val());

    var route = baseUrl+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'expenses':expenses,
        'flag':3,
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
        }
    })

    calculoTotales();
}
function calculoCurrency()
{
    var curr = $("#onoffCurrency").prop('checked');
    var currency = 0;
    if(curr)
        currency = 1;
    else
        currency = 2;

    var route = baseUrl+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'currency':currency,
        'flag':4,
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
        }
    })

    calculoTotales();
}
function calculoTotales()
{
    mxn_total = 0;
    var curr = $("#onoffCurrency").prop('checked');

    comition = cellar * parseFloat($("#percent").val())/100;

    if(curr)
        comition += parseFloat($("#exp").val());
    else
        mxn_total += parseFloat($("#exp").val());

    mxn_total += comition * parseFloat($("#dlls").val());
    iva = mxn_total * 0.16;
    mxn_invoice = mxn_total + iva;

    $("#cellar").val(formatter.format(cellar));
    $("#comition").val(formatter.format(comition));
    $("#net_mxn").val(formatter.format(mxn_total));
    $("#iva").val(formatter.format(iva));
    $("#total_invoice").val(formatter.format(mxn_invoice));
}
imgName = "";
function opcionesEstatus(id,statusId)
{
    id_item=id;
    var fileInput = document.getElementById("fileInput");
    var imgShow = document.getElementById("imgShow");
    $('#imagen').val('');
    var route = baseUrl+'/GetinfoStatus/'+id_item;
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result){
            $("#selectStatus").val(statusId);
            $("#commentary").val(result.data.commentary);
            // $("#id").val(id);
            if(result.data.image != null)
            {
                fileInput.style.display = "none";
                imgShow.style.display = "";
                $("#statusImg").attr("src","/img/itemsStatus/"+result.data.image);
                $("#dwnldImg").attr("href","/img/itemsStatus/"+result.data.image);
                $("#dwnldImg").attr("download",result.data.image);
                imgName = result.data.image;
            }
            else
            {
                fileInput.style.display = "";
                imgShow.style.display = "none";
            }
            $("#myEstatusModal").modal('show');
        }
    })
}
function actualizarEstatus()
{
    var status = $("#selectStatus").val();
    var commentary = $("#commentary").val();
    var table = $('#tbProf1').DataTable();

    var formData = new FormData();
    var files = $('input[type=file]');
    for (var i = 0; i < files.length; i++) {
        if (files[i].value == "" || files[i].value == null)
        {
            // console.log(files.length);
            // return false;
        }
        else
        {
            formData.append(files[i].name, files[i].files[0]);
        }
    }
    // console.log("entre");
    var formSerializeArray = $("#Form").serializeArray();
    for (var i = 0; i < formSerializeArray.length; i++) {
        formData.append(formSerializeArray[i].name, formSerializeArray[i].value)
    }

    // var $file = document.getElementById('imagen'),
    // formData = new FormData();

    // if ($file != null)
    // {
    //     for (var i = 0; i < $file.files.length; i++)
    //     {
    //         formData.append('file-' + i, $file.files[i]);
    //     }
    // }

    formData.append('_token', $("meta[name='csrf-token']").attr("content"));
    formData.append('status', status);
    formData.append('id', id_item);
    formData.append('idOrder', idOrder);
    formData.append('commentary', commentary);

    var route = baseUrl+"/updateStatus";

    jQuery.ajax({
        url:route,
        type:'post',
        data:formData,
        contentType: false,
        processData: false,
        cache: false,
        success:function(result)
        {
            refreshTable(result);
            alertify.success(result.message);
            $("#myEstatusModal").modal('hide');

        }
    })
}
function cerrarEstatus()
{
    $("#myEstatusModal").modal('hide');
    $("#comentary").val("");
}

idupdateItem = 0;
function editItem(id)
{
    idupdateItem=id;

    var route = baseUrl + '/GetInfoItem/'+id;
    // alert(route);
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            $("#store1").val(result.data.store);
            $("#item_number1").val(result.data.item_number);
            $("#description1").val(result.data.description);
            $("#back_order1").val(result.data.back_order);
            $("#existence1").val(result.data.existence);
            $("#net_price1").val(formatToCurrency(result.data.net_price));
            $("#total_price1").val(formatToCurrency(result.data.total_price));
            $("#myModalEdit").modal('show');

        }
    })
}
function actualizarItem()
{
    var store = $("#store1").val();
    var item_number = $("#item_number1").val();
    var description = $("#description1").val();
    var back_order = $("#back_order1").val();
    var existence = $("#existence1").val();
    var table = $('#tbProf1').DataTable();
    var net_price = Number($("#net_price1").val().replace(/[^0-9.-]+/g,""));
    var total_price = Number($("#total_price1").val().replace(/[^0-9.-]+/g,""));

    var route = baseUrl + '/updateItem';

    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'id':idupdateItem,
        'store':store,
        'item_number':item_number,
        'description':description,
        'back_order':back_order,
        'existence':existence,
        'net_price':net_price,
        'total_price':total_price,
        'fk_order':idOrder,
    };

    // alert(route);
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            refreshTable(result);
            $("#myModalEdit").modal('hide');
            alertify.success(result.message);
        }
    })
}
function cancelarEditItem()
{
    $("#myModalEdit").modal('hide');
}
function calculo1(){
    var existence = $("#existence1").val();
    var net_price = Number($("#net_price1").val().replace(/[^0-9.-]+/g,""));

    if(existence != ""){
        existence = parseFloat(existence);
    }
    else{
        existence = 0;
    }
    if(net_price != ""){
        net_price = parseFloat(net_price);
    }
    else{
        net_price = 0;
    }

    var temp = existence * net_price;

    $("#total_price1").val(formatToCurrency(temp));
}
function deleteFile()
{
    var fileInput = document.getElementById("fileInput");
    var imgShow = document.getElementById("imgShow");
    var route = baseUrl + '/deleteFile';
    var data = {
        'id':id_item,
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
        }
    })
}
