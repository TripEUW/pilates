"use strict";
var KTDatatablesDataSourceHtml = function() {

	var initTable1 = function() {
		var table = $('#kt_table_1');

		var columnDefsCalc=[];
		columnDefsCalc.push({
			targets: 0,
			orderable: false,
			"visible": false,
			width: 90/(cantRoles+1)+"%"
		});
		 for (let index = 0; index < cantRoles; index++) {

			columnDefsCalc.push({
				targets: index+1,
				orderable: ((index+1>1)?false:true),
				width: 100/(cantRoles+1)+"%"
			});
		 }

		// begin first table
		table.DataTable({
			language: {
			processing: "Procesando el contenido",
			searchPlaceholder: "",
			search: "Buscar Modulo",
			lengthMenu: "Mostrar _MENU_  por página",
            zeroRecords: "Nada encontrado",
            info: "Página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros para mostrar.",
            infoFiltered: "(filtered from _MAX_ total records)"
			  },
			scrollY: false,
			scrollX: true,
			scrollCollapse: true,
			lengthMenu: [5, 10, 25, 50,100],
			pageLength: 10,
			columnDefs:columnDefsCalc,
			order: [[0, 'asc']]
			
		});

	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		},

	};

}();
try {
	jQuery(document).ready(function() {
		KTDatatablesDataSourceHtml.init();
	});
	
} catch (error) {
	location.reload();
}
var actionValue=$("#form_delete_rol").attr('action');
function deleteRol(idRol){

$('#form_delete_rol').attr('action', actionValue+"/"+idRol);
$("#id_delete_rol").val(idRol);
$("#modal_delete_rol").modal('show');

}

function updateRol(idRol,name){
	$("#name_update_rol").val(name);
	$("#id_update_rol").val(idRol);
	$("#modal_update_rol").modal('show');
}

function updatePermission(id_rol,id_module,element,url){
    var check=false;
    if($(element).prop("checked")) {
		check=1;
    } else {
		check=0;
    }
	var data = {
	id_rol: id_rol,
	id_module: id_module,
	_token: $('#token_ajax').val(),
	state: check
	};
	ajaxRequest(url,data);
}

function ajaxRequest(url,data){
	$.ajax({
		url: url,
		type: 'POST',
		data:data,
		success: function(res){
			showToast(0,res.response);
		}
		,
		error: function (xhr, status, error) {
			showToast(3,'Ocurrio un error, intente de nuevo');
		},
	});
}



function showToast(type,msg){
var types=['success','info','warning','error'];
toastr.options = {
closeButton: true,
debug: false,
newestOnTop:true,
progressBar: true,
positionClass: 'toast-top-right',
preventDuplicates: false,
onclick: null,
timeOut: 1500
};
var $toast = toastr[types[type]](msg, ''); // Wire up an event handler to a button in the toast, if it exists
$toastlast = $toast;
if(typeof $toast === 'undefined'){
return;
}
}

 
        