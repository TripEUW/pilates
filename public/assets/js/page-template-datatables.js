"use strict";
var tableGroup=null;
var tableClients=null;
var tableSessionsGroup=null;
var tableSessionsGroupMove=null;
var tabelEmployeeSelected=null;
var tableRoomSelected =null;

var dataForTableGroup=1;
var clientsSelected=[];
var negativeBalanceGlobal=false;
var sessionsMoveSelected=[];


var dataForSessionMove={
    day:null,
    timepicker_start:null,
    timepicker_end:null,
    group_selected:null
};
var DatatableDataGroupServer = function() {
	var initTableGroup = function() {
		// begin first table
		tableGroup = $('#kt_table_groups').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar grupo",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"template/dataTable_group_calendar",
                dataType: "json",
                type: "POST",
                data:function(data) {
                        data._token = $('#token_ajax').val();
                }
            },


			columns: [
                {data: 'id',responsivePriority: -2},
                {data: 'name'},
                {data: 'employee_name'},
                {data: 'room_name'},
                {data: 'type_room'},
				{data: 'status',responsivePriority: -4},
                {data: 'level',responsivePriority: -3},
                {data: 'observation'},
                {data: 'actions',responsivePriority: -1},

			],
			columnDefs: [
                {
                    'targets': 0,
                    "visible": false,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
                {
                    'targets': 5,
                    "visible": false,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){
                        if(data=='Completo'){
                            return `<div class="status-red p-1">`+data+`</div>`;
                           }else if(data=='Vacío'){
                            return `<div class="status-blue p-1">`+data+`</div>`;

                           }else{
                            return `<div class="status-green p-1">`+data+`</div>`;
                           }

                    }
                },
                {
                    'targets': 7,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                    return '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
                    }
                },
				{
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
                        return `<a href="#" onclick='setGroup(`+JSON.stringify(data)+`);' class="btn btn-brand btn-elevate btn-icon-sm p-1"><i class="la la-plus"></i>Elegir</a>`;

					},
				}
            ],
            order: [[0, 'desc']]

        });


	};

	return {
		//main function to initiate the module
		init: function() {
			initTableGroup();
		},

    };
}();

var KTDatatablesDataSourceAjaxServer = function() {
	var initTableClients = function() {
		// begin first table
		tableClients = $('#kt_table_clients').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar cliente",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"management_client/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },

			columns: [
                {data: 'id',responsivePriority: 1},
                {data: 'last_name',responsivePriority: 2},
                {data: 'name',responsivePriority: 3},
                {data: 'suscription'},
                {data: 'tel'},
				{data: 'level'},
				{data: 'sex'},
				{data: 'email'},
				{data: 'status'},
                {data: 'address'},
                {data: 'dni'},
                {data: 'date_of_birth'},
                {data: 'date_register'},
                {data: 'observation'},
                {data: 'sessions_machine'},
                {data: 'sessions_floor'},
                {data: 'sessions_individual'},
				{data: 'actions',  responsivePriority: -1},
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'visible':false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
                {
                    'targets': 4,
                    visible:false
                },
                {
                    'targets': 9,
                    'orderable': true,
                    visible:false,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                    return '<a href="#" onclick="showInfoCellInModal(`Dirección`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
                    }
                },
                {
                    'targets': 10,
                    visible:false
                },
                {
                    'targets': 13,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){
                  return '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
                    }
                },
				{
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {

                        return `
                        <a href="#" onclick='setClientClose(`+JSON.stringify(data)+`);' class="btn btn-brand btn-elevate btn-icon-sm p-1 mb-2 w-90">Elegir</a>
                        <a href="#" onclick='setClient(`+JSON.stringify(data)+`);' class="btn btn-brand btn-elevate btn-icon-sm p-1 w-90 mb-2"><i class="la la-plus d-inline"></i>Agregar</a>
                        `;
					},
				}
            ],
            drawCallback: function( settings ) {

                $('#kt_table_clients').show();
            },
            order: [[0, 'desc']]

        });


	};

	return {

		//main function to initiate the module
		init: function() {
			initTableClients();
		},

    };

}();

var KTDatatablesDataSourceAjaxServerSessionsGroup = function() {
	var initTableSessionsGroup = function() {
		// begin first table
		tableSessionsGroup = $('#kt_table_sessions_group').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"template/data_table_sessions_group",
                dataType: "json",
                type: "POST",
                data:function(data) {
                    data.id_template =  templateSelected;
                    data.day =  $(`#day-edit-previous`).val();
                    data.timepicker_start =  moment($('#timepicker_start_edit').val(), 'H:mm').format('H:mm');
                    data.timepicker_end =  moment($('#timepicker_end_edit').val(), 'H:mm').format('H:mm');
                    data.group_selected = $('#group-id-1-edit').val();
                    data._token = $('#token_ajax').val();
                }
            },
            rowId: "id",
			columns: [
                {data: 'id_session',responsivePriority: 1},
                {data: 'last_name',responsivePriority: 2},
                {data: 'name',responsivePriority: 3},
                {data: 'level'},
                {data: 'observation'},
				{data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 0,
                    "visible": false,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
                {
                    'targets': 4,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){
                  return '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="flaticon-arrows"></i></a>';
                    }
                },
				{
                    targets: -1,
                    title: 'Acciones',
                    orderable: false,
                    'class':'text-center',
					render: function(data, type, full, meta) {

                    return `
                    <a href="#"  onclick='deleteSession([`+data.id_session+`])' class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="flaticon-delete color-green p-1"></i> Eliminar</a>
                    <br>
                    <a href="#"  onclick='setMoveSession([`+data.id_session+`])' class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="fa fa-arrow-alt-circle-right p-1"></i> Mover</a>
                    `;
					},
				}
            ],
            drawCallback: function( settings ) {

             $('#kt_table_sessions_group').show();
            },
            order: [[0, 'desc']]

        });


	};

	return {

		//main function to initiate the module
		init: function() {
			initTableSessionsGroup();
		},

    };

}();

var DatatableDataEmployeeSelectedServer = function() {
	var initTableEmployeeSelected = function() {
		// begin first table
		tabelEmployeeSelected = $('#kt_table_employee_selected').DataTable({
            lengthMenu: [[5,10, 25, 50,100, -1], [5,10, 25, 50,100, "Todo"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 5,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar empleado",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"dashboard/dataTable_employee_select",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },

			columns: [
                {data: 'id',responsivePriority: -1},
                {data: 'name'},
                {data: 'rol_name'},
                {data: 'status'},
                {data: 'status_assign'},
				{data: 'n_groups'},
                {data: 'actions',responsivePriority: -1},

			],
			columnDefs: [
                {
                    'targets': 0,
                    'orderable': true,
                },
                {
                    'targets': 4,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                    if(data=='Sin grupo'){
                     return `<div class="status-red p-1">`+data+`</div>`;
                    }else if(data=='Asignado'){
                     return `<div class="status-green p-1">`+data+`</div>`;
                    }else{
                    return `<div class="status-gray p-1">Sin status</div>`;
                    }
                    }
                },
                {
                    'targets': 5,
                    'orderable': true,
                    'class':'text-center',
                },
                {
                    'targets': -1,
                    'orderable': false,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                   return `<a href="#" onclick='setEmployee(`+JSON.stringify(data)+`);' class="btn btn-brand btn-elevate btn-icon-sm p-1"><i class="la la-plus"></i>Elegir</a>`;
                    }
                }
            ],
            drawCallback: function( settings ) {

                $('#kt_table_employee_selected').show();
               },
            order: [[0, 'desc']]
        });

	};

	return {
		//main function to initiate the module
		init: function() {
			initTableEmployeeSelected();
		},

    };
}();

var DatatableDataRoomSelectedServer = function() {
	var initTableRoomSelected = function() {
		// begin first table
		tableRoomSelected = $('#kt_table_room_selected').DataTable({
            lengthMenu: [[5,10, 25, 50,100, -1], [5,10, 25, 50,100, "Todo"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 5,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar sala",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"management_room_group/dataTable_room_select",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },

			columns: [
                {data: 'id',responsivePriority: -2},
                {data: 'name'},
                {data: 'type_room'},
                {data: 'capacity'},
                {data: 'observation'},
                {data: 'actions',responsivePriority: -1},

			],
			columnDefs: [
                {
                    'targets': 0,
                    'orderable': true,
                },
                {
                    'targets': 4,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                    return '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="flaticon-arrows"></i></a>';
                    }
                },
                {
                    'targets': -1,
                    'orderable': false,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                   return `<a href="#" onclick='setRoomForm(`+JSON.stringify(data)+`);' class="btn btn-brand btn-elevate btn-icon-sm p-1"><i class="la la-plus"></i>Elegir</a>`;
                    }
                }
            ],
            order: [[0, 'desc']]
        });
	};
	return {
		//main function to initiate the module
		init: function() {
			initTableRoomSelected();
		},

    };
}();

var KTDatatablesDataSourceAjaxServerSessionsGroupMove = function() {
	var initTableSessionsGroupMove = function() {
		// begin first table
		tableSessionsGroupMove = $('#kt_table_sessions_group_move').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom:  'ftpr',
            pageLength: -1,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Ninguna sesión en este grupo.",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"template/data_table_sessions_group",
                dataType: "json",
                type: "POST",
                data:function(data) {
                    data.day=dataForSessionMove.day;
                    data.timepicker_start=moment(dataForSessionMove.timepicker_start, 'H:mm').format('H:mm');
                    data.timepicker_end=moment(dataForSessionMove.timepicker_end, 'H:mm').format('H:mm');
                    data.group_selected=dataForSessionMove.group_selected;
                    data.id_template=templateSelected;
                    data._token = $('#token_ajax').val();

                }
            },
            rowId: "id",
			columns: [
                {data: 'id_session',responsivePriority: 1},
                {data: 'last_name',responsivePriority: 2},
                {data: 'name',responsivePriority: 2},
                {data: 'level'},
                {data: 'observation'},
				{data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 0,
                    "visible": true,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
                {
                    'targets': 1,
                    "visible": false
                },
                {
                    'targets': 2,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                  return `<div data-id-session="${full.id_session}" class="drag-move-session">${full.name} ${full.last_name}</div>`;
                    }
                },
                {
                    'targets': 3,
                    "visible": false
                },
                {
                    'targets': 4,
                    'orderable': true,
                    "visible": false,
                    'class':'text-center',
                    'render': function (data, type, full, meta){
                  return '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="flaticon-arrows"></i></a>';
                    }
                },
				{
                    targets: -1,
                    title: 'Acciones',
                    orderable: false,
                    'class':'text-center',
					render: function(data, type, full, meta) {

                    return `
                    <a href="#"  onclick='deleteSession([`+data.id_session+`])' class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="flaticon-delete color-green p-1"></i> Eliminar</a>
                   <br>
                    <a href="#"  onclick='setMoveSession([`+data.id_session+`])' class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="fa fa-arrow-alt-circle-right p-1"></i> Mover</a>
                    `;
					},
				}
            ],
            drawCallback: function( settings ) {

                $(".drag-move-session").draggable(
                    {
                        //snap: ".event-session",
                        //snapMode: 'inner',
                        helper: 'clone',
                        appendTo: 'body',
                        zIndex: 1500,
                        revert: function (event, ui) {
                            $(this).data("uiDraggable").originalPosition = {
                                top: 0,
                                left: 0
                            };
                            return !event;
                        }
                    }
                );

             $('#kt_table_sessions_group_move').show();
            },
            order: [[0, 'desc']]

        });


	};

	return {

		//main function to initiate the module
		init: function() {
			initTableSessionsGroupMove();
		},

    };

}();


jQuery(document).ready(function() {

DatatableDataGroupServer.init();
KTDatatablesDataSourceAjaxServer.init();
KTDatatablesDataSourceAjaxServerSessionsGroup.init();
KTDatatablesDataSourceAjaxServerSessionsGroupMove.init();
DatatableDataEmployeeSelectedServer.init();
DatatableDataRoomSelectedServer.init();

    $('#select-all-sessions-move').on('click', function () {
        var rows = tableSessionsGroupMove.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    tableSessionsGroupMove.on('draw', function () {
        if ($('#select-all-sessions-move').is(":checked")) {
            var rows = tableSessionsGroupMove.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', true);
        }
    });

});

var hideAddGroup=true;
function showModalAddGroup(mode=true){
    hideAddGroup=mode;

    $("#group-name-add").val("");
    $("#group-level-add").val("");
    $("#group-id-room-add").val("");
    $("#group-name-room-add").val("");
    $("#group-observation-add").val("");
    $('#modal_add_group_session').modal('hide');
    $('#modal_add_group').modal('show');
}

$('#modal_add_group').on('hide.bs.modal', function(e) {
    if(hideAddGroup)
    $('#modal_add_group_session').modal('show');
});

$('#timepicker_start1, #timepicker_end1').timepicker({
    minuteStep: 1,
    showSeconds: false,
    showMeridian: false,
    disableFocus: false,
    defaultTime: 'current',
    modalBackdrop: false,
    appendWidgetTo: '#container-timepicker-fix'
}).on('show.timepicker', function(e) {

});

$('#timepicker_end_edit').timepicker({
    minuteStep: 1,
    showSeconds: false,
    showMeridian: false,
    disableFocus: false,
    defaultTime: 'current',
    modalBackdrop: false,
    appendWidgetTo: '#container-timepicker-end-edit-fix'
}).on('show.timepicker', function(e) {

});

$('#timepicker_start_edit').timepicker({
    minuteStep: 1,
    showSeconds: false,
    showMeridian: false,
    disableFocus: false,
    defaultTime: 'current',
    modalBackdrop: false,
    appendWidgetTo: '#container-timepicker-start-edit-fix'
}).on('show.timepicker', function(e) {

});

$('#timepicker_start_edit_previous,#timepicker_end_edit_previous').timepicker({
    minuteStep: 1,
    showSeconds: false,
    showMeridian: false,
    disableFocus: false,
    defaultTime: 'current',
    modalBackdrop: false
});



function showInfoCellInModal(title,content){
    $('#modal-info-cell-title').text(((title)? title  : ''));
    $('#modal-info-cell-content').text(((content)? content  : ''));
    $('#modal-info-cell').modal('show');

}

// modal select employee
function showModalSelectEmployee(){
$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').hide();
$('#modal_select_employee').modal('show');
tabelEmployeeSelected.search("");
tabelEmployeeSelected.ajax.reload();
}

var defaultRouteImage=$('#img-change-profile').attr("src");
function showModalAddEmployee(){
$('#img-change').val(null);
$('#img-change-profile').attr("src",defaultRouteImage);
$('#recipient-name').val('');
$('#recipient-last-name').val('');
$('#recipient-email').val('');
$('#recipient-password').val('');
$('#recipient-re-password').val('');
$('#recipient-date-of-birth').val('');
$('#recipient-dni').val('');
$('#recipient-address').val('');
$('#recipient-tel').val('');
$('#observation').val('');



$('#modal_add_employee').modal('show');

}

$('#modal_select_employee').on('show.bs.modal', function(e) {
tabelEmployeeSelected.responsive.recalc();
setTimeout(function() {
$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').show();
tabelEmployeeSelected.responsive.recalc();
}, 300);
});
//end modal select employee

// modal select room
function showModalSelectRoom(){
    $('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').hide();
    $('#modal_select_room').modal('show');
    tableRoomSelected.search("");
    tableRoomSelected.ajax.reload();
}

$('#modal_select_room').on('show.bs.modal', function(e) {
tableRoomSelected.responsive.recalc();
setTimeout(function() {
$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').show();
tableRoomSelected.responsive.recalc();
}, 300);
});

function setRoomForm(data){
$('#group-name-room-add').val(data.name+"-"+data.type_room+"-"+data.capacity);
$('#group-id-room-add').val(data.id);
$('#modal_select_room').modal('hide');
}
//end modal select room


// modal select client
function showModalSelectClient(){
    if(groupSelected!=null){
        $('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').hide();
        $('#kt_table_clients_modal').modal('show');
        tableClients.search("");
        tableClients.ajax.reload();
    }else{
        showToast(1,"Seleccione un grupo para poder agregar clientes.");
    }

}

$('#kt_table_clients_modal').on('show.bs.modal', function(e) {
    tableClients.responsive.recalc();
    setTimeout(function() {
        $('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').show();
        tableClients.responsive.recalc();
    }, 300);
});
//end  modal select client

//modal select group
function showModalSelectGroup(){
$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').hide();
$("#kt_table_groups_modal").modal("show");
tableGroup.search("");
tableGroup.ajax.reload();
}


$('#kt_table_groups_modal').on('show.bs.modal', function(e) {
tableGroup.responsive.recalc();
setTimeout(function() {
$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').show();
tableGroup.responsive.recalc();
}, 300);
});
//end modal select group

//modal edit group sessions
function showModalEditGroupSessions(){
$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').hide();
$("#modal_edit_group_session").modal("show");
tableSessionsGroup.search("");
tableSessionsGroup.ajax.reload();
}

$('#modal_edit_group_session').on('show.bs.modal', function(e) {
tableSessionsGroup.responsive.recalc();
setTimeout(function() {
$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').show();
tableSessionsGroup.responsive.recalc();
}, 300);
});
//end edit group sessions

var groupSelected=null;
var clientSelected=null;
var employeeSelectedId=null;
var employeeSelectedName=null;

function setGroup(data){

groupSelected=data;
employeeSelectedId=data.employee_id;
employeeSelectedName=data.employee_name;

$('#employee-name-1').val(data.employee_name);
$('#group-name-1').val(data.name);
$('#group-id-1').val(data.id_group);

$('#group-name-edit').val(data.name);
$('#employee-name-edit').val(data.employee_name);

$('#room-name-edit').val(data.room_name);
$('#room-type-edit').val(data.type_room);




$("#kt_table_groups_modal").modal("hide");
checkLevelModal('group');
}

function setEmployee(data){

employeeSelectedId=data.id;
employeeSelectedName=data.name;
$('#employee-name-1').val(data.name);
$('#employee-name-edit').val(data.name);

$("#modal_select_employee").modal("hide");

}

function setClient(data){

if(groupSelected!=null){

var flagExist=true;
clientsSelected.forEach(client => {if(client.id==data.id)flagExist=false;});

if(flagExist){
    clientSelected=data;
    data.negative_balance = negativeBalanceGlobal;
    clientsSelected.push(data);
    $('#client-name-1').val(data.name+" "+data.last_name);
    $('#client-id-1').val(data.id);

    $('#client-name-2').val(data.name+" "+data.last_name);
    $('#client-id-2').val(data.id);

    //$("#kt_table_clients_modal").modal("hide");
    checkLevelModal('client');
}else{
    showToast(1,"El cliente ya fue agregado.");

}
}else{
    showToast(1,"Seleccione un grupo para poder agregar clientes.");
}
}

function setClientClose(data) {
    if (groupSelected != null) {

        var flagExist = true;
        clientsSelected.forEach(client => { if (client.id == data.id) flagExist = false; });

        if (flagExist) {
            clientSelected = data;
            data.negative_balance = negativeBalanceGlobal;
            clientsSelected.push(data);
            $('#client-name-1').val(data.name + " " + data.last_name);
            $('#client-id-1').val(data.id);

            $('#client-name-2').val(data.name + " " + data.last_name);
            $('#client-id-2').val(data.id);

            $("#kt_table_clients_modal").modal("hide");
            checkLevelModal('client');
        } else {
            showToast(1, "El cliente ya fue agregado.");
        }
    } else {
        showToast(1, "Seleccione un grupo para poder agregar clientes.");
    }
}

function reloadTableSelectedClients() {
$("#container-table-clients-add").html("");
$("#container-table-clients-add-2").html("");
var htmlTmp = ``;
clientsSelected.forEach(client => {
htmlTmp += `<tr><td>${client.name + " " + client.last_name}</td><td style="width: 55px;" class="text-center">
<a href="#" onclick="removeClientFromList(${client.id})" class="btn btn-brand btn-elevate btn-icon-sm p-1"><i class="flaticon2-delete"></i>Quitar</a>
</td></tr>`;
});

if(htmlTmp==``)
htmlTmp=`<tr><td colspan="2" class="text-center"> Ningún cliente agregado. </td></tr>`;
var htmlTable=`
<div class="container">
<table class="table-bordered table-hover table-data-custom w-100">
<thead>
<tr>
<th>Cliente</th>
<th style="width: 55px;">Quitar</th>
</tr>
</thead>
<tbody>
${htmlTmp}
</tbody>
</table>
</div>
`;

$("#container-table-clients-add").html(htmlTable);
$("#container-table-clients-add-2").html(htmlTable);

}

function checkLevelModal(type){
if(groupSelected!=null && clientSelected!=null){

if(groupSelected.level != clientSelected.level){
$("#type-level-check").val(type);
$("#modal_check_level").modal("show");
}else{
 checkBalance();
}
}
}

function acceptAndCloseLevelDiff(){
checkBalance();
}

function acceptAndCloseBalanceDiff(negativeBalance=false){
negativeBalanceGlobal=negativeBalance;
showToast(0,"El cliente fue selecionado.");
setNegativeBalanceClient();
reloadTableSelectedClients();
}

function printListClientsAdd(){
 console.log(JSON.stringify(clientsSelected));
}

function setNegativeBalanceClient(){

    clientsSelected.forEach(client => {
        if(clientSelected.id==client.id){

        client.negative_balance=negativeBalanceGlobal;
        }
    });


}

var acceptLevelStatus=true;
function acceptLevelDiff(response){
    negativeBalanceGlobal=false;
    acceptLevelStatus=response;
    var type=$("#type-level-check").val();
    removeClientFromList(clientSelected.id);
    if(type=="client"){
        clientSelected=null;
        $('#client-id-1').val("");
        $('#client-name-1').val("");
        $('#client-id-2').val("");
        $('#client-name-2').val("");

    }else{
        groupSelected=null;
        clientSelected=null;
        $('#group-id-1').val("");
        $('#group-name-1').val("");
    }
}
//$('#modal_check_level').on('hide.bs.modal', function(e) { acceptLevelDiff();  });
// --------------------------------
function editHoursSession(){
  var timepicker_start=$("#timepicker_start1").val();
  var timepicker_end= $("#timepicker_end1").val();
    var observation= $("#data-hour").val();

showOverlay();
$.ajax({
    url: "template/edit_hour_session",
    type: 'POST',
    data: {
        id_template:templateSelected,
        timepicker_start: moment(timepicker_start, 'H:mm').format('HH:mm'),
        timepicker_end:  moment(timepicker_end, 'H:mm').format('HH:mm'),
        observation:observation,
        _token: $('#token_ajax').val()
    },
    success: function (res) {

        if (res.error != false) {
            hideOverlay();

            sendErrorsShow(res.error);

        } else {
            groupSelected=null;
            clientSelected=null;
            clientsSelected=[];
            employeeSelectedId=null;
            employeeSelectedName=null;

            $('#client-id-1').val("");
            $('#client-name-1').val("");
            $('#group-id-1').val("");
            $('#group-name-1').val("");
            $('#employee-name-1').val("");

            $('#group-id-2').val("");
            $('#group-name-2').val("");

            $("#modal_add_group_session").modal("hide");
            $("#modal_add_new_session").modal("hide");
            getDataByCalendar();
            showToast(0,res.success);

        }
    },
    error: function (xhr, status, error) {
        hideOverlay();
        console.log(JSON.stringify(xhr));
        sendErrorsShow([error]);
    },
});
}
// -----------------------------------------------------------------------------
function addGroupSession(){
    var timepicker_start=$("#timepicker_start1").val();
    var timepicker_end= $("#timepicker_end1").val();
    var observation= $("#group-observation").val();


    var serieDaysSelected=[];
    serialDays.forEach(day => { if($(`#check-${day}`).prop('checked'))serieDaysSelected.push(day); });



showOverlay();
$.ajax({
    url: "template/add_group_session",
    type: 'POST',
    data: {
        id_template:templateSelected,
        group_selected: groupSelected,
        client_selected: clientSelected,
        clients_selected: clientsSelected,
        negative_balance:negativeBalanceGlobal,
        timepicker_start: moment(timepicker_start, 'H:mm').format('HH:mm'),
        timepicker_end:  moment(timepicker_end, 'H:mm').format('HH:mm'),
        observation:observation,
        serie_days_selected:serieDaysSelected,
        _token: $('#token_ajax').val()
    },
    success: function (res) {

        if (res.error != false) {
            hideOverlay();

            sendErrorsShow(res.error);

        } else {
            groupSelected=null;
            clientSelected=null;
            clientsSelected=[];
            employeeSelectedId=null;
            employeeSelectedName=null;

            $('#client-id-1').val("");
            $('#client-name-1').val("");
            $('#group-id-1').val("");
            $('#group-name-1').val("");
            $('#employee-name-1').val("");

            $('#group-id-2').val("");
            $('#group-name-2').val("");

            $("#modal_add_group_session").modal("hide");
            $("#modal_add_new_session").modal("hide");
            getDataByCalendar();
            showToast(0,res.success);

        }
    },
    error: function (xhr, status, error) {
        hideOverlay();
        console.log(JSON.stringify(xhr));
        sendErrorsShow([error]);
    },
});
}

function addNewSessionGroup(){

dataForNewSession.observation=$("#group-observation").val();
dataForNewSession.group_selected=groupSelected;
dataForNewSession.client_selected=clientSelected;
dataForNewSession.clients_selected=clientsSelected;


var serieDaysSelected=[];
serialDays.forEach(day => { if($(`#check-${day}`).prop('checked'))serieDaysSelected.push(day); });
dataForNewSession.serie_days_selected=serieDaysSelected;

showOverlay();
$.ajax({
    url: "template/add_new_session",
    type: 'POST',
    data:dataForNewSession,
    success: function (res) {


        if (res.error != false) {
            hideOverlay();
            sendErrorsShow(res.error);
        } else {
            groupSelected=null;
            clientSelected=null;
            employeeSelectedId=null;
            employeeSelectedName=null;
            clientsSelected=[];

            dataForNewSession={
                group_selected: '',
                client_selected: '',
                clients_selected:[],
                date_start: '',
                timepicker_start: '',
                timepicker_end: '',
                observation:'',
                serie_days_selected:[],
                day:null,
                id_template:null,
                _token: $('#token_ajax').val()
            };

            $('#group-id-2').val("");
            $('#group-name-2').val("");


            $("#modal_add_new_session").modal("hide");

            getDataByCalendar();
            showToast(0,res.success);
        }
    },
    error: function (xhr, status, error) {

        hideOverlay();
        console.log(JSON.stringify(xhr));
        sendErrorsShow([error]);
    },
});
}

function createGroup(){
    showOverlay();
$.ajax({
    url: "dashboard/create_group",
    type: 'POST',
    data:{
        name:$("#group-name-add").val(),
        level:$("#group-level-add").val(),
        id_room:$("#group-id-room-add").val(),
        observation:$("#group-observation-add").val(),
        _token: $('#token_ajax').val()
    },
    success: function (res) {


        if (!res.response) {
            hideOverlay();
            sendErrorsShow(res.error);
        } else {
            hideOverlay();



            groupSelected= {id_group:res.group.id,id:res.group.id};

           $('#group-name-1').val(res.group.name);
           $('#group-id-1').val(groupSelected);

           $('#group-name-edit').val(res.group.name);


            $("#modal_add_group").modal("hide");
            $("#group-name-add").val("");
            $("#group-level-add").val("");
            $("#group-id-room-add").val("");
            $("#group-name-room-add").val("");

            $("#group-observation-add").val("");

            showToast(0,res.message);
        }
    },
    error: function (xhr, status, error) {

        hideOverlay();
        console.log(JSON.stringify(xhr));
        sendErrorsShow([error]);
    },
});
}


function checkBalance(){

    showOverlay();

    $.ajax({
        url: "dashboard/check_balance",
        type: 'POST',
        data: {
            id_group: groupSelected.id_group,
            id_client: clientSelected.id,
            _token: $('#token_ajax').val()
        },
        success: function (res) {

            hideOverlay();
            if (res.success == true) {
                if(res.error!=false && res.error!=""){
                    //mostrar canjeo
                    $("#modal_check_level").modal("hide");
                    $("#modal-check-balance").modal("show");
                    $("#modal-check-balance-text").html(res.error);
                }else{
                    showToast(0,"El cliente fue selecionado.");
                    setNegativeBalanceClient()
                    reloadTableSelectedClients();
                }

            } else {

                $("#modal_check_level").modal("hide");
                $("#modal-check-balance-2").modal("show");
                $("#modal-check-balance-text-2").html(res.error);

                   /*
                removeClientFromList(clientSelected.id);

             //groupSelected=null;
                clientSelected=null;
                $("#modal-check-balance").modal("hide");
                $('#client-id-1').val("");
                $('#client-name-1').val("");
                //$('#group-id-1').val("");
               // $('#group-name-1').val("");

                $('#client-id-2').val("");
                $('#client-name-2').val("");
                //$('#modal_add_new_session').modal("hide");
                sendErrorsShow([res.error]);
                */
            }
        },
        error: function (xhr, status, error) {
            hideOverlay();
            sendErrorsShow([error]);
        },
    });
}

function removeClientFromList(id){


    for(var i = clientsSelected.length - 1; i >= 0; i--) {
        if(clientsSelected[i].id == id) {
         clientsSelected.splice(i, 1);
        }
    }

    reloadTableSelectedClients();

}

function acceptBalanceDiff(){
removeClientFromList(clientSelected.id);
//groupSelected=null;
clientSelected=null;

$("#type-level-check").val("");
$('#client-id-1').val("");
$('#client-name-1').val("");

//$('#group-id-1').val("");
//$('#group-name-1').val("");

$('#client-id-2').val("");
$('#client-name-2').val("");
//$('#modal_add_new_session').modal("hide");
$('#modal_check_level').modal("hide");

}


function sendSuccess(success) {
successAlert = `<div class="alert alert-outline-success fade show p-1 mb-0" role="alert">
<div class="alert-icon"><i class="flaticon2-accept"></i></div>
<div class="alert-text">
${success}
</div>
<div class="alert-close">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true"><i class="la la-close"></i></span>
</button>
</div>
</div>
<br>`;
$("#alerts-ajax").append(successAlert);
}

function sendErrorsShow(errors) {
    var errorsHtml = "";
    errors.forEach(error => {
        errorsHtml += `<li class="mt-2" style="text-transform: initial;">` + error + `</li>`;
    });
    errorsHtml =
        `<div class="alert-text">
<ul class="float-left text-left">
` + errorsHtml + `
</ul>
</div>`;

    $("#modal_errors").modal("show");
    $("#content-errors").html(errorsHtml);

}

function showToast(type,msg,time=1500){
var types=['success','info','warning','error'];
toastr.options = {
closeButton: true,
debug: false,
newestOnTop:true,
progressBar: true,
positionClass: 'toast-top-right',
preventDuplicates: false,
onclick: null,
timeOut: time
};
var $toast = toastr[types[type]](msg, ''); // Wire up an event handler to a button in the toast, if it exists
var $toastlast = $toast;
if(typeof $toast === 'undefined'){
return;
}
}

var idRowDelete=null;
$('#kt_table_sessions_group').on( 'click', 'tr', function () {
 idRowDelete = tableSessionsGroup.row(this).id();
} );



var selectedSessionsForDelete=[];
function deleteSession(data){
    selectedSessionsForDelete=data;
$("#modal_delete_session").modal("show");
}

function confirmDeleteSession(){
showOverlay();
$.ajax({
url: "template/delete_session",
type: 'POST',
data: {
id: selectedSessionsForDelete,
_token: $('#token_ajax').val()
},
success: function (res) {
getDataByCalendar();
selectedSessionsForDelete=[];
if (res.status == true) {
tableSessionsGroup.row("#"+idRowDelete).remove().draw();
hideShowPanelLeft(true,true);
showToast(0,res.response);
}else{
showToast(3,res.response);
}
},
error: function (xhr, status, error) {
    selectedSessionsForDelete=[];
hideOverlay();
showToast(3,error);
},
});
}

function editGroupSession(){

var id_group= $("#group-id-1-edit").val();
var day=null;

serialDays.forEach(dayF => { if($(`#radio-${dayF}`).prop('checked')){day=dayF} });

var timepicker_start= $("#timepicker_start_edit").val();
var timepicker_end= $("#timepicker_end_edit").val();


var day_previous=$("#day-edit-previous").val();
var timepicker_start_previous=$("#timepicker_start_edit_previous").val();
var timepicker_end_previous=$("#timepicker_end_edit_previous").val();



showOverlay();
$.ajax({
url: "template/edit_group_sessions",
type: 'POST',
data:{
id_group: groupSelected.id_group,
id_group_previous: id_group,
employee_selected: employeeSelectedId,
day: day,
timepicker_start: moment(timepicker_start, 'HH:mm').format('HH:mm'),
timepicker_end: moment(timepicker_end, 'HH:mm').format('HH:mm'),
day_previous:day_previous,
timepicker_start_previous:moment(timepicker_start_previous, 'HH:mm').format('HH:mm'),
timepicker_end_previous:moment(timepicker_end_previous, 'HH:mm').format('HH:mm'),
id_template:templateSelected,
_token: $('#token_ajax').val()
},
success: function (res) {

if (res.error != false) {
 hideOverlay();
sendErrorsShow(res.error);
} else {
    getDataByCalendar();
showToast(0,res.success);
}
},
error: function (xhr, status, error) {

hideOverlay();
console.log(JSON.stringify(xhr));
sendErrorsShow([error]);
},
});
}

function editGroupSessionForDrag(id_group,day,timepicker_start,timepicker_end,day_previous,timepicker_start_previous,timepicker_end_previous){

return new Promise((resolve,reject) => {

showOverlay();
$.ajax({
url: "template/edit_group_sessions_drag",
type: 'POST',
data:{
id_template: templateSelected,
id_group: id_group,
day: day,
timepicker_start: timepicker_start,
timepicker_end: timepicker_end,
day_previous:day_previous,
timepicker_start_previous:timepicker_start_previous,
timepicker_end_previous:timepicker_end_previous,
_token: $('#token_ajax').val()
},
success: function (res) {
    reloadContextMenu();
if (res.error != false) {
hideOverlay();
sendErrorsShow(res.error);
reject(res.error);
} else {
resolve(res.success);
}

},
error: function (xhr, status, error) {
    reloadContextMenu();
hideOverlay();
console.log(JSON.stringify(xhr));
sendErrorsShow([error]);
reject(error);
},
});

});
}

function setMoveSessions(){


    sessionsMoveSelected=[];
    // Iterate over all checkboxes in the table
    tableSessionsGroupMove.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-clients-delete').append(
                   sessionsMoveSelected.push(this.value)
             );
          }
       //}
    });

    if(sessionsMoveSelected.length<=0){
        showToast(1,'Ninguna sesión seleccionada.',1500);
    }else{

        $("#modal_edit_group_session").modal("hide");
        showToast(0,'Ahora click derecho sobre un grupo de sesiones y elegir la opción mover aquí.',6000);
    }


}

function setMoveSession(idsSession){
    sessionsMoveSelected=idsSession;

    $("#modal_edit_group_session").modal("hide");

    showToast(0,'Ahora click derecho sobre un grupo de sesiones y elegir la opción mover aquí.',6000);
}

function moveSessions(day,timepicker_start,timepicker_end,id_group){

showOverlay();
$.ajax({
url: "template/move_sessions",
type: 'POST',
data:{
sessions_selected:sessionsMoveSelected,
id_template:templateSelected,
id_group: id_group,
day: day,
timepicker_start: timepicker_start,
timepicker_end: timepicker_end,
_token: $('#token_ajax').val()
},
success: function (res) {

if (res.status == false) {
hideOverlay();
sendErrorsShow(res.response);
}else{
sessionsMoveSelected=[];
getDataByCalendar();
hideShowPanelLeft(true,true);
showToast(0,res.response);
}
},
error: function (xhr, status, error) {
hideOverlay();
console.log(JSON.stringify(xhr));
sendErrorsShow([error]);
},
});

}

function lockAddGroupSession(date_start,timepicker_start,timepicker_end,status){

showOverlay();
$.ajax({
url: "dashboard/lock_group_session_add",
type: 'POST',
data:{
status:status,
date_start: date_start,
timepicker_start: timepicker_start,
timepicker_end: timepicker_end,
_token: $('#token_ajax').val()
},
success: function (res) {

if (res.status == false) {
hideOverlay();
sendErrorsShow(res.response);
}else{
    getDataByCalendar();
showToast(0,res.response);
}
},
error: function (xhr, status, error) {
hideOverlay();
console.log(JSON.stringify(xhr));
sendErrorsShow([error]);
},
});

}





function hideShowPanelLeft(status=true,ignore=false){
    if(status && !ignore){
        $("#kt_demo_panel").addClass('kt-demo-panel--on');

    }else{
        if(!ignore)
        $("#kt_demo_panel").removeClass('kt-demo-panel--on');
    }

    if(status || ignore){

        $('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').hide();

        tableSessionsGroupMove.search("");
        tableSessionsGroupMove.ajax.reload();
        tableSessionsGroupMove.responsive.recalc();
        setTimeout(function () {
            $('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').show();
            tableSessionsGroupMove.responsive.recalc();
        }, 300);
    }
}


function loadSessionsGroupModal(element){
    var key = $(element).attr("data-group-key");
    var day = dataCalendarSessionByAccess[key].day;
    var timepicker_start = dataCalendarSessionByAccess[key].time_start;
    var timepicker_end = dataCalendarSessionByAccess[key].time_end;
    var group_selected = dataCalendarSessionByAccess[key].group.id;

    dataForSessionMove.day=day;
    dataForSessionMove.timepicker_start=timepicker_start;
    dataForSessionMove.timepicker_end=timepicker_end;
    dataForSessionMove.group_selected=group_selected;


    hideShowPanelLeft(true);
}

var selected_delete_day=null;
var selected_delete_timepicker_start=null;
var selected_delete_timepicker_end=null;
var selected_delete_id_group=null;

function deleteGroupSessions(day,timepicker_startp,timepicker_endp,id_group){
    selected_delete_day=day;
    selected_delete_timepicker_start=timepicker_startp;
    selected_delete_timepicker_end=timepicker_endp;
    selected_delete_id_group=id_group;
    $("#modal_delete_group_sessions").modal('show');
}
function confirmDeleteGroupSessions(){
$("#modal_delete_group_sessions").modal('hide');

showOverlay();
$.ajax({
url: "template/delete_group_sessions",
type: 'POST',
data:{
id_group: selected_delete_id_group,
day: selected_delete_day,
timepicker_start: selected_delete_timepicker_start,
timepicker_end: selected_delete_timepicker_end,
id_template:templateSelected,
_token: $('#token_ajax').val()
},
success: function (res) {

if (res.status == false) {
    hideOverlay();
sendErrorsShow([res.response]);
} else {
    getDataByCalendar();
showToast(0,res.response);
}

selected_delete_day=null;
selected_delete_timepicker_start=null;
selected_delete_timepicker_end=null;
selected_delete_id_group=null;
},
error: function (xhr, status, error) {
selected_delete_day=null;
selected_delete_timepicker_start=null;
selected_delete_timepicker_end=null;
selected_delete_id_group=null;
hideOverlay();
console.log(JSON.stringify(xhr));
sendErrorsShow([error]);
},
});
}
var templatesForDelete=[];
function deleteActualTemplate(){
templatesForDelete=[templateSelected];
$("#modal_delete_template").modal("show");
}

function deleteTemplates(){
$("#modal_delete_template").modal("hide");

showOverlay();
$.ajax({
url: "template/delete_template",
type: 'POST',
data:{
templates:templatesForDelete,
_token: $('#token_ajax').val()
},
success: function (res) {

if (res.status == false) {
hideOverlay();
sendErrorsShow([res.response]);
} else {
//getDataByCalendar();
showToast(0,res.response);
location.reload();
}
},
error: function (xhr, status, error) {
hideOverlay();
console.log(JSON.stringify(xhr));
sendErrorsShow([error]);
},
});

}

function enableDisableTemplate(){
showOverlay();
$.ajax({
url: "template/enable_disable",
type: 'POST',
data:{
template_selected:templateSelected,
_token: $('#token_ajax').val()
},
success: function (res) {

if (res.status == false) {
hideOverlay();
sendErrorsShow([res.response]);
} else {
getDataByCalendar();
showToast(0,res.response);
}
},
error: function (xhr, status, error) {
hideOverlay();
console.log(JSON.stringify(xhr));
sendErrorsShow([error]);
},
});
}

function renameTemplate() {
    showOverlay();
    $.ajax({
        url: "template/rename",
        type: 'POST',
        data: {
            template_selected: templateSelected,
            name:$("#template-name-edit").val(),
            _token: $('#token_ajax').val()
        },
        success: function (res) {

            if (res.status == false) {
                hideOverlay();
                sendErrorsShow([res.response]);
            } else {
                $("#modal_rename_template").modal('hide');
                getDataByCalendar();
                showToast(0, res.response);
            }
        },
        error: function (xhr, status, error) {
            hideOverlay();
            console.log(JSON.stringify(xhr));
            sendErrorsShow([error]);
        },
    });
}

$('#form-add-employee').on('submit',(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    //formData.append('picture_upload', $('#img-change')[0].files[0]);
    showOverlay();
    $.ajax({
        type:'POST',
        url: $(this).attr('action'),
        data:formData,
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        success:function(res){

            hideOverlay();

            if (!res.response) {
                hideOverlay();
                sendErrorsShow(res.error);
            } else {
                hideOverlay();

                employeeSelectedId = res.employee.employee_id;
                employeeSelectedName = res.employee.employee_name;

                $('#employee-name-1').val(employeeSelectedName);
                $('#employee-name-edit').val(employeeSelectedName);
                $("#modal_add_employee").modal("hide");

                showToast(0,res.message,12000);
            }
        },
        error: function (xhr, status, error) {
            hideOverlay();
            console.log(JSON.stringify(xhr));
            sendErrorsShow([error]);
        }
    });
}));

function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img-change-profile').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img-change").change(function(){
    readURL2(this);
});


////////////////////////////////////////////////////////////////protected columns
var tablesForProtectedColumns=[
    {name:"kt_table_clients",colums_protected:[4,9,10],status:false}
    ];
function showHiddenFields(tableId,btn){

    showOverlay();
    $.ajax({
        url: baseUrl+"/administration_config/check_status_hide_attr",
        type: 'POST',
        data: {
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            hideOverlay();
            if (res.status == true) {

                tablesForProtectedColumns.forEach(table => {


                    if(table.name==tableId && table.status==false){
                        var tableTmp= $(`#${table.name}`).DataTable();
                        table.status=true;
                        $(btn).html("Ocultar campos protegidos");

                        table.colums_protected.forEach(columnNum => {
                            var columShow=tableTmp.column(columnNum)
                            columShow.visible(true);
                        });

                        tableTmp.search("");
                        tableTmp.ajax.reload();
                        tableTmp.responsive.recalc();

                        showToast(0,"Ahora pude ver los campos protegidos");
                    }else if(table.name==tableId && table.status==true){
                        var tableTmp= $(`#${table.name}`).DataTable();
                        table.status=false;
                        $(btn).html("Ver campos protegidos");
                        table.colums_protected.forEach(columnNum => {
                            var columShow=tableTmp.column(columnNum)
                            columShow.visible(false);
                        });

                        tableTmp.search("");
                        tableTmp.ajax.reload();
                        tableTmp.responsive.recalc();

                        showToast(0,"Ahora estan ocultos los campos protegidos");
                    }

                });

            } else {
                showToast(3,res.response);
            }
        },
        error: function (xhr, status, error) {
            hideOverlay();
            console.log(JSON.stringify(xhr));
            sendErrorsShow([error]);
        },
    });
}
////////////////////////////////////////////////////////////////protected columns
