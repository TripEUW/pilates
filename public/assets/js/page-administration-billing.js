"use strict";

var tableBilling = null;
var KTDatatablesDataSourceAjaxServer = function() {

    var initTableBilligns = function() {

        // begin first table
        tableBilling = $('#kt_table_billing').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todo"]
            ],
            //dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            autoWidth: true,
            responsive: true,
            colReorder: true,
            //scrollY: false,
            //scrollX: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Busqueda general",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
            },
            ajax: {
                url: "administration_billing/dataTable",
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
                { data: 'id_invoice', responsivePriority: -2 },
                { data: 'last_name' },
                { data: 'name' },
                { data: 'tel' },
                { data: 'level' },
                { data: 'sex' },
                { data: 'email' },
                { data: 'status' },
                // {data: 'address'},
                // {data: 'dni'},
                // {data: 'date_of_birth'},
                // {data: 'date_register'},
                // {data: 'observation'},
                // {data: 'sessions_machine'},
                // {data: 'sessions_floor'},
                // {data: 'sessions_individual'},
                { data: 'amount_invoice', responsivePriority: -5 },
                { data: 'date_invoice', responsivePriority: -4 },
                { data: 'code', responsivePriority: -3 },
                { data: 'actions', responsivePriority: -1 },
            ],
            columnDefs: [{
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function(data, type, full, meta) {

                        return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="` + $('<div/>').text(data).html() + `" >
                        <span></span>
                    </label>`;
                    }
                },
                /*
                {
                    'targets': 8,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                    return '<a href="#" onclick="showInfoCellInModal(`Dirección`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
                    }
                },
                {
                    'targets': 12,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){
                  return '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
                    }
                },
                */
               {
                'targets': 7,
                visible:false
            },
            {
                'targets': 3,
                visible:false
            },
                {
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {


                        return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                               <!-- <a class="dropdown-item" href="#" onclick='editClient(` + JSON.stringify(data) + `)'><i class="flaticon-edit color-green"></i> Editar cliente</a> -->
                                <a class="dropdown-item" href="#" onclick="deleteInvoice(` + data.id_invoice + `)"><i class="flaticon-delete color-green"></i> Eliminar factura</a>
                                <a class="dropdown-item" target="_blank" href="administration_billing/invoice_download/` + data.id_sale + `"><i class="flaticon-file-1 color-green"></i> Descargar factura</a>
                                <a class="dropdown-item" target="_blank" href="administration_billing/invoice_print/` + data.id_sale + `"><i class="flaticon-file-1 color-green"></i> Imprimir factura</a>

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
            initTableBilligns();
        },

    };

}();
! function(a) { a.fn.datepicker.dates.es = { days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"], daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"], daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"], monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"], today: "Hoy", monthsTitle: "Meses", clear: "Borrar", weekStart: 1, format: "dd/mm/yyyy" } }(jQuery);
$('#kt_datepicker').datepicker({
    todayHighlight: true,
    language: 'es',
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>',
    },
});

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
    // Handle click on "Select all" control
    $('#select-all-invoices').on('click', function() {
        // Get all rows with search applied
        var rows = tableBilling.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    tableBilling.on('draw', function() {
        if ($('#select-all-invoices').is(":checked")) {
            var rows = tableBilling.rows({ 'search': 'applied' }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', true);
        }
    });
    // var head_item = table.columns(0).header();
    // $(head_item ).addClass('clean-icon-table');
});

function showInfoCellInModal(title, content) {
    $('#modal-info-cell-title').text(((title) ? title : ''));
    $('#modal-info-cell-content').text(((content) ? content : ''));
    $('#modal-info-cell').modal('show');

}

function selectedInvoicesDelete() {
    $('#container-ids-invoices-delete').html('');
    // Iterate over all checkboxes in the table
    tableBilling.$('input[type="checkbox"]').each(function() {
        // If checkbox doesn't exist in DOM
        //if(!$.contains(document, this)){
        // If checkbox is checked
        if (this.checked) {
            // Create a hidden element
            $('#container-ids-invoices-delete').append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', this.name)
                .val(this.value)
            );
        }
        //}
    });
    $('#modal_delete_invoices').modal('show');
}


function deleteInvoice(id){
    $('#id_delete_invoice').val(id);
    $('#modal_delete_invoice').modal('show');
}


$("#btn_search").click(function() {


    tableBilling.search("");

    tableBilling.ajax.reload(null, false).draw('full-reset');

});

$("#btn_reset").click(function() {
    $('#client_search').val("");
    $('#start_date').val("");
    $('#end_date').val("");
    $('#start_amount').val("");
    $('#end_amount').val("");
    tableBilling.search("");

    tableBilling.ajax.reload(null, false).draw('full-reset');

});


////////////////////////////////////////////////////////////////protected columns
var tablesForProtectedColumns=[
    {name:"kt_table_billing",colums_protected:[3],status:false}
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
