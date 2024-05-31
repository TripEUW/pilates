"use strict";
var tableProducts = null;
var KTDatatablesDataSourceAjaxServer = (function () {
    var initTableProducts = function () {
        // begin first table
        try {
            tableProducts = $("#kt_table_products").DataTable({
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
                    search: "Buscar producto",
                    lengthMenu: "Mostrar _MENU_  por página",
                    zeroRecords: "Nada encontrado",
                    info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                    infoEmpty: "No hay registros para mostrar.",
                    infoFiltered: "",
                },
                ajax: {
                    url: "management_product/dataTable",
                    dataType: "json",
                    type: "POST",
                    data: { _token: $("#token_ajax").val() },
                },

                columns: [
                    { data: "id_select" },
                    { data: "id" },
                    { data: "name" },
                    { data: "type" },
                    { data: "sessions_individual" },
                    { data: "sessions_floor" },
                    { data: "sessions_machine" },
                    { data: "observation" },
                    { data: "tax" },
                    { data: "price" },
                    { data: "price_end" },
                    { data: "created_at" },
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
                        targets: 1,
                        orderable: true,
                        class: "text-center",
                        visible: false,
                    },
                    {
                        targets: 7,
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
                        targets: 8,
                        orderable: true,
                        class: "text-center",
                    },
                    {
                        targets: 11,
                        orderable: true,
                        class: "text-center",
                        render: function (data, type, full, meta) {
                            return full.created_at_2;
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
                                <a class="dropdown-item" href="#" onclick='editProduct(` +
                                JSON.stringify(data) +
                                `)'><i class="flaticon-edit color-green"></i> Editar producto</a>
                                <a class="dropdown-item" href="#" onclick="deleteProduct(` +
                                data.id +
                                `)"><i class="flaticon-delete color-green"></i> Eliminar producto</a>
                            </div>
                        </span>
                        `
                            );
                        },
                    },
                ],
                order: [[1, "desc"]],
            });
        } catch (error) {
            location.reload();
        }
    };

    return {
        //main function to initiate the module
        init: function () {
            initTableProducts();
        },
    };
})();
try {
    jQuery(document).ready(function () {
        KTDatatablesDataSourceAjaxServer.init();
        // Handle click on "Select all" control
        $("#select-all-products").on("click", function () {
            // Get all rows with search applied
            var rows = tableProducts.rows({ search: "applied" }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop("checked", this.checked);
        });

        tableProducts.on("draw", function () {
            if ($("#select-all-products").is(":checked")) {
                var rows = tableProducts.rows({ search: "applied" }).nodes();
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

function deleteSelectedProducts() {
    $("#container-ids-products-delete").html("");
    // Iterate over all checkboxes in the table
    tableProducts.$('input[type="checkbox"]').each(function () {
        // If checkbox doesn't exist in DOM
        //if(!$.contains(document, this)){
        // If checkbox is checked
        if (this.checked) {
            // Create a hidden element
            $("#container-ids-products-delete").append(
                $("<input>")
                    .attr("type", "hidden")
                    .attr("name", this.name)
                    .val(this.value)
            );
        }
        //}
    });

    $("#modal_delete_products").modal("show");
}

function deleteProduct(id) {
    $("#id_delete_product").val(id);
    $("#modal_delete_product").modal("show");
}

function editProduct(data) {
    $("#id_edit").val(data.id);
    $("#name_edit").val(data.name);
    $("#sessions_machine_edit").val(data.sessions_machine);
    $("#sessions_floor_edit").val(data.sessions_floor);
    $("#sessions_individual_edit").val(data.sessions_individual);

    if (data.suscription == "true") {
        $("#suscription_status_edit").prop("checked", true);
    } else {
        $("#suscription_status_edit").prop("checked", false);
    }

    data.tax.forEach((tax) => {
        if (tax.name == "IGIC") {
            $("#tax_edit").val(tax.tax);
            $("#id_tax_edit").val(tax.id);
        }
    });
    $("#price_edit").val(data.price);
    $("#price_all_edit").val(
        Math.round((data.price_end + Number.EPSILON) * 100) / 100
    );
    $("#observation_edit").text(data.observation);

    $("#modal_edit_product").modal("show");
}

function updateFullPrice(type) {
    var tax = 0;

    var priceTaxOut = 0;
    var priceAll = $("#price_all").val() ? $("#price_all").val() : 0;

    tax = $("#tax").val() ? $("#tax").val() : 0;

    var factorDivi = tax / 100 + 1;
    priceTaxOut = parseFloat(priceAll) / parseFloat(factorDivi);

    $("#price").val(Math.round((priceTaxOut + Number.EPSILON) * 100) / 100);
}

function updateFullPriceEdit(type) {
    var tax = 0;

    var priceTaxOut = 0;
    var priceAll = $("#price_all_edit").val() ? $("#price_all_edit").val() : 0;

    tax = $("#tax_edit").val() ? $("#tax_edit").val() : 0;
    var factorDivi = tax / 100 + 1;
    priceTaxOut = parseFloat(priceAll) / parseFloat(factorDivi);

    $("#price_edit").val(
        Math.round((priceTaxOut + Number.EPSILON) * 100) / 100
    );
}
