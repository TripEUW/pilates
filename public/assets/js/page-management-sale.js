"use strict";
var tableSales = null;
var KTDatatablesDataSourceAjaxServer = function() {
    var initTableSales = function() {
        // begin first table
        try {
            tableSales = $('#kt_table_sales').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Todo"]
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
                    url: "management_sale/dataTable",
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
                    { data: 'id_sale', responsivePriority: 2 },
                    { data: 'sale_date', responsivePriority: 3 },
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
        } catch (error) {
            location.reload()
        }
        
    };

    return {

        //main function to initiate the module
        init: function() {
            initTableSales();
        },

    };

}();
try {
    
    ! function(a) { a.fn.datepicker.dates.es = { days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"], daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"], daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"], monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"], today: "Hoy", monthsTitle: "Meses", clear: "Borrar", weekStart: 1, format: "dd/mm/yyyy" } }(jQuery);
    $('#kt_datepicker').datepicker({
        todayHighlight: true,
        language: 'es',
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        },
    });
} catch (error) {
    location.reload();
}
try {
    jQuery(document).ready(function() {
        KTDatatablesDataSourceAjaxServer.init();
    
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
    });
    
} catch (error) {
    location.reload();
}

function deleteSelectedSales(){
    $('#container-ids-sales-delete').html('');
    // Iterate over all checkboxes in the table
    tableSales.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-sales-delete').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });

$('#modal_delete_sales').modal('show');
}

function deleteSale(id){
    $('#id_delete_sale').val(id);
    $('#modal_delete_sale').modal('show');
}

function showInfoCellInModal(title, content) {
    $('#modal-info-cell-title').text(((title) ? title : ''));
    $('#modal-info-cell-content').text(((content) ? content : ''));
    $('#modal-info-cell').modal('show');
}

$("#btn_search").click(function() {
    tableSales.search("");
    tableSales.ajax.reload(null, false).draw('full-reset');
});

$("#btn_reset").click(function() {
    $('#client_search').val("");
    $('#start_date').val("");
    $('#end_date').val("");
    $('#start_amount').val("");
    $('#end_amount').val("");
    tableSales.search("");
    tableSales.ajax.reload(null, false).draw('full-reset');
});