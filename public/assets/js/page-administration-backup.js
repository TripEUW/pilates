"use strict";

var tableBackup = null;
var KTDatatablesDataSourceAjaxServer = function() {

    var initTableBilligns = function() {

        // begin first table
        tableBackup = $('#kt_table_backup').DataTable({
            lengthMenu: [
                [2,10, 25, 50, 100, -1],
                [2,10, 25, 50, 100, "Todo"]
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
                search: "Buscar copia",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
            },
            ajax: {
                url: "administration_backup/dataTable",
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data._token = $('#token_ajax').val();
                }
            },
            columns: [
                { data: 'id'},
                { data: 'description' },
                { data: 'file_name' },
                { data: 'date_create' },
                { data: 'file_size' },
                { data: 'status'},
                { data: 'actions', responsivePriority: -1 },
            ],
            columnDefs: [{
                    'targets': 0,
                    'type': "alt-string",
                    'searchable': false,
                    'orderable': false,
                    'className': 'text-center',
                    'render': function(data, type, full, meta) {
                        return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="` + $('<div/>').text(data).html() + `" >
                        <span></span>
                    </label>`;
                    }
                },
                {
                    'targets': 2,
                    'className': 'text-center',
                    "visible":true
                },
                {
                    'targets': 4,
                    'className': 'text-center',
                    "visible":false
                },
                {
                    'targets': 5,
                    'className': 'text-center'
                },
                {
                    targets: -1,
                    title: 'Actions',
                    'className': 'text-center',
                    orderable: false,
                    render: function(data, type, full, meta) {

                        if(data.status_num=='2'){
                            return `
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                  <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick='renameBackup(${data.id},"${data.file_name}")'><i class="flaticon-edit color-green"></i>Renombrar</a> 
                                    <a class="dropdown-item" href="#" onclick="deleteBackup(` + data.id + `)"><i class="flaticon-delete color-green"></i>Eliminar copia</a>
                                    <a class="dropdown-item" target="_blank"  href="administration_backup/download_backup/${data.id}"><i class="flaticon-download"></i></i>Descargar Copia Local</a>
                                    <a class="dropdown-item" href="#" onclick="restoreBackup(${data.id})" ><i class="flaticon2-reload"></i>Restaurar Copia Local</a>
                                </div>
                            </span>
                            `;
                        }else if(data.status_num=='3'){
                            return `
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                  <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick='renameBackup(${data.id},"${data.file_name}")'><i class="flaticon-edit color-green"></i>Renombrar</a> 
                                    <a class="dropdown-item" href="#" onclick="deleteBackup(` + data.id + `)"><i class="flaticon-delete color-green"></i>Eliminar copia</a>
                                    <a class="dropdown-item" target="_blank"  href="administration_backup/download_backup/${data.id}"><i class="flaticon-download"></i></i>Descargar Copia</a>
                                    <a class="dropdown-item" href="#" onclick="restoreBackup(${data.id})" ><i class="flaticon2-reload"></i>Restaurar Copia</a>
                                </div>
                            </span>
                            `;
                        }else if(data.status_num=='4'){
                            return `
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                  <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="deleteBackup(` + data.id + `)"><i class="flaticon-delete color-green"></i>Eliminar copia</a>
                                </div>
                            </span>
                            `;
                        }
                      
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

try {
    jQuery(document).ready(function() {
        KTDatatablesDataSourceAjaxServer.init();
        // Handle click on "Select all" control
        $('#select-all-backups').on('click', function() {
            // Get all rows with search applied
            var rows = tableBackup.rows({ 'search': 'applied' }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    
        tableBackup.on('draw', function() {
            if ($('#select-all-backups').is(":checked")) {
                var rows = tableBackup.rows({ 'search': 'applied' }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', true);
            }
        });
        // var head_item = table.columns(0).header();
        // $(head_item ).addClass('clean-icon-table');
    });
} catch (error) {
    location.reload();
}

function showInfoCellInModal(title, content) {
    $('#modal-info-cell-title').text(((title) ? title : ''));
    $('#modal-info-cell-content').text(((content) ? content : ''));
    $('#modal-info-cell').modal('show');

}

function selectedBackupsDelete() {
    $('#container-ids-backups-delete').html('');
    // Iterate over all checkboxes in the table
    tableBackup.$('input[type="checkbox"]').each(function() {
        // If checkbox doesn't exist in DOM
        //if(!$.contains(document, this)){
        // If checkbox is checked
        if (this.checked) {
            // Create a hidden element
            $('#container-ids-backups-delete').append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', this.name)
                .val(this.value)
            );
        }
        //}
    });
    $('#modal_delete_backups').modal('show');
}


function deleteBackup(id){
    $('#id_delete_backup').val(id);
    $('#modal_delete_backup').modal('show');
}


$("#btn_search").click(function() {


    tableBackup.search("");

    tableBackup.ajax.reload(null, false).draw('full-reset');

});

$("#btn_reset").click(function() {

    $('#client_search').val("");
    $('#start_date').val("");
    $('#end_date').val("");
    $('#start_amount').val("");
    $('#end_amount').val("");
    tableBackup.search("");

    tableBackup.ajax.reload(null, false).draw('full-reset');

});

function downloadBackup(id){
    showOverlay();
    $.ajax({
        url: "administration_backup/download_backup",
        type: 'POST',
        data:{id:id,_token:$('#token_ajax').val()},
        success: function (res) {
         
            hideOverlay();
            if (res.status == false) {
                showToast(4,res.response);
            } else {
                showToast(0,res.response);
            }
        },
        error: function (xhr, status, error) {
         
            hideOverlay();
            console.log(JSON.stringify(xhr));
            showToast(4,error);
        },
    });
}


function restoreBackup(id){
$("#id-for-restore-backup").val(id);
$("#modal_restore_backup").modal("show");
}
function restoreAccept(){
$("#modal_restore_backup").modal("hide");
$("#form-for-restore").submit();
}
function renameBackup(id, name){
$("#id-backup-for-rename").val(id);
$("#name-backup-change").val(name);
$("#modal_rename_backup").modal("show");
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
var $toastlast = $toast;
if(typeof $toast === 'undefined'){
return;
}
}


