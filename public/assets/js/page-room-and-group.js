"use strict";
var tableRoom=null;
var tabelEmployeeSelected=null;
var tableRoomSelected=null;
var tableGroup=null;
var setGroupMode='add';
var DatatableDataRoomServer = function() {
	var initTableRoom = function() {
		// begin first table
		tableRoom = $('#kt_table_rooms').DataTable({
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
                search: "Buscar sala",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"management_room_group/dataTable_room",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },

			columns: [
                {data: 'id',responsivePriority: -2},
                {data: 'id_num'},
                {data: 'name'},
				{data: 'type_room'},
				{data: 'capacity'},
                {data: 'observation'},
                {data: 'actions',responsivePriority: -1},
                
			],
			columnDefs: [
                {
                    'targets': 0,
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
                {   'targets': 1,
                    "visible": false,
                },
                {
                    'targets': 5,
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
                        <span class='dropdown'>
                        <a href='#' class='btn btn-sm btn-clean btn-icon btn-icon-md' data-toggle='dropdown' aria-expanded='true'>
                          <i class='la la-ellipsis-h'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-right'>
                            <a class='dropdown-item' href='#' onclick='editRoom(`+JSON.stringify(data)+`)'><i class='flaticon-edit'></i> Editar</a>
                            <a class='dropdown-item' href='#' onclick='deleteRoom(`+JSON.stringify(data)+`)'><i class='flaticon-delete'></i> Eliminar</a>
                        
                        </div>
                        </span>
                        `;
					},
				}
            ],
            order: [[0, 'desc']]
            
        });
        
        
	};

	return {
		//main function to initiate the module
		init: function() {
			initTableRoom();
		},

    };
}();

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
                url:"management_room_group/dataTable_group",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax2').val()}
            },

			columns: [
                {data: 'id',responsivePriority: -2},
                {data: 'name'},
                {data: 'employee_name'},
                {data: 'room_name'},
				{data: 'status',responsivePriority: -4},
                {data: 'level',responsivePriority: -3},
                {data: 'observation'},
                {data: 'actions',responsivePriority: -1},
                
			],
			columnDefs: [
                {
                    'targets': 0,
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
                    'targets': 6,
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
                        <span class='dropdown'>
                        <a href='#' class='btn btn-sm btn-clean btn-icon btn-icon-md' data-toggle='dropdown' aria-expanded='true'>
                          <i class='la la-ellipsis-h'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-right'>
                            <a class='dropdown-item' href='#' onclick='editGroup(`+JSON.stringify(data)+`)'><i class='flaticon-edit'></i> Editar</a>
                            <a class='dropdown-item' href='#' onclick='deleteGroup(`+JSON.stringify(data)+`)'><i class='flaticon-delete'></i> Eliminar</a>
                        
                        </div>
                        </span>
                        `;
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
                url:"management_room_group/dataTable_employee_select",
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
 
                   return `<a href="#" onclick='setEmployeeForm(`+JSON.stringify(data)+`);' class="btn btn-brand btn-elevate btn-icon-sm p-1"><i class="la la-plus"></i>Elegir</a>`;
                    }
                }
            ],
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
try {
    jQuery(document).ready(function() {
    DatatableDataRoomServer.init();
    DatatableDataEmployeeSelectedServer.init();
    DatatableDataRoomSelectedServer.init();
    DatatableDataGroupServer.init();
    
    tableRoom.on( 'draw', function () {
    if($('#select-all-rooms').is(":checked")){
    var rows = tableRoom.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', true);
    }
    });
    
    $('#select-all-rooms').on('click', function(){
    // Get all rows with search applied
    var rows = tableRoom.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    
    
    tableGroup.on( 'draw', function () {
    if($('#select-all-group').is(":checked")){
    var rows = tableGroup.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', true);
    }
    });
    
    $('#select-all-group').on('click', function(){
    // Get all rows with search applied
    var rows = tableGroup.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    
    });
    
} catch (error) {
    location.reload()
}

function showInfoCellInModal(title,content){
    $('#modal-info-cell-title').text(((title)? title  : ''));
    $('#modal-info-cell-content').text(((content)? content  : ''));
    $('#modal-info-cell').modal('show');

}

function deleteSelectedRooms(){
    var form = '#form_delete_rooms';
    $('#container-ids-rooms-delete').html('');
    // Iterate over all checkboxes in the table
    tableRoom.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-rooms-delete').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });

$('#modal_delete_rooms').modal('show');
}

function deleteSelectedGroups(){
    var form = '#form_delete_groups';
    $('#container-ids-groups-delete').html('');
    // Iterate over all checkboxes in the table
    tableGroup.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-groups-delete').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });

$('#modal_delete_groups').modal('show');    
}

function deleteRoom(data){
    $('#id_delete_room').val(data.id);
    $('#modal_delete_room').modal('show');
}
function deleteGroup(data){
    $('#id_delete_group').val(data.id_group);
    $('#modal_delete_group').modal('show');
}
function editGroup(data){
    setGroupMode='edit';
    $('#group-name-edit').val(data.name);
    $('#group-level-edit').val(data.level);
    $('#group-name-employee-edit').val(data.employee_name);
    $('#group-id-employee-edit').val(data.employee_id);
    $('#group-name-room-edit').val(data.room_name+"-"+data.type_room+"-"+data.capacity_room);
    $('#group-id-room-edit').val(data.room_id);
    $('#group-observation-edit').text(data.observation);
    $('#group-id-edit').val(data.id_group);

    $('#modal_edit_group').modal('show');
}

function editRoom(data){
 

$('#room-id-edit').val(data.id);
$('#type-room-edit').val(data.type_room);

$('#room-name-edit').val(data.name);
$('#room-capacity-edit').val(data.capacity);
$('#room-observation-edit').val(data.observation);
$('#modal_edit_room').modal('show');
    
}

function showModalSelectEmployee(){
   
    $('#modal_select_employee').modal('show');
    tabelEmployeeSelected.ajax.reload();
}
function setEmployeeForm(data){
  
    if(setGroupMode=='add'){
        $('#group-name-employee').val(data.name);
        $('#group-id-employee').val(data.id);
        $('#modal_select_employee').modal('hide');
    }else{
        $('#group-name-employee-edit').val(data.name);
        $('#group-id-employee-edit').val(data.id);
        $('#modal_select_employee').modal('hide');
    }
}
function setRoomForm(data){
   
    if(setGroupMode=='add'){
        $('#group-name-room').val(data.name+"-"+data.type_room+"-"+data.capacity);
        $('#group-id-room').val(data.id);
        $('#modal_select_room').modal('hide');
    }else{
    $('#group-name-room-edit').val(data.name+"-"+data.type_room+"-"+data.capacity);
    $('#group-id-room-edit').val(data.id);
    $('#modal_select_room').modal('hide');
    }
}
function showModalSelectRoom(){
    $('#modal_select_room').modal('show');
    tableRoomSelected.ajax.reload();
}

function showModalAddGroup(){
    setGroupMode='add';
    $('#modal_add_group').modal('show');
}


