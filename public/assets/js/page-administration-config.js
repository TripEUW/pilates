"use strict";
var tableNoWorksDays = null;
var KTDatatablesNoWorkDays = function() {
    var initTableNoWorkDays = function() {
        // begin first table
        tableNoWorksDays = $('#kt_table_no_work_days').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todo"]
            ],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
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
                search: "Buscar día",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
            },
            ajax: {
                url: "administration_config/dataTableNoWorkDays",
                dataType: "json",
                type: "POST",
                data: { _token: $('#token_ajax').val() }
            },

            columns: [
                {data: 'id',responsivePriority: 1},
                { data: 'date'},
                { data: 'description'},
                { data: 'actions', responsivePriority: -1 },
            ],
            columnDefs: [
                {
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
                                <a class="dropdown-item" href="#" onclick='editNoWorkDay(` + JSON.stringify(data) + `)'><i class="flaticon-edit color-green"></i>Editar</a>
                                <a class="dropdown-item" href="#" onclick="deleteNoWorkDay(` + data.id + `)"><i class="flaticon-delete color-green"></i>Eliminar</a>
                            </div>
                        </span>
                        `;
                    },
                }
            ],
            drawCallback: function(settings) {
                    $('#kt_table_no_work_days').show();
            },
            order: [
                [0, 'desc']
            ]

        });


    };

    return {

        //main function to initiate the module
        init: function() {
            initTableNoWorkDays();
        },

    };

}();

try {
    jQuery(document).ready(function() {
    KTDatatablesNoWorkDays.init();
    
    $('#select-all-days').on('click', function(){
    // Get all rows with search applied
    var rows = tableNoWorksDays.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    
    tableNoWorksDays.on( 'draw', function () {
    if($('#select-all-days').is(":checked")){
    var rows = tableNoWorksDays.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', true);
    }
    });
    });
} catch (error) {
    location.reload();    
}

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
$('#kt_datepicker_edit').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight: true,
    language: 'es',
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>',
    },
});


function deleteSelectedNoWorkDays(){
$('#container-ids-no-work-days-delete').html('');
tableNoWorksDays.$('input[type="checkbox"]').each(function(){
if(this.checked){
$('#container-ids-no-work-days-delete').append(
$('<input>')
.attr('type', 'hidden')
.attr('name', this.name)
.val(this.value)
);
}
});

$('#modal_delete_no_work_days').modal('show');
}

function deleteNoWorkDay(id){
$('#id_delete_no_work_day').val(id);
$('#modal_delete_no_work_day').modal('show');
}

function editNoWorkDay(data){
    

    $("#kt_datepicker_edit").datepicker('setDate',data.date);
    $('#description-edit').text(data.description);
    $('#id-edit-no-work-day').val(data.id);

    
    $('#modal_edit_no_work_day').modal("show");
    
    

}

function updateStatusModuleAssitances(element){
    var status=(($(element).prop('checked'))?"true":"false");
showOverlay();
$.ajax({
    url: "administration_config/update_status_module_assitances",
    type: 'POST',
    data: {
        asisstance_module_status: status,
        _token: $('#token_ajax').val()
    },
    success: function (res) {
        hideOverlay();
        showToast(0,res.response);
        if(status=="true"){
            $("#assistances-btn").show();

        }else{
            $("#assistances-btn").hide();
        }
    },
    error: function (xhr, status, error) {
        hideOverlay();
        console.log(JSON.stringify(xhr));
        sendErrorsShow([error]);
    },
});

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

