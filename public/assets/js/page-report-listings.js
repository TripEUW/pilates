"use strict";
////////////////////////////////////////////////////////////////////////table sales start
var tableSales = null;
var KTDatatablesSales = function() {
    var initTableSales = function() {
        // begin first table
        tableSales = $('#kt_table_sales').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todo"]
            ],
            dom: '<"top"ifBlp<"clear">>rt<"bottom"ifBlp<"clear">>',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [1,2,3,4,5,6]
                    }
                }
            ],
            autoWidth: false,
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
                search: "Buscar venta",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
            },
            ajax: {
                url: `${baseUrl}report/get_sales_data_table`,
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data.client_search = $('#client_search').val();
                    data.start_date = $('#start_date').val();
                    data.end_date = $('#end_date').val();
                    data.start_amount = $('#start_amount').val();
                    data.end_amount = $('#end_amount').val();
                    data._token = $('#token_ajax').val();
                }
            },
            columns: [
                { data: 'id_sale' },
                { data: 'sale_date' },
                { data: 'amount_invoice' },
                { data: 'client_name_c' },
                { data: 'employee_name_c' },
                { data: 'type_payment' },
                { data: 'type_emission' },
                { data: 'actions', responsivePriority: -1 },
            ],
            columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    visible:false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){

                    return `<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
                {
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    visible:false,
                    render: function(data, type, full, meta) {
                     
                        var htmlPart2=``;
                        if(parseInt(data.invoice_count)>0){
                         htmlPart2=`
                         <a class="dropdown-item" target="_blank" href="administration_billing/invoice_download/` + data.id_sale + `"><i class="flaticon-file-1 color-green"></i> Descargar factura</a>
                         <a class="dropdown-item" target="_blank" href="administration_billing/invoice_print/` + data.id_sale + `"><i class="flaticon-file-1 color-green"></i> Imprimir factura</a>`;
                        }else if(parseInt(data.invoice_count)<=0){
                            htmlPart2=    `
                        <a class="dropdown-item" href="management_sale/generate_invoice/` + data.id_sale + `"><i class="flaticon-file-1 color-green"></i>Generar factura</a>
                        `;
                        }
                        return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="deleteSale(` + data.id_sale + `)"><i class="flaticon-delete color-green"></i> Eliminar venta</a>
                                <a class="dropdown-item" target="_blank" href="administration_billing/ticket_download/` + data.id_sale + `"><i class="flaticon-file-1 color-green"></i>Descargar ticket</a>
                                <a class="dropdown-item" target="_blank" href="administration_billing/ticket_print/` + data.id_sale + `"><i class="flaticon-file-1 color-green"></i>Imprimir ticket</a>
                                `+htmlPart2+`
                            </div>
                        </span>
                        `;
                    },
                }
            ],
            order: [
                [0, 'desc']
            ]

        });
    };

    return {

        //main function to initiate the module
        init: function() {
            initTableSales();
        },

    };

}();



function startTableSales(){
KTDatatablesSales.init();
// Handle click on "Select all" control
$('#select-all-sales').on('click', function(){
// Get all rows with search applied
var rows = tableSales.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableSales.on( 'draw', function () {
if($('#select-all-sales').is(":checked")){
var rows = tableSales.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});
};

$("#btn_search_sale").click(function() {
    tableSales.search("");
    tableSales.ajax.reload(null, false).draw('full-reset');
});

$("#btn_reset_sale").click(function() {
    $('#client_search').val("");
    $('#start_amount').val("");
    $('#end_amount').val("");
    tableSales.search("");
    tableSales.ajax.reload(null, false).draw('full-reset');
});
////////////////////////////////////////////////////////////////////////table sales end
////////////////////////////////////////////////////////////////////////table products start
var tableProducts=null;
var tableProductsDefaultColOrder=1;
var tableProductsDefaultOrder='desc';

var KTDatatablesProducts = function() {

	var initTableProducts= function() {

		// begin first table
		tableProducts = $('#kt_table_products').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"ifBlp<"clear">>rt<"bottom"ifBlp<"clear">>',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [1,3,4,5,6,7,8,9,10,11]
                    }
                }
            ],
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
                search: "Buscar producto",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:`${baseUrl}report/get_products_data_table`,
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data.start_date = $('#start_date').val();
                    data.end_date = $('#end_date').val();
                    data._token = $('#token_ajax').val();
                }
            },
            
      
			columns: [
                {data: 'id_select'},
                {data: 'count_sold'},
                {data: 'id'},
                {data: 'name'},
                {data: 'sessions_individual'},
                {data: 'sessions_floor'},
                {data: 'sessions_machine'},
				{data: 'observation'},
				{data: 'tax'},
				{data: 'price'},
                {data: 'price_end'},
                {data: 'created_at2'},
				{data: 'actions',  responsivePriority: -1},
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    visible:false,
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
                    targets:1,
                    'orderable': true,
                    visible:true,
                },
                {
                    targets:2,
                    visible:false,
                },
                {
                    'targets': 8,
                    'orderable': true,
                    'class':'text-center'
            
                },
				{
                    targets: 12,
                    title: 'Actions',
                    orderable: false,
                    visible:false,
					render: function(data, type, full, meta) {
                       
						return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick='editProduct(`+JSON.stringify(data)+`)'><i class="flaticon-edit color-green"></i> Editar producto</a>
                                <a class="dropdown-item" href="#" onclick="deleteProduct(`+data.id+`)"><i class="flaticon-delete color-green"></i> Eliminar producto</a>
                            </div>
                        </span>
                        `;
					},
				}
            ],
            order: [[tableProductsDefaultColOrder, tableProductsDefaultOrder]]
            
        });
        
        
	};

	return {

		//main function to initiate the module
		init: function() {
			initTableProducts();
		},

    };

}();


function startTableProducts(){
KTDatatablesProducts.init();
// Handle click on "Select all" control
$('#select-all-products').on('click', function(){
// Get all rows with search applied
var rows = tableProducts.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableProducts.on( 'draw', function () {
if($('#select-all-products').is(":checked")){
var rows = tableProducts.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});
// var head_item = table.columns(0).header();
// $(head_item ).addClass('clean-icon-table');
}

////////////////////////////////////////////////////////////////////////table products end
////////////////////////////////////////////////////////////////////////table employee start
var tableEmployee=null;
var columnsExportTableEmployee=[1,2,3,4,5,8,9];
var KTDatatablesEmployee = function() {

	var initTable1 = function() {

		// begin first table
		tableEmployee = $('#kt_table_employee').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"ifBlp<"clear">>rt<"bottom"ifBlp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: columnsExportTableEmployee
                    }
                }
            ],
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
                url:`${baseUrl}report/get_employee_data_table`,
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data.start_date = $('#start_date').val();
                    data.end_date = $('#end_date').val();
                    data._token = $('#token_ajax').val();
                }
            },
			columns: [
                {data: 'id'},
                {data: 'rol'},
				{data: 'name',responsivePriority: 2},
				{data: 'email'},
				{data: 'sex'},
				{data: 'date_of_birth'},
				{data: 'tel'},
                {data: 'address'},
                {data: 'observation'},
                {data: 'status' ,responsivePriority: -2},
				{data: 'actions',  responsivePriority: -1},
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    visible:false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
                {
                    'targets': 6,
                    visible:false,
                 
                },
                {
                    'targets': 7,
                    visible:false,
                 
                },
				{
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    visible:false,
					render: function(data, type, full, meta) {
                    
						return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="management_employee/edit/`+data+`"><i class="flaticon-edit"></i> Editar empleado</a>
                                <a class="dropdown-item" href="#" onclick="deleteEmployee(`+data+`)"><i class="flaticon-delete"></i> Eliminar empleado</a>
                            
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
			initTable1();
		},

    };
    


}();




function startTableEmployees(){
    
KTDatatablesEmployee.init();

// Handle click on "Select all" control
$('#select-all-employees').on('click', function(){
// Get all rows with search applied
var rows = tableEmployee.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableEmployee.on( 'draw', function () {
if($('#select-all-employees').is(":checked")){
var rows = tableEmployee.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});

// var head_item = table.columns(0).header();
// $(head_item ).addClass('clean-icon-table');
 }

////////////////////////////////////////////////////////////////////////table employee end
////////////////////////////////////////////////////////////////////////table client start
var tableClients=null;
var columnsExportTableClients=[1,2,3,5,6,7,8,11,12,13,14,15,16];
var KTDatatablesClients = function() {

	var initTableClients = function() {

		// begin first table
		tableClients = $('#kt_table_clients').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"ifBlp<"clear">>rt<"bottom"ifBlp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns:columnsExportTableClients
                    }
                }
            ],
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
                url:`${baseUrl}report/get_client_data_table`,
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data.start_date = $('#start_date').val();
                    data.end_date = $('#end_date').val();
                    data._token = $('#token_ajax').val();
                }
            },
                      
			columns: [
                {data: 'id'},
                {data: 'last_name'},
                {data: 'name'},
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
                    'className': 'dt-body-center',
                    visible:false,
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
                   visible:false
                },
                {
                    'targets': 10,
                   visible:false
                },
				{
                    targets: -1,
                    title: 'Actions',
                    visible:false,
					orderable: false,
					render: function(data, type, full, meta) {
              
                      
						return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick='editClient(`+JSON.stringify(data)+`)'><i class="flaticon-edit color-green"></i> Editar cliente</a>
                                <a class="dropdown-item" href="#" onclick="deleteClient(`+data.id+`)"><i class="flaticon-delete color-green"></i> Eliminar cliente</a>
                                <a class="dropdown-item" href="#" onclick="addDocumentClient(`+data.id+`)"><i class="flaticon-file-1 color-green"></i> Añadir documento</a>

                            </div>
                        </span>
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


function  startTableClients(){
 
KTDatatablesClients.init();
// Handle click on "Select all" control
$('#select-all-clients').on('click', function(){
// Get all rows with search applied
var rows = tableClients.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableClients.on( 'draw', function () {
if($('#select-all-clients').is(":checked")){
var rows = tableClients.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});

}
////////////////////////////////////////////////////////////////////////table client end
////////////////////////////////////////////////////////////////////////table holidays start
var tableHoliday=null;
var KTDatatableHolidays = function() {

	var initTableHolidays = function() {

		// begin first table
		tableHoliday = $('#kt_table_holidays').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"ifBlp<"clear">>rt<"bottom"ifBlp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
                    }
                }
            ],
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
                url:`${baseUrl}report/get_holidays_data_table`,
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data.start_date = $('#start_date').val();
                    data.end_date = $('#end_date').val();
                    data._token = $('#token_ajax').val();
                }
            },
			columns: [
                {data: 'id'},
                {data: 'date_add2'},
                {data: 'name'},
                {data: 'start2'},
				{data: 'end2'},
                {data: 'status'},
                {data: 'total_days'},
				{data: 'days_take'},
				{data: 'days_pending'},
				{data: 'actions',  responsivePriority: -1},
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    visible:false,
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
				{
                    targets: -1,
                    title: 'Actions',
                    visible:false,
					orderable: false,
					render: function(data, type, full, meta) {
              
                      
						return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick='editClient(`+JSON.stringify(data)+`)'><i class="flaticon-edit color-green"></i> Editar cliente</a>
                                <a class="dropdown-item" href="#" onclick="deleteClient(`+data.id+`)"><i class="flaticon-delete color-green"></i> Eliminar cliente</a>
                                <a class="dropdown-item" href="#" onclick="addDocumentClient(`+data.id+`)"><i class="flaticon-file-1 color-green"></i> Añadir documento</a>

                            </div>
                        </span>
                        `;
					},
				}
            ],
            drawCallback: function( settings ) {
               
                $('#kt_table_holidays').show();
            },
            order: [[0, 'desc']]
            
        });
        
        
	};

	return {

		//main function to initiate the module
		init: function() {
			initTableHolidays();
		},

    };

}();


function  startTableHolidays(){
 
KTDatatableHolidays.init();
// Handle click on "Select all" control
$('#select-all-holidays').on('click', function(){
// Get all rows with search applied
var rows = tableHoliday.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableHoliday.on( 'draw', function () {
if($('#select-all-holidays').is(":checked")){
var rows = tableHoliday.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});

}
////////////////////////////////////////////////////////////////////////table holidays end
////////////////////////////////////////////////////////////////////////table attendances start
var tableAttendance=null;
var KTDatatableAttendances = function() {

	var initTableAttendances = function() {

		// begin first table
		tableAttendance = $('#kt_table_attendances').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"ifBlp<"clear">>rt<"bottom"ifBlp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10]
                    }
                }
            ],
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
                url:`${baseUrl}report/get_attendances_data_table`,
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data.start_date = $('#start_date').val();
                    data.end_date = $('#end_date').val();
                    data._token = $('#token_ajax').val();
                }
            },
			columns: [
                {data: 'id'},
                {data: 'date'},
                {data: 'name'},
                {data: 'status'},
                {data: 'o_in_time'},
                {data: 'o_out_time'},
				{data: 'in_time'},
                {data: 'out_time'},
                {data: 'hours_to_work'},
				{data: 'hours_worked_pending'},
				{data: 'hours_worked'},
				{data: 'actions',  responsivePriority: -1},
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    visible:false,
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
				{
                    targets: -1,
                    title: 'Actions',
                    visible:false,
					orderable: false,
					render: function(data, type, full, meta) {
              
                      
						return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick='editClient(`+JSON.stringify(data)+`)'><i class="flaticon-edit color-green"></i> Editar cliente</a>
                                <a class="dropdown-item" href="#" onclick="deleteClient(`+data.id+`)"><i class="flaticon-delete color-green"></i> Eliminar cliente</a>
                                <a class="dropdown-item" href="#" onclick="addDocumentClient(`+data.id+`)"><i class="flaticon-file-1 color-green"></i> Añadir documento</a>

                            </div>
                        </span>
                        `;
					},
				}
            ],
            drawCallback: function( settings ) {
               
                $('#kt_table_attendances').show();
            },
            order: [[0, 'desc']]
            
        });
        
        
	};

	return {

		//main function to initiate the module
		init: function() {
			initTableAttendances();
		},

    };

}();
function  startTableAttendance(){

KTDatatableAttendances.init();
// Handle click on "Select all" control
$('#select-all-attendances').on('click', function(){
// Get all rows with search applied
var rows = tableAttendance.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableAttendance.on( 'draw', function () {
if($('#select-all-attendances').is(":checked")){
var rows = tableAttendance.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});

}
////////////////////////////////////////////////////////////////////////table free hours
var tableFreeHours=null;
var KTDatatableFreeHours = function() {

	var initTableFreeHours = function() {

		// begin first table
		tableFreeHours = $('#kt_table_free_hours').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"ifBlp<"clear">>rt<"bottom"ifBlp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [1,2,3,4,5,6]
                    }
                }
            ],
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
                url:`${baseUrl}report/get_free_hours_data_table`,
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data.start_date = $('#start_date').val();
                    data.end_date = $('#end_date').val();
                    data._token = $('#token_ajax').val();
                }
            },
			columns: [
                {data: 'id_employee'},
                {data: 'employee_name'},
                {data: 'monday'},
                {data: 'tuesday'},
                {data: 'wednesday'},
                {data: 'thursday'},
				{data: 'friday'}
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    visible:false,
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                }
            ],
            drawCallback: function( settings ) {
               
                $('#kt_table_free_hours').show();
            },
            order: [[0, 'desc']]
            
        });
        
        
	};

	return {

		//main function to initiate the module
		init: function() {
			initTableFreeHours();
		},

    };

}();

function  startTableFreeHours(){
 
KTDatatableFreeHours.init();

}
////////////////////////////////////////////////////////////////////////table attendances end

///////////////////////////////////////////////////////////////////////////////////////////process
! function(a) { a.fn.datepicker.dates.es = { days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"], daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"], daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"], monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"], today: "Hoy", monthsTitle: "Meses", clear: "Borrar", weekStart: 1, format: "dd/mm/yyyy" } }(jQuery);
$('#kt_datepicker').datepicker({
format: 'dd/mm/yyyy',
todayHighlight: true,
language: 'es',
templates: {
leftArrow: '<i class="la la-angle-left"></i>',
rightArrow: '<i class="la la-angle-right"></i>',
},
});

var tableItems=[
{id:'kt_table_sales_container',text:'Ventas',kt:'startTableSales',tableObj:'tableSales',paramsFilter:{date_filter_title:'Rango por fecha de venta'}},
{id:'kt_table_products_container',text:'Productos',kt:'startTableProducts',tableObj:'tableProducts',paramsFilter:{date_filter_title:'Rango por fecha de agregación',colum_to_order:1,order_colum:'desc'}},
{id:'kt_table_employees_container',text:'Empleados',kt:'startTableEmployees',tableObj:'tableEmployee',paramsFilter:{date_filter_title:'Rango por fecha de agregación'}},
{id:'kt_table_clients_container',text:'Clientes',kt:'startTableClients',tableObj:'tableClients',paramsFilter:{date_filter_title:'Rango por fecha de agregación'}},
{id:'kt_table_holidays_container',text:'Vacaciones',kt:'startTableHolidays',tableObj:'tableHoliday',paramsFilter:{date_filter_title:'Rango por fecha de solicitud'}},
{id:'kt_table_free_hours_container',text:'Horas libres de esta semana',kt:'startTableFreeHours',tableObj:'tableFreeHours',paramsFilter:{start_date:moment().startOf('week').add(1, "days").format('DD/MM/YYYY'),end_date:moment().endOf('week').add(1, "days").format('DD/MM/YYYY'),date_filter_title:'Rango por fecha de horas libres',}}
];




if(statusModuleAssitances){
    tableItems.push({id:'kt_table_attendances_container',text:'Asistencias',kt:'startTableAttendance',tableObj:'tableAttendance',paramsFilter:{date_filter_title:'Rango por fecha de asistencia'}});
}
var tableTemplates=[
//start ventas
{
id:'kt_table_sales_container',
text:'Ventas mensuales',
kt:'startTableSales',
tableObj:'tableSales',
paramsFilter:{
start_date:moment().startOf('month').format('DD/MM/YYYY'),
end_date:moment().endOf('month').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de venta'
}
},
{
id:'kt_table_sales_container',
text:'Ventas anuales',
kt:'startTableSales',
tableObj:'tableSales',
paramsFilter:{
start_date:moment().startOf('year').format('DD/MM/YYYY'),
end_date:moment().endOf('year').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de venta'
}
},
{
id:'kt_table_sales_container',
text:'Ventas de hoy',
kt:'startTableSales',
tableObj:'tableSales',
paramsFilter:{
start_date:moment().format('DD/MM/YYYY'),
end_date:moment().format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de venta'
}
}
//end ventas
//start productos
,
{
id:'kt_table_products_container',
text:'Productos más vendidos',
kt:'startTableProducts',
tableObj:'tableProducts',
paramsFilter:{
colum_to_order:1,
order_colum:'desc'
}
},
{
id:'kt_table_products_container',
text:'Productos menos vendidos',
kt:'startTableProducts',
tableObj:'tableProducts',
paramsFilter:{
colum_to_order:1,
order_colum:'asc'
}
},
{
id:'kt_table_products_container',
text:'Productos agregados este mes',
kt:'startTableProducts',
tableObj:'tableProducts',
paramsFilter:{
start_date:moment().startOf('month').format('DD/MM/YYYY'),
end_date:moment().endOf('month').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
colum_to_order:1,
order_colum:'desc'
}
}
,
{
id:'kt_table_products_container',
text:'Productos agregados este año',
kt:'startTableProducts',
tableObj:'tableProducts',
paramsFilter:{
start_date:moment().startOf('year').format('DD/MM/YYYY'),
end_date:moment().endOf('year').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
colum_to_order:1,
order_colum:'desc'
}
}
,
{
id:'kt_table_products_container',
text:'Productos agregados hoy',
kt:'startTableProducts',
tableObj:'tableProducts',
paramsFilter:{
start_date:moment().format('DD/MM/YYYY'),
end_date:moment().format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
colum_to_order:1,
order_colum:'desc'
}
}
//end productos
//start employees
,{
id:'kt_table_employees_container',
text:'Empleados agregados este mes',
kt:'startTableEmployees',
tableObj:'tableEmployee',
paramsFilter:{
start_date:moment().startOf('month').format('DD/MM/YYYY'),
end_date:moment().endOf('month').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
}
},
{
id:'kt_table_employees_container',
text:'Empleados agregados este año',
kt:'startTableEmployees',
tableObj:'tableEmployee',
paramsFilter:{
start_date:moment().startOf('year').format('DD/MM/YYYY'),
end_date:moment().endOf('year').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
}
},
{
id:'kt_table_employees_container',
text:'Empleados agregados hoy',
kt:'startTableEmployees',
tableObj:'tableEmployee',
paramsFilter:{
start_date:moment().format('DD/MM/YYYY'),
end_date:moment().format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
}
}
//end employess
//start clients
,{
id:'kt_table_clients_container',
text:'Clientes agregados este mes',
kt:'startTableClients',
tableObj:'tableClients',
paramsFilter:{
start_date:moment().startOf('month').format('DD/MM/YYYY'),
end_date:moment().endOf('month').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
}
},
{
id:'kt_table_clients_container',
text:'Clientes agregados este año',
kt:'startTableClients',
tableObj:'tableClients',
paramsFilter:{
start_date:moment().startOf('year').format('DD/MM/YYYY'),
end_date:moment().endOf('year').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
}
},
{
id:'kt_table_clients_container',
text:'Clientes agregados hoy',
kt:'startTableClients',
tableObj:'tableClients',
paramsFilter:{
start_date:moment().format('DD/MM/YYYY'),
end_date:moment().format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de agregación',
}
}
//end clients
//start holidays
,{
id:'kt_table_holidays_container',
text:'Vacaciones agregadas este mes',
kt:'startTableHolidays',
tableObj:'tableHoliday',
paramsFilter:{
start_date:moment().startOf('month').format('DD/MM/YYYY'),
end_date:moment().endOf('month').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de solicitud',
}
},
{
id:'kt_table_holidays_container',
text:'Vacaciones agregadas este año',
kt:'startTableHolidays',
tableObj:'tableHoliday',
paramsFilter:{
start_date:moment().startOf('year').format('DD/MM/YYYY'),
end_date:moment().endOf('year').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de solicitud',
}
},
{
id:'kt_table_holidays_container',
text:'Vacaciones agregadas hoy',
kt:'startTableHolidays',
tableObj:'tableHoliday',
paramsFilter:{
start_date:moment().format('DD/MM/YYYY'),
end_date:moment().format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de solicitud',
}
}
//end holidays
//free hours
,{
id:'kt_table_free_hours_container',
text:'Horas libres de este mes',
kt:'startTableFreeHours',
tableObj:'tableFreeHours',
paramsFilter:{
start_date:moment().startOf('month').format('DD/MM/YYYY'),
end_date:moment().endOf('month').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de horas libres',
}
},
{
id:'kt_table_free_hours_container',
text:'Horas libres de este año',
kt:'startTableFreeHours',
tableObj:'tableFreeHours',
paramsFilter:{
start_date:moment().startOf('year').format('DD/MM/YYYY'),
end_date:moment().endOf('year').format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de horas libres',
}
},
{
id:'kt_table_free_hours_container',
text:'Horas libres de hoy',
kt:'startTableFreeHours',
tableObj:'tableFreeHours',
paramsFilter:{
start_date:moment().format('DD/MM/YYYY'),
end_date:moment().format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de horas libres',
}
},
{
id:'kt_table_free_hours_container',
text:'Horas libres de esta semana',
kt:'startTableFreeHours',
tableObj:'tableFreeHours',
paramsFilter:{
start_date:moment().startOf('week').add(1, 'days').format('DD/MM/YYYY'),
end_date:moment().endOf('week').add(1, "days").format('DD/MM/YYYY'),
date_filter_title:'Rango por fecha de horas libres',
}
}
//free hours
];

if(statusModuleAssitances){
    tableTemplates.push(
        {
        id:'kt_table_attendances_container',
        text:'Asistencias de este mes',
        kt:'startTableAttendance',
        tableObj:'tableAttendance',
        paramsFilter:{
        start_date:moment().startOf('month').format('DD/MM/YYYY'),
        end_date:moment().endOf('month').format('DD/MM/YYYY'),
        date_filter_title:'Rango por fecha de asistencia',
        }
        },
        {
        id:'kt_table_attendances_container',
        text:'Asistencias de este año',
        kt:'startTableAttendance',
        tableObj:'tableAttendance',
        paramsFilter:{
        start_date:moment().startOf('year').format('DD/MM/YYYY'),
        end_date:moment().endOf('year').format('DD/MM/YYYY'),
        date_filter_title:'Rango por fecha de asistencia',
        }
        },
        {
        id:'kt_table_attendances_container',
        text:'Asistencias de hoy',
        kt:'startTableAttendance',
        tableObj:'tableAttendance',
        paramsFilter:{
        start_date:moment().format('DD/MM/YYYY'),
        end_date:moment().format('DD/MM/YYYY'),
        date_filter_title:'Rango por fecha de asistencia',
        }
        }
        );
}

var typeSelectedGlobal=null;
var keySelGlobal=null;
initItemsTables();
initItemsTemplates();
function initItemsTables(){
    $("#table-select").html("");
    $("#table-select").append(`<option value="" disabled selected="selected"></option>`);
    tableItems.forEach(function(item, index){
        $("#table-select").append(`<option  value="${index}">${item.text}</option>`);
    });
}

function initItemsTemplates(){
    $("#template-select").html("");
    $("#template-select").append(`<option value="" disabled selected="selected"></option>`);
    tableTemplates.forEach(function(item, index){
        $("#template-select").append(`<option value="${index}">${item.text}</option>`);
    });
}

function showByTable(element){
initItemsTemplates();

var keySel=parseInt($(element).val());
var itemKey=tableItems[keySel];
var tableObj=window[itemKey.tableObj];
typeSelectedGlobal='table';
keySelGlobal=keySel;
showHideContainers();

if(itemKey.paramsFilter.hasOwnProperty('start_date')){$("#start_date").datepicker("setDate",itemKey.paramsFilter['start_date']);}else{$('#start_date').val("");}
if(itemKey.paramsFilter.hasOwnProperty('end_date')){ $("#end_date").datepicker("setDate",itemKey.paramsFilter['end_date']);}else{$('#end_date').val("");}
if(itemKey.paramsFilter.hasOwnProperty('date_filter_title')){$('#title-range-filter-date').text(itemKey.paramsFilter['date_filter_title']+":");}else{$('#title-range-filter-date').text("Rango de fechas:");}

if(tableObj==null){
if(itemKey.paramsFilter.hasOwnProperty('colum_to_order') && itemKey.paramsFilter.hasOwnProperty('order_colum')){
tableProductsDefaultColOrder=itemKey.paramsFilter['colum_to_order'];
tableProductsDefaultOrder=itemKey.paramsFilter['order_colum'];
}
eval(itemKey.kt)();
}else{
if(itemKey.paramsFilter.hasOwnProperty('colum_to_order') && itemKey.paramsFilter.hasOwnProperty('order_colum')){
tableObj.order([itemKey.paramsFilter['colum_to_order'], itemKey.paramsFilter['order_colum']]);
}
        
tableObj.search("");
tableObj.ajax.reload(null, false).draw('full-reset');
}
}

function showByTemplate(element){
initItemsTables();

var keySel=parseInt($(element).val());
var itemKey=tableTemplates[keySel];
var tableObj=window[itemKey.tableObj];
typeSelectedGlobal='template';
keySelGlobal=keySel;
showHideContainers();

if(itemKey.paramsFilter.hasOwnProperty('start_date')){$("#start_date").datepicker("setDate",itemKey.paramsFilter['start_date']);}else{$('#start_date').val("");}
if(itemKey.paramsFilter.hasOwnProperty('end_date')){ $("#end_date").datepicker("setDate",itemKey.paramsFilter['end_date']);}else{$('#end_date').val("");}
if(itemKey.paramsFilter.hasOwnProperty('date_filter_title')){$('#title-range-filter-date').text(itemKey.paramsFilter['date_filter_title']+":");}else{$('#title-range-filter-date').text("Rango de fechas:");}

if(tableObj==null){

if(itemKey.paramsFilter.hasOwnProperty('colum_to_order') && itemKey.paramsFilter.hasOwnProperty('order_colum')){
tableProductsDefaultColOrder=itemKey.paramsFilter['colum_to_order'];
tableProductsDefaultOrder=itemKey.paramsFilter['order_colum'];
}
eval(itemKey.kt)();

}else{
if(itemKey.paramsFilter.hasOwnProperty('colum_to_order') && itemKey.paramsFilter.hasOwnProperty('order_colum')){
tableObj.order([itemKey.paramsFilter['colum_to_order'], itemKey.paramsFilter['order_colum']]);
}

tableObj.search("");
tableObj.ajax.reload(null, false).draw('full-reset');
}
}

function showBySearch(clean=false){
if(typeSelectedGlobal!=null && keySelGlobal!=null){
//initItemsTables();
initItemsTemplates();
showHideContainers();
var keySel=keySelGlobal;
var itemKey=(typeSelectedGlobal=='template')?tableTemplates[keySel]:tableItems[keySel];
var tableObj=window[itemKey.tableObj];

if(clean){$("#start_date").val("");$("#end_date").val("");}


if(tableObj==null){
eval(itemKey.kt)();
}else{
tableObj.search("");
tableObj.ajax.reload(null, false).draw('full-reset');
}
}else{
    $("#kt-container-default").show();
}
}

function showHideContainers(){
    if(typeSelectedGlobal!=null && keySelGlobal!=null){
        $("#kt-container-default").hide();
    var keySel=keySelGlobal;
    var itemKey=(typeSelectedGlobal=='template')?tableTemplates[keySel]:tableItems[keySel];
    var tableObj=window[itemKey.tableObj];

    tableTemplates.forEach(element => {$(`#${element.id}`).hide();});
    tableItems.forEach(element => {$(`#${element.id}`).hide();});
    $(`#${itemKey.id}`).show();
    }else{
    $("#kt-container-default").show();
    }
}


////////////////////////////////////////////////////////////////protected columns
var tablesForProtectedColumns=[
    {name:"kt_table_clients",colums_protected:[4,9,10],status:false,columns_export:columnsExportTableClients},
    {name:"kt_table_employee",colums_protected:[6,7],status:false,columns_export:columnsExportTableEmployee}
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
                            

                            if(!table.columns_export.includes(columnNum))
                            table.columns_export.push(columnNum);
                            table.columns_export.sort((a, b) => a - b);

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

                        if(table.columns_export.includes(columnNum))
                        table.columns_export.splice(  table.columns_export.indexOf(columnNum), 1 );
                        table.columns_export.sort((a, b) => a - b);

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
function showToast(type, msg) {
    var types = ['success', 'info', 'warning', 'error'];
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        preventDuplicates: false,
        onclick: null,
        timeOut: 1500
    };
    var $toast = toastr[types[type]](msg, ''); // Wire up an event handler to a button in the toast, if it exists
    if (typeof $toast === 'undefined') {
        return;
    }
}
