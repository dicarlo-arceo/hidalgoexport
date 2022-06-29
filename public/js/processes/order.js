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
        iDisplayLength: -1
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
            table.clear();
            cellar = 0;
            if(profile != 61)
            {
                result.data.forEach( function(valor, indice, array) {
                    btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
                    btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                    table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                        formatter.format(valor.net_price),formatter.format(valor.total_price),btnTrash]).node().id = valor.id;
                    cellar += valor.total_price;
                });
            }
            else
            {
                result.data.forEach( function(valor, indice, array) {
                    btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                    table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                        formatter.format(valor.net_price),formatter.format(valor.total_price)]).node().id = valor.id;
                    cellar += valor.total_price;
                });
            }
            comition = cellar * .225;
            mxn_total = comition * parseFloat($("#dlls").val());
            iva = mxn_total * 0.16;
            mxn_invoice = mxn_total + iva;
            $("#cellar").val(formatter.format(cellar));
            $("#comition").val(formatter.format(comition));
            $("#net_mxn").val(formatter.format(mxn_total));
            $("#iva").val(formatter.format(iva));
            $("#total_invoice").val(formatter.format(mxn_invoice));
            table.draw(false);
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
            table.clear();
            cellar = 0;
            if(profileOrder != 61)
            {
                result.data.forEach( function(valor, indice, array) {
                    btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
                    btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                    table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                        formatter.format(valor.net_price),formatter.format(valor.total_price),btnTrash]).node().id = valor.id;
                    cellar += valor.total_price;
                });
            }
            else
            {
                result.data.forEach( function(valor, indice, array) {
                    btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                    table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                        formatter.format(valor.net_price),formatter.format(valor.total_price)]).node().id = valor.id;
                    cellar += valor.total_price;
                });
            }
            comition = cellar * .225;
            mxn_total = comition * parseFloat($("#dlls").val());
            iva = mxn_total * 0.16;
            mxn_invoice = mxn_total + iva;
            $("#cellar").val(formatter.format(cellar));
            $("#comition").val(formatter.format(comition));
            $("#net_mxn").val(formatter.format(mxn_total));
            $("#iva").val(formatter.format(iva));
            $("#total_invoice").val(formatter.format(mxn_invoice));
            table.draw(false);
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
                    table.clear();
                    cellar = 0;
                    if(profileOrder != 61)
                    {
                        result.data.forEach( function(valor, indice, array) {
                            btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
                            btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                            table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                                formatter.format(valor.net_price),formatter.format(valor.total_price),btnTrash]).node().id = valor.id;
                            cellar += valor.total_price;
                        });
                    }
                    else
                    {
                        result.data.forEach( function(valor, indice, array) {
                            btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                            table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                                formatter.format(valor.net_price),formatter.format(valor.total_price)]).node().id = valor.id;
                            cellar += valor.total_price;
                        });
                    }
                    comition = cellar * .225;
                    mxn_total = comition * parseFloat($("#dlls").val());
                    iva = mxn_total * 0.16;
                    mxn_invoice = mxn_total + iva;
                    $("#cellar").val(formatter.format(cellar));
                    $("#comition").val(formatter.format(comition));
                    $("#net_mxn").val(formatter.format(mxn_total));
                    $("#iva").val(formatter.format(iva));
                    $("#total_invoice").val(formatter.format(mxn_invoice));
                    table.draw(false);
                    $("#myModal").modal('hide');
                }
            })
        },
        function(){
            alertify.error('Cancelado');
    });
}
function calculoTotales()
{
    comition = cellar * .225;
    mxn_total = comition * parseFloat($("#dlls").val());
    iva = mxn_total * 0.16;
    mxn_invoice = mxn_total + iva;
    $("#cellar").val(formatter.format(cellar));
    $("#comition").val(formatter.format(comition));
    $("#net_mxn").val(formatter.format(mxn_total));
    $("#iva").val(formatter.format(iva));
    $("#total_invoice").val(formatter.format(mxn_invoice));
}
function opcionesEstatus(id,statusId)
{
    id_item=id;
    var route = baseUrl+'/GetinfoStatus/'+id_item;
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result){
            $("#selectStatus").val(statusId);
            $("#commentary").val(result.data.commentary);
            $("#myEstatusModal").modal('show');
        }
    })
}
function actualizarEstatus()
{
    var status = $("#selectStatus").val();
    var sub_status = $("#selectSubEstatus").val();
    var commentary = $("#commentary").val();
    var table = $('#tbProf1').DataTable();
    var route = baseUrl+"/updateStatus";
    var data = {
        'id':id_item,
        'idOrder':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'status':status,
        "sub_status":sub_status,
        "commentary":commentary
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            table.clear();
            cellar = 0;
            if(profileOrder != 61)
            {
                result.data.forEach( function(valor, indice, array) {
                    btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
                    btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                    table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                        formatter.format(valor.net_price),formatter.format(valor.total_price),btnTrash]).node().id = valor.id;
                    cellar += valor.total_price;
                });
            }
            else
            {
                result.data.forEach( function(valor, indice, array) {
                    btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                    table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                        formatter.format(valor.net_price),formatter.format(valor.total_price)]).node().id = valor.id;
                    cellar += valor.total_price;
                });
            }
            comition = cellar * .225;
            mxn_total = comition * parseFloat($("#dlls").val());
            iva = mxn_total * 0.16;
            mxn_invoice = mxn_total + iva;
            $("#cellar").val(formatter.format(cellar));
            $("#comition").val(formatter.format(comition));
            $("#net_mxn").val(formatter.format(mxn_total));
            $("#iva").val(formatter.format(iva));
            $("#total_invoice").val(formatter.format(mxn_invoice));
            table.draw(false);
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
            table.clear();
            cellar = 0;
            if(profileOrder != 61)
            {
                result.data.forEach( function(valor, indice, array) {
                    btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
                    btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                    table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                        formatter.format(valor.net_price),formatter.format(valor.total_price),btnTrash]).node().id = valor.id;
                    cellar += valor.total_price;
                });
            }
            else
            {
                result.data.forEach( function(valor, indice, array) {
                    btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                    table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnStatus,
                        formatter.format(valor.net_price),formatter.format(valor.total_price)]).node().id = valor.id;
                    cellar += valor.total_price;
                });
            }
            comition = cellar * .225;
            mxn_total = comition * parseFloat($("#dlls").val());
            iva = mxn_total * 0.16;
            mxn_invoice = mxn_total + iva;
            $("#cellar").val(formatter.format(cellar));
            $("#comition").val(formatter.format(comition));
            $("#net_mxn").val(formatter.format(mxn_total));
            $("#iva").val(formatter.format(iva));
            $("#total_invoice").val(formatter.format(mxn_invoice));
            table.draw(false);
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
