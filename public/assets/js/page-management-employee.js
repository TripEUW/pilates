"use strict";
var table = null;
var KTDatatablesDataSourceAjaxServer = (function () {
    var initTable1 = function () {
        try {
            table = $("#kt_table_1").DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Todo"],
                ],
                dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
                pageLength: 25,
                responsive: true,
                colReorder: true,
                /* scrollY: false,
                scrollX: true,*/
                searchDelay: 500,
                processing: true,
                serverSide: true,
                serverMethod: "post",
                language: {
                    processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                    searchPlaceholder: "",
                    search: "Buscar empleado",
                    lengthMenu: "Mostrar _MENU_  por página",
                    zeroRecords: "Nada encontrado",
                    info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                    infoEmpty: "No hay registros para mostrar.",
                    infoFiltered: "",
                },
                ajax: {
                    url: "management_employee/dataTable",
                    dataType: "json",
                    type: "POST",
                    data: { _token: $("#token_ajax").val() },
                },
                columns: [
                    { data: "id" },
                    { data: "rol" },
                    { data: "name", responsivePriority: 2 },
                    { data: "email" },
                    { data: "sex" },
                    { data: "date_of_birth" },
                    { data: "tel" },
                    { data: "address" },
                    { data: "observation" },
                    { data: "status", responsivePriority: -2 },
                    { data: "actions", responsivePriority: -1 },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        searchable: false,
                        orderable: false,
                        className: "dt-body-center",
                        render: function (data, type, full, meta) {
                            return (
                                ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                            <input type="checkbox" name="id[]" value="` +
                                $("<div/>").text(data).html() +
                                `" >
                            <span></span>
                        </label>`
                            );
                        },
                    },
                    {
                        targets: 6,
                        visible: false,
                    },
                    {
                        targets: 7,
                        orderable: true,
                        visible: false,
                        class: "text-center",
                        render: function (data, type, full, meta) {
                            return (
                                '<a href="#" onclick="showInfoCellInModal(`Dirección`,`' +
                                (data
                                    ? data
                                    : "Ningún dato para mostrar en esta celda") +
                                '`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>'
                            );
                        },
                    },
                    {
                        targets: 8,
                        orderable: true,
                        class: "text-center",
                        render: function (data, type, full, meta) {
                            return (
                                '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`' +
                                (data
                                    ? data
                                    : "Ningún dato para mostrar en esta celda") +
                                '`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>'
                            );
                        },
                    },

                    {
                        targets: -1,
                        title: "Actions",
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                `
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                  <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="management_employee/edit/` +
                                data +
                                `"><i class="flaticon-edit"></i> Editar empleado</a>
                                    <a class="dropdown-item" href="#" onclick="deleteEmployee(` +
                                data +
                                `)"><i class="flaticon-delete"></i> Eliminar empleado</a>
                                
                                </div>
                            </span>
                            `
                            );
                        },
                    },
                ],
                order: [[0, "desc"]],
            });
        } catch (e) {
            location.reload();
        }
        // begin first table
    };

    return {
        //main function to initiate the module
        init: function () {
            initTable1();
        },
    };
})();

try {
    jQuery(document).ready(function () {
        KTDatatablesDataSourceAjaxServer.init();
        // Handle click on "Select all" control
        $("#select-all").on("click", function () {
            // Get all rows with search applied
            var rows = table.rows({ search: "applied" }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop("checked", this.checked);
        });
    
        table.on("draw", function () {
            if ($("#select-all").is(":checked")) {
                var rows = table.rows({ search: "applied" }).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop("checked", true);
            }
        });
    
        // var head_item = table.columns(0).header();
        // $(head_item ).addClass('clean-icon-table');
    });
} catch (error) {
    location.reload();
}


function showInfoCellInModal(title, content) {
    $("#modal-info-cell-title").text(title ? title : "");
    $("#modal-info-cell-content").text(content ? content : "");
    $("#modal-info-cell").modal("show");
}
function deleteSelectedEmployees() {
    var form = "#form_delete_employees";
    $("#container-ids-delete").html("");
    // Iterate over all checkboxes in the table
    table.$('input[type="checkbox"]').each(function () {
        // If checkbox doesn't exist in DOM
        //if(!$.contains(document, this)){
        // If checkbox is checked
        if (this.checked) {
            // Create a hidden element
            $("#container-ids-delete").append(
                $("<input>")
                    .attr("type", "hidden")
                    .attr("name", this.name)
                    .val(this.value)
            );
        }
        //}
    });

    $("#modal_delete_employees").modal("show");
}

function deleteEmployee(id) {
    $("#id_delete_employee").val(id);
    $("#modal_delete_employee").modal("show");
}

function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#img-change-profile").attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img-change").change(function () {
    readURL2(this);
});

////////////////////////////////////////////////////////////////protected columns
var tablesForProtectedColumns = [
    { name: "kt_table_1", colums_protected: [6, 7], status: false },
];
function showHiddenFields(tableId, btn) {
    showOverlay();
    $.ajax({
        url: baseUrl + "/administration_config/check_status_hide_attr",
        type: "POST",
        data: {
            _token: $("#token_ajax").val(),
        },
        success: function (res) {
            hideOverlay();
            if (res.status == true) {
                tablesForProtectedColumns.forEach((table) => {
                    if (table.name == tableId && table.status == false) {
                        var tableTmp = $(`#${table.name}`).DataTable();
                        table.status = true;
                        $(btn).html("Ocultar campos protegidos");

                        table.colums_protected.forEach((columnNum) => {
                            var columShow = tableTmp.column(columnNum);
                            columShow.visible(true);
                        });

                        tableTmp.search("");
                        tableTmp.ajax.reload();
                        tableTmp.responsive.recalc();

                        showToast(0, "Ahora pude ver los campos protegidos");
                    } else if (table.name == tableId && table.status == true) {
                        var tableTmp = $(`#${table.name}`).DataTable();
                        table.status = false;
                        $(btn).html("Ver campos protegidos");
                        table.colums_protected.forEach((columnNum) => {
                            var columShow = tableTmp.column(columnNum);
                            columShow.visible(false);
                        });

                        tableTmp.search("");
                        tableTmp.ajax.reload();
                        tableTmp.responsive.recalc();

                        showToast(
                            0,
                            "Ahora estan ocultos los campos protegidos"
                        );
                    }
                });
            } else {
                showToast(3, res.response);
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

////////////////////////////////////////////////////////////////protected edit
var protectedFieldsEdit = [
    { name: "recipient-dni-edit-container", status: false },
    { name: "recipient-address-edit-container", status: false },
    { name: "recipient-tel-edit-container", status: false },
];

protectedFieldsEdit.forEach((field) => {
    $(`#${field.name}`).hide();
    field.status = false;
});

function showHiddenFieldsEdit(btn) {
    showOverlay();
    $.ajax({
        url: baseUrl + "/administration_config/check_status_hide_attr",
        type: "POST",
        data: {
            _token: $("#token_ajax").val(),
        },
        success: function (res) {
            hideOverlay();
            if (res.status == true) {
                var status = false;

                protectedFieldsEdit.forEach((field) => {
                    if (field.status == false) {
                        $(`#${field.name}`).show();
                        field.status = true;
                        status = true;
                    } else if (field.status == true) {
                        $(`#${field.name}`).hide();
                        field.status = false;
                        status = false;
                    }
                });

                if (status) {
                    $(btn).html("Ocultar campos protegidos");

                    showToast(0, "Ahora pude ver los campos protegidos");
                } else {
                    $(btn).html("Editar campos protegidos");
                    showToast(0, "Ahora estan ocultos los campos protegidos");
                }
            } else {
                showToast(3, res.response);
            }
        },
        error: function (xhr, status, error) {
            hideOverlay();
            console.log(JSON.stringify(xhr));
            sendErrorsShow([error]);
        },
    });
}
////////////////////////////////////////////////////////////////protected edit
function showToast(type, msg, time = 1500) {
    var types = ["success", "info", "warning", "error"];
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        timeOut: time,
    };
    var $toast = toastr[types[type]](msg, ""); // Wire up an event handler to a button in the toast, if it exists
    var $toastlast = $toast;
    if (typeof $toast === "undefined") {
        return;
    }
}
