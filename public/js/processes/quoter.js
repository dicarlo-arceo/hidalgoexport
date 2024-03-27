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
        },
        columnDefs: [ {
            orderable: false,
            targets:   0
        } ],
        order: [[ 1, 'asc' ]]
    });
} );

function closeModal(modal)
{
    $(modal).modal('hide');
}

function openNew()
{
    idupdate = 0;
    today = new Date();
    $("#quote_number").val("");
    $("#selectProject").val(0);
    $("#selectClient").val(0);
    $("#address").val("");
    $("#designer").val("");
    $("#quote_date").val(today.getFullYear() + "-" + ("0"+(today.getMonth()+1)).slice(-2) + "-" + ("0" + today.getDate()).slice(-2));
    $("#orderModal").modal('show');
}

function guardarOrden()
{
    var quote_number = $("#quote_number").val();
    var fk_project = $("#selectProject").val();
    var fk_user = $("#selectClient").val();
    var destiny = $("#destiny").val();
    var quote_date = $("#quote_date").val();
    var designer = $("#designer").val();

    var route = baseUrl + '/SaveOrder';
    // alert(route);
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'quote_number':quote_number,
        'fk_project':fk_project,
        'fk_user':fk_user,
        'destiny':destiny,
        'designer':designer,
        'quote_date':quote_date,
        'idupdate':idupdate,
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
            $("#orderModal").modal('hide');
            window.location.reload(true);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
idOrder = 0;

function nuevoItem(id,profile)
{
    idOrder = id;
    var table = $('#tbProf1').DataTable();
    var route = baseUrl + '/GetInfo/'+ id;

    $("#amount").val("");
    table.clear();

    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            console.log(result);
            refreshTable(result);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
    $("#myModal2").modal('show');
}

function newItem()
{
    $("#item_number").val("");
    $("#description").val("");
    $("#back_order").val("");
    $("#existence").val("");
    $("#net_price").val("");
    $("#total_price").val("");
    $("#myModalNewItem").modal('show');
}

bototal = 0;
total_merch = 0;

function refreshTable(result)
{
    var table = $('#tbProf1').DataTable();

    table.clear();
    cellar = 0;
    bototal = 0;
    subtotal = 0;
    // console.log(result.data);
    if(result.flag != 1)
    {
        pdfResult = result.data;
        // console.log(pdfResult);
        result.data.forEach( function(valor, indice, array) {
            btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
            chkbox = '<input class="form-check-input" type="checkbox" onclick="chkChange('+valor.id+')" id="chk'+valor.id+'">';
            table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,
                formatter.format(valor.net_price),formatter.format(parseFloat(valor.net_price)*parseFloat(valor.back_order)),btnTrash]).node().id = valor.id;
            bototal += parseFloat(valor.back_order) * parseFloat(valor.net_price);
        });
    }

    $("#percent").val(result.order.percentage);
    $("#broker").val(result.order.broker);
    $("#tax").val(result.order.tax);
    $("#dlls").val(result.order.exc_rate);
    $("#discount").val(result.order.discount);

    if(result.order.iva_flag == 1)
        $("#onoffIva").bootstrapToggle('on');
    else
        $("#onoffIva").bootstrapToggle('off');

    table.draw(false);
    calculoTotales();
}

function calculoTotales()
{
    var onoffIva = $("#onoffIva").prop('checked');
    var roundout = 0;
    if(onoffIva)
        roundout = 1;
    else
        roundout = 2;

    total_merch = 0;
    sub_total = 0;
    iva = 0;
    comition = 0;
    total = 0;

    if(parseFloat($("#discount").val()) != 0) total_merch = bototal - bototal * parseFloat($("#discount").val())/100;
    else total_merch = bototal;

    comition = total_merch * parseFloat($("#percent").val())/100;

    sub_total += comition + parseFloat($("#broker").val()) + parseFloat($("#tax").val()) + total_merch;

    if(roundout == 1) iva = sub_total * 0.16;
    else iva = 0;

    total = sub_total + iva;
    totalMxn = total * parseFloat($("#dlls").val());

    $("#totalmerch").val(formatter.format(bototal));
    $("#pay").val(formatter.format(comition));
    $("#iva").val(formatter.format(iva));
    $("#paytotal").val(formatter.format(total));
    $("#paytotalMxn").val(formatter.format(totalMxn));
}

function calculo(){
    var existence = $("#back_order").val();
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

function calculo1(){
    var existence = $("#back_order1").val();
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

function calculoBroker()
{
    if($("#broker").val() == "")
        $("#broker").val(0);

    var broker = parseFloat($("#broker").val());

    var route = baseUrl+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'broker':broker,
        'flag':5,
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

function calculoPercTienda()
{
    if($("#discount").val() == "")
        $("#discount").val(0);

    var discount = parseFloat($("#discount").val());

    var route = baseUrl+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'discount':discount,
        'flag':7,
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

function calculoTax()
{
    if($("#tax").val() == "")
        $("#tax").val(0);

    var tax = parseFloat($("#tax").val());

    var route = baseUrl+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'tax':tax,
        'flag':8,
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

function calculoIva()
{
    var onoffIva = $("#onoffIva").prop('checked');
    var iva_flag = 0;
    if(onoffIva)
        iva_flag = 1;
    else
        iva_flag = 2;

    var route = baseUrl+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'iva_flag':iva_flag,
        'flag':9,
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

function calculoDlls()
{
    if($("#dlls").val() == "")
        $("#dlls").val(0);

    var exc_rate = parseFloat($("#dlls").val());

    var route = baseUrlOrder+'/updateOrder';
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

function guardarItem()
{
    var store = $("#store").val();
    var item_number = $("#item_number").val();
    var description = $("#description").val();
    var back_order = $("#back_order").val();
    var existence = 0;
    var net_price = parseFloat($("#net_price").val());
    var total_price = 0;

    var route = baseUrl+'/storeItem';

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
            $("#myModalNewItem").modal('hide');
            alertify.success(result.message);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}

function deleteItem(id)
{
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
            $("#net_price1").val(result.data.net_price);
            calculo1();
            $("#myModalEditItem").modal('show');

        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function actualizarItem()
{
    var store = $("#store1").val();
    var item_number = $("#item_number1").val();
    var description = $("#description1").val();
    var back_order = $("#back_order1").val();
    var existence = 0;
    var net_price = Number($("#net_price1").val().replace(/[^0-9.-]+/g,""));
    var total_price = 0;

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
        'fk_order':idOrder
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
            $("#myModalEditItem").modal('hide');
            alertify.success(result.message);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}

function GetPDFQuote()
{
    total = Number($("#totalmerch").val().replace(/[^0-9.-]+/g,""));
    // total = $("#totalmerch").val();
    broker = Number($("#broker").val().replace(/[^0-9.-]+/g,""));
    pay = Number($("#pay").val().replace(/[^0-9.-]+/g,""));
    iva = Number($("#iva").val().replace(/[^0-9.-]+/g,""));
    payt = Number($("#paytotal").val().replace(/[^0-9.-]+/g,""));
    tax = Number($("#tax").val().replace(/[^0-9.-]+/g,""));
    discount = Number($("#discount").val().replace(/[^0-9.-]+/g,""));
    percent = Number($("#percent").val().replace(/[^0-9.-]+/g,""));
    paytotalMxn = Number($("#paytotalMxn").val().replace(/[^0-9.-]+/g,""));

    var route = baseUrl + '/GetPDF/' + total + '/' + broker + '/' + pay + '/' + iva + '/' + payt + '/' + idOrder + '/' + tax + '/' + discount + '/' + percent + '/' + paytotalMxn;

    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    var form = $('<form></form>');
    form.attr("method", "get");
    form.attr("action", route);
    form.attr('_token',$("meta[name='csrf-token']").attr("content"));
    $.each(function(key, value) {
        var field = $('<input></input>');
        field.attr("type", "hidden");
        field.attr("name", key);
        field.attr("value", value);
        form.append(field);
    });
    var field = $('<input></input>');
    field.attr("type", "hidden");
    field.attr("name", "_token");
    field.attr("value", $("meta[name='csrf-token']").attr("content"));
    form.append(field);
    $(document.body).append(form);
    form.submit();
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
            $("#quote_number").val(result.data.quote_number);
            $("#selectProject").val(result.data.fk_project);
            $("#selectClient").val(result.data.fk_user);
            $("#destiny").val(result.data.destiny);
            $("#quote_date").val(result.data.quote_date);
            $("#designer").val(result.data.designer);
            $("#orderModal").modal('show');

        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}

function terminarOrden(id)
{
    idupdate=id;
    $("#order_number").val("");
    $("#address").val("");
    $("#finishOrderModal").modal('show');
}

function crearOrden()
{
    var order_number = $("#order_number").val();
    var address = $("#address").val();

    var route = baseUrl + '/CreateOrder';
    // alert(route);
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'order_number':order_number,
        'address':address,
        'idupdate':idupdate,
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
            $("#finishOrderModal").modal('hide');
            window.location.reload(true);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}

function eliminarOrden(id)
{
    var route = baseUrl + "/" + id;
    var data = {
        'id':id,
        "_token": $("meta[name='csrf-token']").attr("content"),
    };
    alertify.confirm("Eliminar Cotización","¿Desea borrar la cotización?",
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

function formatToCurrency(amount){
    return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}
var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  });
