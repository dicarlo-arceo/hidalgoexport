var getUrlOrder = window.location;
var baseUrlOrder = getUrlOrder .protocol + "//" + getUrlOrder.host + "/processes/order/orders";

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
        order: [[ 1, 'asc' ]],
        'columnDefs': [
            {
               'targets': 0,
               'checkboxes': {
                  'selectRow': true
               }
            }
         ],
         'select': {
            'style': 'multi'
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
        columnDefs: [ {
            orderable: false,
            targets:   0
        } ],
        order: [[ 1, 'asc' ]]

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
flagTR = "null";
pdfResult = null;
checkedChkb = [];
chkbIds = [];
checkedOrderChkb = [];
chkbOrderIds = [];
flagMultipleItems = 0;
trAll = 0;
statAll = 0;
bototal = 0;
subtotal = 0;
tc = 0;

function refreshTable(result)
{
    var table = $('#tbProf1').DataTable();
    var onoffRound = document.getElementById("round");

    table.clear();
    cellar = 0;
    bototal = 0;
    subtotal = 0;
    // console.log(result.data);
    if(result.flag != 1)
    {
        pdfResult = result.data;
        // console.log(pdfResult);
        if(profileOrder != 61)
        {
            chkbIds = [];
            result.data.forEach( function(valor, indice, array) {
                btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
                if(valor.tr == 0) valTR = "-"; else valTR = valor.tr;
                chkbox = '<input class="form-check-input" type="checkbox" onclick="chkChange('+valor.id+')" id="chk'+valor.id+'">';
                btnTR = '<button class="btn btn-info" style="background-color: #FFFFFF !important; border-color: #000000 !important; color: #000000 !important;" onclick="editTR('+ valor.id +','+ valor.tr +')">'+ valTR +'</button>'
                btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                table.row.add([chkbox,valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnTR,btnStatus,
                    formatter.format(valor.net_price),formatter.format(valor.total_price),btnTrash]).node().id = valor.id;
                cellar += parseFloat(valor.total_price);
                bototal += parseFloat(valor.back_order) * parseFloat(valor.net_price);
                chkbIds.push(valor.id);
            });
        }
        else
        {
            result.data.forEach( function(valor, indice, array) {
                btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,valor.tr,btnStatus,
                    formatter.format(valor.net_price),formatter.format(valor.total_price)]).node().id = valor.id;
                cellar += parseFloat(valor.total_price);
                bototal += parseFloat(valor.back_order) * parseFloat(valor.net_price);
            });
        }
    }

    subtotal = cellar + bototal;

    if(profileOrder != 61)
    {
        var btnNewItem = document.getElementById("btnNewItem");
        var btnHojaCobro = document.getElementById("btnHojaCobro");
        btnNewItem.style.display = "";
        btnHojaCobro.style.display = "";
        document.getElementById('dlls').disabled = false;
        document.getElementById('percent').disabled = false;
        document.getElementById('exp').disabled = false;
        document.getElementById('broker').disabled = false;
        document.getElementById('onoffCurrency').disabled = false;
        document.getElementById('onoffRound').disabled = false;
        document.getElementById('broker').disabled = false;
    }

    var btnDescargarItem = document.getElementById("btnDescargarItem");
    btnDescargarItem.style.display = "";


    $("#dlls").val(result.data[0].exc_rate);
    $("#percent").val(result.data[0].percentage);
    $("#exp").val(result.data[0].expenses);
    $("#broker").val(result.data[0].broker);
    $("#onoffCurrency").prop('disabled', false);
    onoffRound.style.display = "";
    if(result.data[0].currency == 1)
        $("#onoffCurrency").bootstrapToggle('on');
    else
        $("#onoffCurrency").bootstrapToggle('off');
    if(result.data[0].roundout == 1)
        $("#onoffRound").bootstrapToggle('on');
    else
        $("#onoffRound").bootstrapToggle('off');
    if(profileOrder == 61)
    {
        $("#onoffCurrency").prop('disabled', true);
        onoffRound.style.display = "none";
    }

    calculoTotales();

    table.draw(false);
}
function refreshTableAll(result)
{
    if(profileOrder != 61)
    {
        document.getElementById('btnChangeAll').disabled = true;
        document.getElementById('chkAll').checked = false;
        checkedChkb = [];
    }
    var table = $('#tbProf1').DataTable();
    var onoffRound = document.getElementById("onoffRound");

    table.clear();
    cellar = 0;
    bototal = 0;
    subtotal = 0;
    console.log(result.payData['cellar']);
    if(result.flag != 1)
    {
        pdfResult = result.data;
        // console.log(pdfResult);
        if(profileOrder != 61)
        {
            chkbIds = [];
            result.data.forEach( function(valor, indice, array) {
                btnTrash = '<button type="button" class="btn btn-warning"'+'onclick="editItem('+valor.id+')"><i class="fa fa-edit"></i></button> <button type="button" class="btn btn-danger"'+'onclick="deleteItem('+valor.id+')"><i class="fa fa-trash"></i></button>';
                if(valor.tr == 0) valTR = "-"; else valTR = valor.tr;
                chkbox = '<input class="form-check-input" type="checkbox" onclick="chkChange('+valor.id+')" id="chk'+valor.id+'">';
                btnTR = '<button class="btn btn-info" style="background-color: #FFFFFF !important; border-color: #000000 !important; color: #000000 !important;" onclick="editTR('+ valor.id +','+ valor.tr +')">'+ valTR +'</button>'
                btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                table.row.add([chkbox,valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,btnTR,btnStatus,
                    formatter.format(valor.net_price),formatter.format(valor.total_price),btnTrash]).node().id = valor.id;
                cellar += parseFloat(valor.total_price);
                bototal += parseFloat(valor.back_order) * parseFloat(valor.net_price);
                chkbIds.push(valor.id);
            });
        }
        else
        {
            result.data.forEach( function(valor, indice, array) {
                btnStatus = '<button class="btn btn-info" style="background-color: #'+ valor.color +' !important; border-color: #'+ valor.border_color +' !important; color: #'+ valor.font_color +' !important;" onclick="opcionesEstatus('+ valor.id +','+ valor.statId +')">'+ valor.name +'</button>'
                table.row.add([valor.store,valor.item_number,valor.description,valor.back_order,valor.existence,valor.tr,btnStatus,
                    formatter.format(valor.net_price),formatter.format(valor.total_price)]).node().id = valor.id;
                cellar += parseFloat(valor.total_price);
                bototal += parseFloat(valor.back_order) * parseFloat(valor.net_price);
            });
        }
    }

    subtotal = cellar + bototal;

    var btnNewItem = document.getElementById("btnNewItem");
    var btnHojaCobro = document.getElementById("btnHojaCobro");
    var btnDescargarItem = document.getElementById("btnDescargarItem");
    btnNewItem.style.display = "none";
    btnHojaCobro.style.display = "none";
    btnDescargarItem.style.display = "none";
    document.getElementById('dlls').disabled = true;
    document.getElementById('percent').disabled = true;
    document.getElementById('exp').disabled = true;
    document.getElementById('broker').disabled = true;
    document.getElementById('onoffCurrency').disabled = true;
    document.getElementById('onoffRound').disabled = true;

    $("#dlls").val(result.data[0].exc_rate);
    $("#percent").val(result.data[0].percentage);
    $("#exp").val(result.data[0].expenses);
    $("#broker").val(result.data[0].broker);
    $("#onoffCurrency").prop('disabled', false);
    onoffRound.style.display = "";
    if(result.data[0].currency == 1)
        $("#onoffCurrency").bootstrapToggle('on');
    else
        $("#onoffCurrency").bootstrapToggle('off');
    if(result.data[0].roundout == 1)
        $("#onoffRound").bootstrapToggle('on');
    else
        $("#onoffRound").bootstrapToggle('off');
    if(profileOrder == 61)
    {
        $("#onoffCurrency").prop('disabled', true);
        onoffRound.style.display = "none";
    }

    $("#paytotal").val(formatter.format(result.payData['usd_total']));
    $("#cellar").val(formatter.format(result.payData['cellar']));
    $("#bototal").val(formatter.format(bototal));
    $("#subtotal").val(formatter.format(subtotal));
    $("#comition").val(formatter.format(result.payData['comition']));
    $("#net_mxn").val(formatter.format(result.payData['mxn_total']));
    $("#iva").val(formatter.format(result.payData['iva']));
    $("#total_invoice").val(formatter.format(result.payData['mxn_invoice']));

    table.draw(false);
}
function nuevoItem(id,profile)
{
    idOrder = id;
    profileOrder = profile
    var table = $('#tbProf1').DataTable();
    var route = baseUrlOrder + '/GetInfo/'+ id + '/'+ tc;
    var btnNewItem = document.getElementById("btnNewItem");

    flagTR = "null";
    flagMultipleItems = 0;

    $("#amount").val("");
    table.clear();

    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            if(profileOrder != 61)
            {
                document.getElementById('btnChangeAll').disabled = true;
                document.getElementById('chkAll').checked = false;
                btnNewItem.style.display = "";
                checkedChkb = [];
            }
            refreshTable(result);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
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
    $("#item_number").val("");
    $("#description").val("");
    $("#back_order").val("");
    $("#existence").val("");
    $("#net_price").val("");
    $("#total_price").val("");
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
    var route = baseUrlOrder + '/updateTR';
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'tr':tr,
        'id':idTR,
        'fk_order':idOrder,
        'flagMultipleItems':flagMultipleItems,
        'trAll':trAll,
        'statAll':statAll,
        'ids':checkedOrderChkb,
        'flagTR':flagTR
    };
    jQuery.ajax({
        url:route,
        type:"post",
        data: data,
        dataType: 'json',
        success:function(result)
        {
            if(flagMultipleItems == 0)
                refreshTable(result);
            else
                refreshTableAll(result);
            $("#myModalTR").modal('hide');
            alertify.success(result.message);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
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

    var route = baseUrlOrder;

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
        'flagMultipleItems':flagMultipleItems,
        'trAll':trAll,
        'statAll':statAll,
        'ids':checkedOrderChkb,
        'flagTR':flagTR
    };

    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            if(flagMultipleItems == 0)
                refreshTable(result);
            else
                refreshTableAll(result);
            $("#myModal").modal('hide');
            alertify.success(result.message);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
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

    var route = baseUrlOrder + '/GetInfoOrder/'+id;
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
            $("#designer").val(result.data.designer);
            $("#orderModal").modal('show');

        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
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
    var designer = $("#designer").val();

    var route = baseUrlOrder + "/" + idupdate;
    var data = {
        'id':idupdate,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'order_number':order_number,
        'fk_project':fk_project,
        'address':address,
        'designer':designer,
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
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function eliminarOrden(id)
{
    var route = baseUrlOrder + "/" + id;
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
function deleteItem(id)
{
    var table = $('#tbProf1').DataTable();
    var route = baseUrlOrder + '/DeleteItem/' + id + "/" + idOrder + "/" + flagTR;
    var data = {
        'id':id,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'flagMultipleItems':flagMultipleItems,
        'trAll':trAll,
        'statAll':statAll,
        'ids':checkedOrderChkb,
        'flagTR':flagTR
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
                    if(flagMultipleItems == 0)
                        refreshTable(result);
                    else
                        refreshTableAll(result);
                    alertify.success(result.message);
                    $("#myModal").modal('hide');
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
function calculoPerc()
{
    if($("#percent").val() == "")
        $("#percent").val(0);

    var percent = parseFloat($("#percent").val());

    var route = baseUrlOrder+'/updateOrder';
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

    var route = baseUrlOrder+'/updateOrder';
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

    var route = baseUrlOrder+'/updateOrder';
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
function calculoRound()
{
    var round = $("#onoffRound").prop('checked');
    var roundout = 0;
    if(round)
        roundout = 1;
    else
        roundout = 2;

    var route = baseUrlOrder+'/updateOrder';
    var data = {
        'id':idOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'roundout':roundout,
        'flag':6,
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

    var route = baseUrlOrder+'/updateOrder';
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
function calculoTotales()
{
    mxn_total = 0;
    usd_total = 0;
    var curr = $("#onoffCurrency").prop('checked');
    var round = $("#onoffRound").prop('checked');

    comition = cellar * parseFloat($("#percent").val())/100;
    if(round)
        if(comition > 0 && comition < 100)
            comition = 100;

    if(curr)
        usd_total += parseFloat($("#exp").val());
    else
        mxn_total += parseFloat($("#exp").val());

    usd_total += comition + parseFloat($("#broker").val());
    mxn_total += usd_total * parseFloat($("#dlls").val());
    iva = mxn_total * 0.16;
    mxn_invoice = mxn_total + iva;

    $("#paytotal").val(formatter.format(usd_total));
    $("#cellar").val(formatter.format(cellar));
    $("#bototal").val(formatter.format(bototal));
    $("#subtotal").val(formatter.format(subtotal));
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
    var route = baseUrlOrder+'/GetinfoStatus/'+id_item;
    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result){
            $("#selectStatus").val(statusId);
            $("#commentary").val(result.data.commentary);
            $("#date").val(result.data.delivery_date);
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
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function actualizarEstatus()
{
    var status = $("#selectStatus").val();
    var commentary = $("#commentary").val();
    var date = $("#date").val();
    var table = $('#tbProf1').DataTable();
    // alert(date);

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
    formData.append('delivery_date', date);
    formData.append('commentary', commentary);
    formData.append('flagTR', flagTR);
    formData.append('trAll', trAll);
    formData.append('statAll', statAll);
    formData.append('ids', checkedOrderChkb);
    formData.append('flagMultipleItems', flagMultipleItems);

    var route = baseUrlOrder+"/updateStatus";

    jQuery.ajax({
        url:route,
        type:'post',
        data:formData,
        contentType: false,
        processData: false,
        cache: false,
        success:function(result)
        {
            if(flagMultipleItems == 0)
                refreshTable(result);
            else
                refreshTableAll(result);
            alertify.success(result.message);
            $("#myEstatusModal").modal('hide');

        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
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

    var route = baseUrlOrder + '/GetInfoItem/'+id;
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
            $("#net_price1").val(result.data.net_price);
            $("#total_price1").val(result.data.total_price);
            $("#myModalEdit").modal('show');

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
    var existence = $("#existence1").val();
    var table = $('#tbProf1').DataTable();
    var net_price = Number($("#net_price1").val().replace(/[^0-9.-]+/g,""));
    var total_price = Number($("#total_price1").val().replace(/[^0-9.-]+/g,""));

    var route = baseUrlOrder + '/updateItem';

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
        'flagMultipleItems':flagMultipleItems,
        'trAll':trAll,
        'statAll':statAll,
        'ids':checkedOrderChkb,
        'flagTR':flagTR,
    };

    // alert(route);
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            if(flagMultipleItems == 0)
                refreshTable(result);
            else
                refreshTableAll(result);
            $("#myModalEdit").modal('hide');
            alertify.success(result.message);
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
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
    var route = baseUrlOrder + '/deleteFile';
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
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function verEntregados(id,profile)
{
    profileOrder = profile;
    idOrder = id;
    var selectTR = $('#selectTR');
    var sTR = document.getElementById("sTR");
    var lvl = document.getElementById("lvl");
    var btnAcceptTr = document.getElementById("btnAcceptTr");
    var route = baseUrlOrder + '/GetinfoTR/'+id;

    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            console.log(result.data);
            if(result.data.length == 0)
            {
                lvl.style.display = "";
                sTR.style.display = "none";
                btnAcceptTr.style.display = "none";
            }
            else
            {
                $("#selectTR").empty();
                selectTR.append('<option selected hidden value="">Seleccione una opción</option>');
                result.data.forEach( function(valor, indice, array) {
                    if(valor.tr != 0) selectTR.append("<option value='" + valor.tr + "'>" + valor.tr + "</option>");
                });
                selectTR.append('<option value="0">Todos</option>');
                sTR.style.display = "";
                lvl.style.display = "none";
                btnAcceptTr.style.display = "";
            }
            $("#mySelectTRModal").modal('show');
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })

}
function cerrarSelectTR()
{
    $("#mySelectTRModal").modal('hide');
}
function abrirSeleccionTR()
{
    var selectTR = $('#selectTR').val();
    var btnNewItem = document.getElementById("btnNewItem");
    var route = baseUrlOrder + '/GetItemsTR/' + idOrder + "/" + selectTR + "/" + tc;

    jQuery.ajax({
        url:route,
        type:'get',
        dataType:'json',
        success:function(result)
        {
            $("#mySelectTRModal").modal('hide');
            if(profileOrder != 61)
            {
                btnNewItem.style.display = "none";
                document.getElementById('btnChangeAll').disabled = true;
                document.getElementById('chkAll').checked = false;
            }
            flagTR = selectTR;
            flagMultipleItems = 0;
            checkedChkb = [];

            refreshTable(result);
            $("#myModal2").modal('show');
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function dwnldPDF()
{
    var paymntDetails = $("#paymntDetails").prop('checked');
    if(paymntDetails)
    {
        cellar = $("#cellar").val();
        comition = $("#comition").val();
        dlls = $("#dlls").val();
    }
    else
    {
        cellar = 1;
        comition = 1;
        dlls = 1;
    }
    var route = baseUrlOrder + '/GetPDF/' + idOrder + '/' + cellar + '/' + comition + '/' + dlls + '/' + $("#datePDF").val() + '/' + $("#pkgs").val();

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
function abrirPDF()
{
    $("#datePDF").val("");
    $("#pkgs").val("");
    $("#myModalPDF").modal('show');
}
function cerrarPDF()
{
    $("#myModalPDF").modal('hide');
}
function abrirPDFItems()
{
    $("#myModalPDFItems").modal('show');
    $("#invoiceDetailsItems").bootstrapToggle('off');
}
function cerrarPDFItems()
{
    $("#myModalPDFItems").modal('hide');
}
function ItemsPDF()
{
    var paymntDetails = $("#paymntDetailsItems").prop('checked');
    var invoiceDetails = $("#invoiceDetailsItems").prop('checked');
    if(paymntDetails)
    {
        cellar = $("#cellar").val();
        comition = $("#comition").val();
        mxn_total = $("#net_mxn").val();
        iva = $("#iva").val();
        mxn_invoice = $("#total_invoice").val();
        usd_total = $("#paytotal").val();
        broker = $("#broker").val();
    }
    else
    {
        cellar = 1;
        comition = 1;
    }
    if(invoiceDetails)
    {
        invoiceflag = 0;
    }
    else
    {
        invoiceflag = 1;
    }

    var route = baseUrlOrder + '/ItemsPDF/' + idOrder + '/' + flagTR + '/' + cellar + '/' + comition + '/' + mxn_total + '/' + iva + '/' + mxn_invoice + '/' + usd_total + '/' + broker + '/' + invoiceflag;

    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    var form = $('<form></form>');
    form.attr("method", "get");
    form.attr("action", route);
    form.attr("data", pdfResult);
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
idItemsOrder = 0;
function abrirEstatusTodos()
{
    $('#selectStatusNew').val("");
    $('#trStatusAll').val("");
    $("#myEstatusModalTodos").modal('show');
}
function cerrarEstatusTodos()
{
    $("#myEstatusModalTodos").modal('hide');
}
function actualizarEstatusTodos()
{
    var selectStatusNew = $('#selectStatusNew').val();
    var trStatusAll = $('#trStatusAll').val();

    var route = baseUrlOrder+'/updateStatusOrder';
    var data = {
        'id':idItemsOrder,
        "_token": $("meta[name='csrf-token']").attr("content"),
        'statusNew':selectStatusNew,
        'trStatusAll':trStatusAll,
        'idsItms':checkedChkb,
        'idOrder':idOrder,
        'flagMultipleItems':flagMultipleItems,
        'trAll':trAll,
        'statAll':statAll,
        'ids':checkedOrderChkb,
        'flagTR':flagTR,
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            if(flagMultipleItems == 0)
                refreshTable(result);
            else
                refreshTableAll(result);
            document.getElementById('btnChangeAll').disabled = true;
            document.getElementById('btnBOAll').disabled = true;
            document.getElementById('chkAll').checked = false;
            checkedChkb = [];
            $("#myEstatusModalTodos").modal('hide');
            alertify.success('Orden Actualizada');
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function chkChange(id)
{
    if (document.getElementById('chk'+id).checked)
    {
        checkedChkb.push(id);
    }
    else
    {
        for(var i = 0; i < checkedChkb.length; i++)
        {
            if(checkedChkb[i] == id)
            {
                checkedChkb.splice(i, 1);
                break;
            }
        }
    }
    if(checkedChkb.length == 0)
    {
        document.getElementById('btnChangeAll').disabled = true;
        document.getElementById('btnBOAll').disabled = true;
    }
    else
    {
        document.getElementById('btnChangeAll').disabled = false;
        document.getElementById('btnBOAll').disabled = false;
    }
    // console.log(checkedChkb);
}
function chkAll()
{
    chkbIds.forEach(function chechChecked(item)
    {
        if (document.getElementById('chkAll').checked)
        {
            document.getElementById('chk'+item).checked = true;
            checkedChkb = chkbIds;
            chkbIds = [];
            checkedChkb.forEach(function chechChecked(item)
            {
                chkbIds.push(item);
            });
        document.getElementById('btnBOAll').disabled = false;
        document.getElementById('btnChangeAll').disabled = false;
        }
        else
        {
            checkedChkb = [];
            document.getElementById('chk'+item).checked = false;
        document.getElementById('btnBOAll').disabled = true;
        document.getElementById('btnChangeAll').disabled = true;
        }
    });
    // console.log(checkedChkb);
}
function chkOrderChange(id)
{
    if (document.getElementById('chkOrder'+id).checked)
    {
        checkedOrderChkb.push(id);
    }
    else
    {
        for(var i = 0; i < checkedOrderChkb.length; i++)
        {
            if(checkedOrderChkb[i] == id)
            {
                checkedOrderChkb.splice(i, 1);
                break;
            }
        }
    }
    if(checkedOrderChkb.length == 0)
    {
        document.getElementById('btnOrderUncheckAll').disabled = true;
        document.getElementById('btnOrderChangeAll').disabled = true;
        document.getElementById('btnItemsAll').disabled = true;
        document.getElementById('btnCloseOrd').disabled = true;
        document.getElementById('btnOpenOrd').disabled = true;
        document.getElementById('btnAssignRecp').disabled = true;
        document.getElementById('btnViewItms').disabled = true;
    }

    else
    {
        document.getElementById('btnOrderUncheckAll').disabled = false;
        document.getElementById('btnOrderChangeAll').disabled = false;
        document.getElementById('btnItemsAll').disabled = false;
        document.getElementById('btnCloseOrd').disabled = false;
        document.getElementById('btnOpenOrd').disabled = false;
        document.getElementById('btnAssignRecp').disabled = false;
        document.getElementById('btnViewItms').disabled = false;
    }
}
function UncheckTodos()
{
    checkedOrderChkb.forEach(function chechChecked(item)
    {
        document.getElementById('chkOrder'+item).checked = false;
    });
    checkedOrderChkb = [];
    document.getElementById('btnOrderChangeAll').disabled = true;
    document.getElementById('btnOrderUncheckAll').disabled = true;
    document.getElementById('btnItemsAll').disabled = true;
    document.getElementById('btnCloseOrd').disabled = true;
    document.getElementById('btnOpenOrd').disabled = true;
    document.getElementById('btnAssignRecp').disabled = true;
    document.getElementById('btnViewItms').disabled = true;
}
function HojaCobroTodos()
{
    var table = $('#tbProf').DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    checkedOrderChkb = [];
    if(rows_selected.length == 0)
    {
        alert("¡Seleccione al menos un registro!");
    }
    else
    {
        $.each(rows_selected, function(index, rowId){
            checkedOrderChkb.push(rowId);
        });
        var selectTR = $('#selectTROrder');
        var selectAddress = $('#selectAddressOrder');
        var sTR = document.getElementById("sTROrder");
        var lvl = document.getElementById("lvlOrder");
        var btnAcceptTr = document.getElementById("btnAcceptOrder");
        var route = baseUrlOrder + '/GetinfoTROrders/1';
        var data = {
            "_token": $("meta[name='csrf-token']").attr("content"),
            'ids':checkedOrderChkb
        };

        jQuery.ajax({
            url:route,
            type:'get',
            data:data,
            dataType:'json',
            success:function(result)
            {
                console.log(result);
                if(result.data.length == 0)
                {
                    lvl.style.display = "";
                    sTR.style.display = "none";
                    btnAcceptTr.style.display = "none";
                }
                else
                {
                    $("#datePDFOrder").val("");
                    $("#pkgsOrder").val("");

                    $("#selectTROrder").empty();
                    selectTR.append('<option selected hidden value="">Seleccione una opción</option>');
                    result.data.forEach( function(valor, indice, array) {
                        if(valor != 0) selectTR.append("<option value='" + valor + "'>" + valor + "</option>");
                    });

                    $("#selectAddressOrder").empty();
                    selectAddress.append('<option selected hidden value="">Seleccione una opción</option>');
                    // result.address.forEach( function(valor, indice, array) {
                    //     selectAddress.append("<option value='" + valor.id + "'>" + valor.address + "</option>");
                    // });
                    Object.values(result.address).forEach(val => {
                        selectAddress.append("<option value='" + val['id'] + "'>" + val['address'] + "</option>");
                    });

                    sTR.style.display = "";
                    lvl.style.display = "none";
                    btnAcceptTr.style.display = "";
                }
                $("#mySelectHojaC").modal('show');
            },
            error:function(result,error,errorTrown)
            {
                alertify.error(errorTrown);
            }
        })
    }
}
function cerrarSelectTROrder()
{
    $("#mySelectHojaC").modal('hide');
}
function DwnldHojaCobroTodos()
{
    var paymntDetails = $("#paymntDetailsOrder").prop('checked');
    if(paymntDetails)
    {
        flag = 0;
        // cellar = $("#cellar").val();
        // comition = $("#comition").val();
        // dlls = $("#dlls").val();
    }
    else
    {
        flag = 1;
    }
    stringaux = "";
    checkedOrderChkb.forEach(function(valor, indice, array) {
        stringaux = stringaux.concat(String(valor));
        if(checkedOrderChkb.length-1 != indice) stringaux = stringaux.concat("-");
    });

    var route = baseUrlOrder + '/GetPDFCobroTodos/' + flag + '/' + $("#datePDFOrder").val() + '/' + $("#pkgsOrder").val() + '/' + $("#selectTROrder").val() + '/' + $("#selectAddressOrder").val() + '/' + stringaux + "/" + tc;

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
function moverBO()
{
    var route = baseUrlOrder+'/updateBOAll';
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'idsItems':checkedChkb,
        'idOrder':idOrder,
        'flagTR':flagTR,
        'trAll':trAll,
        'statAll':statAll,
        'ids':checkedOrderChkb,
        'flagMultipleItems':flagMultipleItems,
    };
    alertify.confirm("Back Order","¿Desea transferir la back order a existencias?",
        function(){
            jQuery.ajax({
                url:route,
                type:'post',
                data:data,
                dataType:'json',
                success:function(result)
                {
                    if(flagMultipleItems == 0)
                        refreshTable(result);
                    else
                        refreshTableAll(result);
                    document.getElementById('btnChangeAll').disabled = true;
                    document.getElementById('btnBOAll').disabled = true;
                    document.getElementById('chkAll').checked = false;
                    checkedChkb = [];
                    alertify.success('Orden Actualizada');
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
orderItemsAll = 0;
function HojaItemsTodos()
{
    var table = $('#tbProf').DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    checkedOrderChkb = [];
    if(rows_selected.length == 0)
    {
        alert("¡Seleccione al menos un registro!");
    }
    else
    {
        $.each(rows_selected, function(index, rowId){
            checkedOrderChkb.push(rowId);
        });
        var selectTR = $('#selectTRItems');
        var sTR = document.getElementById("sTRItems");
        var lvl = document.getElementById("lvlItems");
        var btnAcceptTr = document.getElementById("btnAcceptItems");
        var route = baseUrlOrder + '/GetinfoTRItems/1';
        var data = {
            "_token": $("meta[name='csrf-token']").attr("content"),
            'ids':checkedOrderChkb
        };

        jQuery.ajax({
            url:route,
            type:'get',
            data:data,
            dataType:'json',
            success:function(result)
            {
                console.log(result);
                orderItemsAll =  result.address[0].id;
                // if(result.data.length == 0)
                // {
                //     lvl.style.display = "";
                //     sTR.style.display = "none";
                //     btnAcceptTr.style.display = "none";
                // }
                // else
                // {
                    $("#selectTRItems").empty();
                    selectTR.append('<option selected hidden value="0">Seleccione una opción</option>');
                    result.data.forEach( function(valor, indice, array) {
                        if(valor != 0) selectTR.append("<option value='" + valor + "'>" + valor + "</option>");
                    });
                    selectTR.append('<option value="0">Todos</option>');
                    // result.address.forEach( function(valor, indice, array) {
                    //     selectAddress.append("<option value='" + valor.id + "'>" + valor.address + "</option>");
                    // });

                    sTR.style.display = "";
                    lvl.style.display = "none";
                    btnAcceptTr.style.display = "";
                    $("#invoiceDetailsItemsAll").bootstrapToggle('off');
                // }
                $("#myModalPDFItemsAll").modal('show');
            },
            error:function(result,error,errorTrown)
            {
                alertify.error(errorTrown);
            }
        })
    }
}
function cerrarPDFItemsAll()
{
    $("#myModalPDFItemsAll").modal('hide');
}
function DwnldItemsTodos()
{
    var paymntDetails = $("#paymntDetailsItemsAll").prop('checked');
    var invoiceDetails = $("#invoiceDetailsItemsAll").prop('checked');
    if(paymntDetails)
    {
        flag = 0;
        // cellar = $("#cellar").val();
        // comition = $("#comition").val();
        // dlls = $("#dlls").val();
    }
    else
    {
        flag = 1;
    }
    if(invoiceDetails)
    {
        invoiceflag = 0;
    }
    else
    {
        invoiceflag = 1;
    }

    stringaux = "";
    checkedOrderChkb.forEach(function(valor, indice, array) {
        stringaux = stringaux.concat(String(valor));
        if(checkedOrderChkb.length-1 != indice) stringaux = stringaux.concat("-");
    });

    var route = baseUrlOrder + '/GetPDFItemsTodos/' + flag + '/' + $("#selectTRItems").val() + '/' + stringaux + '/' + invoiceflag + '/' + $("#selectStatusItemsAll").val() + "/" + tc;

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
function showInvoiceAll()
{
    var invoiceDetailschk = $("#paymntDetailsItemsAll").prop('checked');
    var invoiceDetails = document.getElementById("invoiceRowAll");
    if(invoiceDetailschk)
    {
        invoiceDetails.style.display = "";
    }
    else
    {
        invoiceDetails.style.display = "none";
    }
}
function showInvoice()
{
    var invoiceDetailschk = $("#paymntDetailsItems").prop('checked');
    var invoiceDetails = document.getElementById("invoiceRow");
    if(invoiceDetailschk)
    {
        invoiceDetails.style.display = "";
    }
    else
    {
        invoiceDetails.style.display = "none";
    }
}
function CloseOrders()
{
    var table = $('#tbProf').DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    checkedOrderChkb = [];
    if(rows_selected.length == 0)
    {
        alert("¡Seleccione al menos un registro!");
    }
    else
    {
        $.each(rows_selected, function(index, rowId){
            checkedOrderChkb.push(rowId);
        });
        var route = baseUrlOrder+'/CloseOrders';
        var data = {
            "_token": $("meta[name='csrf-token']").attr("content"),
            'ids':checkedOrderChkb
        };
        alertify.confirm("Cerrar Ordenes","¿Desea cerrar las ordenes seleccionadas?",
            function(){
                jQuery.ajax({
                    url:route,
                    type:'post',
                    data:data,
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
            },
            function(){
                alertify.error('Cancelado');
        });
    }
}
function OpenOrders()
{
    var table = $('#tbProf').DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    checkedOrderChkb = [];
    if(rows_selected.length == 0)
    {
        alert("¡Seleccione al menos un registro!");
    }
    else
    {
        $.each(rows_selected, function(index, rowId){
            checkedOrderChkb.push(rowId);
        });
        var route = baseUrlOrder+'/OpenOrders';
        var data = {
            "_token": $("meta[name='csrf-token']").attr("content"),
            'ids':checkedOrderChkb
        };
        alertify.confirm("Abrir Ordenes","¿Desea abrir las ordenes seleccionadas?",
            function(){
                jQuery.ajax({
                    url:route,
                    type:'post',
                    data:data,
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
            },
            function(){
                alertify.error('Cancelado');
        });
    }
}

function AsignarRecibo()
{
    var table = $('#tbProf').DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    checkedOrderChkb = [];
    if(rows_selected.length == 0)
    {
        alert("¡Seleccione al menos un registro!");
    }
    else
    {
        $.each(rows_selected, function(index, rowId){
            checkedOrderChkb.push(rowId);
        });
        var selectTR = $('#selectTRRecipt');
        var sTR = document.getElementById("sTRRecipt");
        var lvl = document.getElementById("lvlRecipt");
        var btnAcceptTr = document.getElementById("btnAcceptRecipt");
        var route = baseUrlOrder + '/GetinfoTROrders/1';
        var data = {
            "_token": $("meta[name='csrf-token']").attr("content"),
            'ids':checkedOrderChkb
        };

        jQuery.ajax({
            url:route,
            type:'get',
            data:data,
            dataType:'json',
            success:function(result)
            {
                console.log(result);
                if(result.data.length == 0)
                {
                    lvl.style.display = "";
                    sTR.style.display = "none";
                    btnAcceptTr.style.display = "none";
                }
                else
                {
                    $("#selectTRRecipt").empty();
                    selectTR.append('<option selected hidden value="">Seleccione una opción</option>');
                    result.data.forEach( function(valor, indice, array) {
                        if(valor != 0) selectTR.append("<option value='" + valor + "'>" + valor + "</option>");
                    });
                    $('#receipt').val("");
                    sTR.style.display = "";
                    lvl.style.display = "none";
                    btnAcceptTr.style.display = "";
                }
                $("#myReciptTR").modal('show');
            },
            error:function(result,error,errorTrown)
            {
                alertify.error(errorTrown);
            }
        })
    }
}
function cerrarSelectTRRecipt()
{
    $("#myReciptTR").modal('hide');
}
function SaveAssign()
{
    var tr = $("#selectTRRecipt").val();
    var stringaux = "";
    checkedOrderChkb.forEach(function(valor, indice, array) {
        stringaux = stringaux.concat(String(valor));
        if(checkedOrderChkb.length-1 != indice) stringaux = stringaux.concat("-");
    });

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
    formData.append('tr', tr);
    formData.append('ids', stringaux);

    var route = baseUrlOrder+"/AssignReceipt";

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
            $("#myReciptTR").modal('hide');

        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })
}
function VerItems()
{
    var table = $('#tbProf').DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    checkedOrderChkb = [];
    if(rows_selected.length == 0)
    {
        alert("¡Seleccione al menos un registro!");
    }
    else
    {
        $.each(rows_selected, function(index, rowId){
            checkedOrderChkb.push(rowId);
        });
        var selectTR = $('#selectTRViewItems');
        var route = baseUrlOrder + '/GetinfoTRItems/1';
        var data = {
            "_token": $("meta[name='csrf-token']").attr("content"),
            'ids':checkedOrderChkb,
        };

        jQuery.ajax({
            url:route,
            type:'get',
            data:data,
            dataType:'json',
            success:function(result)
            {
                console.log(result.data);
                orderItemsAll =  result.address[0].id;
                $("#selectTRViewItems").empty();
                selectTR.append('<option selected hidden value="0">Seleccione una opción</option>');
                result.data.forEach( function(valor, indice, array) {
                    if(valor != 0) selectTR.append("<option value='" + valor + "'>" + valor + "</option>");
                });
                selectTR.append('<option value="0">Todos</option>');

                flagMultipleItems = 1;
                $("#myModalViewItemsAll").modal('show');
            },
            error:function(result,error,errorTrown)
            {
                alertify.error(errorTrown);
            }
        })
    }
}
function cerrarViewItemsAll()
{
    $("#myModalViewItemsAll").modal('hide');
}
function VerItemsTodos()
{
    var route = baseUrlOrder + '/GetViewItems/' + $('#selectTRViewItems').val() + '/' + $("#selectStatusViewItemsAll").val();
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'ids':checkedOrderChkb
    };

    jQuery.ajax({
        url:route,
        type:'get',
        data:data,
        dataType:'json',
        success:function(result)
        {

            trAll = $('#selectTRViewItems').val();
            statAll = $("#selectStatusViewItemsAll").val();
            refreshTableAll(result);
            $("#myModalViewItemsAll").modal('hide');
            $("#myModal2").modal('show');
        },
        error:function(result,error,errorTrown)
        {
            alertify.error(errorTrown);
        }
    })

}

function changeGlobal()
{
    if($("#exc_rate_global").val() == "")
        $("#exc_rate_global").val(0);

    var exc_rate_global = parseFloat($("#exc_rate_global").val());

    var route = baseUrlOrder+'/updateGlobal';
    var data = {
        "_token": $("meta[name='csrf-token']").attr("content"),
        'exc_rate_global':exc_rate_global
    };
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {
            alertify.success(result.message);
        }
    })
}

function exc_rate_func(id,profile,selec)
{
    tc = 0;
    alertify.confirm("Tipo de cambio","¿Usar el tipo de cambio del día?",
        function(){
            tc = $("#exc_rate_global").val()
            gotofunc(id,profile,selec);
        },
        function()
        {
            gotofunc(id,profile,selec);
        }).set('labels', {ok:'Si', cancel:'No'});
}

function gotofunc(id,profile,selec)
{
    switch(selec)
    {
        case 1:
            nuevoItem(id,profile);
            break;
        case 2:
            abrirSeleccionTR();
            break;
        case 3:
            DwnldHojaCobroTodos();
            break;
        case 4:
            DwnldItemsTodos();
            break;
    }
}

function exc_rateCobro()
{
    var paymntDetails = $("#paymntDetailsOrder").prop('checked');
    if(paymntDetails)
    {
        flag = 0;
        exc_rate_func(0,0,3);
    }
    else
    {
        DwnldHojaCobroTodos();
    }
}

function exc_rateItems()
{
    var paymntDetails = $("#paymntDetailsItemsAll").prop('checked');
    if(paymntDetails)
    {
        flag = 0;
        exc_rate_func(0,0,4);
    }
    else
    {
        DwnldItemsTodos();
    }
}
