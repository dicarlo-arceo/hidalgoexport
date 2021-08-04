// var hash = window.location.hash;
// // alert(hash);
// if(hash==""){
//     $('.nav-tabs a[href="#profile"]').tab('show');
// }else{
//     $('.nav-tabs a[href="#users"]').tab('show');
// }
// $.fn.editable.defaults.mode = 'inline';
// $.fn.editable.defaults.ajaxOptions = {type:'PUT'};

//******************************************************************************************* PERFILES TAB---------------------------------------------------------------------------------------------------
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

function permprofile() {
    // alert("entre");
    var id = $("#selectProfile").val();
    // alert(id);
    if(id == ""){
        $('input:checkbox').prop('disabled', true);//bloquear
        $('input:checkbox').prop("checked", false);//quitar marca
    }else{
        $('input:checkbox').prop('disabled', false);//desbloquear
        $('input:checkbox').prop("checked", false);//quitar marcas

        // var route = "{{url('admin/permission/')}}/"+id+"/edit";
        var route = baseUrl + "/" + id + "/edit";
        console.log(route);
        $.get(route, function(data){
            console.log("entre",data);
            if(data.length>0){
                $.each( data, function( index, data ){
                    if(data.view == 0){//quitar marcas y bloquear addition,modify,erase
                        $("#add_"+data.fk_section).prop("checked", false);
                        if (data.addition == 1) {
                            $("#add_" + data.fk_section).prop("checked", true);
                        }else{$("#add_" + data.fk_section).prop("checked", false);}
                        if (data.modify == 1) {
                            $("#update_" + data.fk_section).prop("checked", true);
                        }else{ $("#update_" + data.fk_section).prop("checked", false);}

                        if (data.erase == 1) {
                            $("#delete_" + data.fk_section).prop("checked", true);
                        }else{ $("#delete_" + data.fk_section).prop("checked", false);}
                    } else {
                        $("#view_" + data.fk_section).prop("checked", true);//poner su marca
                        if (data.addition == 1) {
                            $("#add_" + data.fk_section).prop("checked", true);
                        }else{$("#add_" + data.fk_section).prop("checked", false);}
                        if (data.modify == 1) {
                            $("#update_" + data.fk_section).prop("checked", true);
                        }else{ $("#update_" + data.fk_section).prop("checked", false);}

                        if (data.erase == 1) {
                            $("#delete_" + data.fk_section).prop("checked", true);
                        }else{ $("#delete_" + data.fk_section).prop("checked", false);}

                    }//fin else de vista es 1
                });//fin each recorrido
            }else{
                $('input:checkbox').prop("checked", false);//quitar marca
            }
        });// fin get data
    }//fin else- area logica
    $.ajax({

    })
};
//ULTIMO VALOR DE LAS RUTAS
// VER = 0
// AGREGAR = 1
// EDITAR = 2
// ELIMINAR = 3

function clickView(section, reference) {
    var id = $("#selectProfile").val();
    if(reference==undefined){
        reference=0;
    }
    var btn = 0;
    var data = {'id':id,
                'section':section,
                'btn':btn,
                'reference':reference,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    // var route = baseUrl + "/" + id + "/"+section+"/"+ 0 +"/"+ reference;
    var route = baseUrl + "/update_store";
    console.log(route);
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function()
        {

        }
    })
};


function clickAdd(section, reference) {
    var id = $("#selectProfile").val();
    if(reference==undefined){
        reference=0;
    }
    var btn = 1;
    var data = {'id':id,
                'section':section,
                'btn':btn,
                'reference':reference,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    // var route = baseUrl + "/" + id + "/"+section+"/"+ 0 +"/"+ reference;
    var route = baseUrl + "/update_store";
    console.log(route);
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {

        }
    })
    // if(id == ""){}else{
    //     $.get(route, function(data){
    //         if(data.length>0){
    //             $("#view_" + data[0]).prop("checked", true);//poner su marca
    //         }
    //     }).fail(function() {
    //         alert("Advertencia No se pudo procesar el permiso de Agregar.");
    //     });
    // }
};

function clickUpdate(section, reference) {
    var id = $("#selectProfile").val();
    if(reference==undefined){
        reference=0;
    }
    var btn = 2;
    var data = {'id':id,
                'section':section,
                'btn':btn,
                'reference':reference,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    // var route = baseUrl + "/" + id + "/"+section+"/"+ 0 +"/"+ reference;
    var route = baseUrl + "/update_store";
    console.log(route);
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {

        }
    })

};


function clickDelete(section, reference) {
    var id = $("#selectProfile").val();
    if(reference==undefined){
        reference=0;
    }
    var btn = 3;
    var data = {'id':id,
                'section':section,
                'btn':btn,
                'reference':reference,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    // var route = baseUrl + "/" + id + "/"+section+"/"+ 0 +"/"+ reference;
    var route = baseUrl + "/update_store";
    console.log(route);
    jQuery.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result)
        {

        }
    })

};
