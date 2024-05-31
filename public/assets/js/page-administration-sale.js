"use strict";
var tableClients = null;
var tableProducts = null;
var KTDatatablesDataSourceAjaxServer = (function () {
    try {
        var initTableClients = function () {
            // begin first table
            tableClients = $("#kt_table_clients").DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Todo"],
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
                serverMethod: "post",
                language: {
                    processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                    searchPlaceholder: "",
                    search: "Buscar cliente",
                    lengthMenu: "Mostrar _MENU_  por página",
                    zeroRecords: "Nada encontrado",
                    info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                    infoEmpty: "No hay registros para mostrar.",
                    infoFiltered: "",
                },
                ajax: {
                    url: "management_client/dataTable",
                    dataType: "json",
                    type: "POST",
                    data: { _token: $("#token_ajax").val() },
                },

                columns: [
                    { data: "last_name", responsivePriority: 2 },
                    { data: "name", responsivePriority: 3 },
                    { data: "suscription" },
                    { data: "tel" },
                    { data: "level" },
                    { data: "sex" },
                    { data: "email" },
                    { data: "status" },
                    { data: "address" },
                    { data: "dni" },
                    { data: "date_of_birth" },
                    { data: "date_register" },
                    { data: "observation" },
                    { data: "sessions_machine" },
                    { data: "sessions_floor" },
                    { data: "sessions_individual" },
                    { data: "actions", responsivePriority: -1 },
                ],
                columnDefs: [
                    {
                        targets: 3,
                        visible: false,
                    },
                    {
                        targets: 8,
                        orderable: true,
                        class: "text-center",
                        visible: false,
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
                        targets: 9,
                        visible: false,
                    },
                    {
                        targets: 12,
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
                            <span class="">
                            <a href="#" onclick='setClient(` +
                                JSON.stringify(data) +
                                `)' class="btn btn-brand btn-elevate btn-icon-sm p-1"><i class="la la-plus"></i>Elegir</a>
                            </span>
                            `
                            );
                        },
                    },
                ],
                drawCallback: function (settings) {
                    $("#kt_table_clients").show();
                },
                order: [[0, "desc"]],
            });
        };

        return {
            //main function to initiate the module
            init: function () {
                initTableClients();
            },
        };
    } catch (error) {
        alert("failed")
    }
})();

var KTDatatablesDataSourceProducts = (function () {
    var initTableProducts = function () {
        // begin first table
        tableProducts = $("#kt_table_products").DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todo"],
            ],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
            autoWidth: false,
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
                { data: "price", responsivePriority: -3 },
                { data: "price_end", responsivePriority: -2 },
                { data: "created_at" },
                { data: "actions", responsivePriority: -1 },
            ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    visible: false,
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
                { width: "10px", targets: 3 },
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
                    targets: 11,
                    orderable: true,
                    class: "text-center",
                    render: function (data, type, full, meta) {
                        return full.created_at_2;
                    },
                },
                {
                    targets: 8,
                    orderable: true,
                    class: "text-center",
                    // 'render': function (taxes, type, full, meta){

                    //     var result="";
                    //     taxes.forEach(tax => {
                    //         if(tax.name=="IGIC"){
                    //         result=tax.tax;
                    //         }
                    //     });

                    //  return result;
                    // }
                },
                {
                    targets: -1,
                    title: "Actions",
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            `
                        <span class="d-flex flex-row bd-highlight">
                        <a href="#" onclick='addProduct(` +
                            JSON.stringify(data) +
                            `)' class="btn btn-brand btn-elevate btn-icon-sm p-1 m-2 bd-highlight"><i class="la la-plus"></i><br>Añadir</a>
                        <a href="#" onclick='selectedProduct(` +
                            JSON.stringify(data) +
                            `)' class="btn btn-brand btn-elevate btn-icon-sm p-1 m-2 bd-highlight"><i class="flaticon2-checkmark"></i></i><br>Elegir</a>
                        </span>
                        `
                        );
                    },
                },
            ],
            drawCallback: function (settings) {
                $("#kt_table_products").show();
            },
            order: [[1, "desc"]],
        });
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
        KTDatatablesDataSourceProducts.init();
    });
    
} catch (error) {
    location.reload();

}

function showInfoCellInModal(title, content) {
    $("#modal-info-cell-title").text(title ? title : "");
    $("#modal-info-cell-content").text(content ? content : "");
    $("#modal-info-cell").modal("show");
}

function showModalSelectClient() {
    $("div.dataTables_wrapper thead,div.dataTables_wrapper tbody").hide();
    $("#modal_select_client").modal("show");
    tableClients.search("");
    tableClients.ajax.reload();
}
$("#modal_select_client").on("show.bs.modal", function (e) {
    try {
        tableClients.responsive.recalc();
        setTimeout(function () {
            $("div.dataTables_wrapper thead,div.dataTables_wrapper tbody").show();
            tableClients.responsive.recalc();
        }, 300);
        
    } catch (error) {
        location.reload();
    }
});

function showModalSelectProduct() {
    try {
        $("div.dataTables_wrapper thead,div.dataTables_wrapper tbody").hide();
        $("#modal_select_products").modal("show");
        tableProducts.search("");
        tableProducts.ajax.reload();
        
    } catch (error) {
        location.reload();
    }
}
$("#modal_select_products").on("show.bs.modal", function (e) {
    tableProducts.responsive.recalc();
    setTimeout(function () {
        $("div.dataTables_wrapper thead,div.dataTables_wrapper tbody").show();
        tableProducts.responsive.recalc();
    }, 300);
});

$("#cant-cash-col").hide();
$("#cant-tj-col").hide();
$("input[name=method_payment]").on("change", function () {
    if ($(this).val() == "mix") {
        $("#cant-cash-col").show();
        $("#cant-tj-col").show();
    } else {
        $("#cant-cash-col").hide();
        $("#cant-tj-col").hide();
    }
});

//////////////////////////////////////////sale
var idClient = null;
var products = [];
var tbodyAppend = $("#tbody_append_products");
var selectedProductForAdd = false;

$("#radio-cash").attr("checked", false);
$("#id-card").attr("checked", false);

function setClient(client) {
    idClient = client.id;
    $("#dni").val(client.dni);
    $("#last_name").val(client.last_name);
    $("#name").val(client.name);
    $("#email").val(client.email);
    $("#address").val(client.address);
    $("#tel").val(client.tel);
    $("#modal_select_client").modal("hide");
    showToast(0, "El cliente ha sido seleccionado con éxito");
}

function addProduct(product, discount = 0, unds = false) {
    var flagExist = false;
    var selectedProduct = null;
    products.forEach((itemProduct) => {
        if (itemProduct.product.id == product.id) {
            flagExist = true;
            if (unds == false) {
                itemProduct.cant++;
            } else {
                itemProduct.cant =
                    parseFloat(itemProduct.cant) + parseFloat(unds);
            }
            itemProduct.discount = discount;
            itemProduct.amount = calculeAmount(
                itemProduct.product.price,
                itemProduct.cant,
                itemProduct.discount,
                itemProduct.product.tax[0].tax
            );
            selectedProduct = itemProduct;
        }
    });

    if (!flagExist) {
        selectedProduct = {
            product: product,
            cant: unds == false ? 1 : unds,
            amount: calculeAmount(
                product.price,
                unds == false ? 1 : unds,
                discount,
                product.tax[0].tax
            ),
            discount: discount,
        };
        products.push(selectedProduct);
        checkEmptyTable();
        tbodyAppend.append(
            `
<tr class="tr-table-add-product" id="tr_product_add_` +
                product.id +
                `">
<th scope="row" class="text-center">    
<button class="quit-element" onclick="quitProduct(` +
                product.id +
                `)"><i class="flaticon-cancel"></i></button>
</th>
<td class="text-center">` +
                product.id +
                `</td>
<td>` +
                product.name +
                `</td>
<td class="text-center">` +
                formatMoney(product.price) +
                `</td>
<td style="padding:0px !important;"><input type="number" min="1" oninput="changeCantProduct(` +
                product.id +
                `)" class="input-for-table text-center" step="1" id="cant_product_` +
                product.id +
                `" value="` +
                selectedProduct.cant +
                `"></td>
<td style="padding:0px !important;" id="cell_discount_` +
                product.id +
                `"><input type="number" min="1" oninput="changeDiscountProduct(` +
                product.id +
                `)" class="input-for-table text-center" step="1" id="discount_product_` +
                product.id +
                `" value="` +
                selectedProduct.discount +
                `"></td>
<td class="text-center">` +
                formatPercent(selectedProduct.product.tax[0].tax) +
                `</td>
<td class="text-center" id="amount_product_` +
                product.id +
                `">` +
                formatMoney(selectedProduct.amount) +
                `</td>
</tr>
`
        );
        showToast(0, "El producto se agregó a la tabla con éxito");
        //$('#modal_select_products').modal('hide');
    } else {
        //console.log("descuento: " + selectedProduct.discount);
        var inputCantElement = document.getElementById(
            "cant_product_" + selectedProduct.product.id
        );
        inputCantElement.value = selectedProduct.cant;

        var inputDisElement = document.getElementById(
            "discount_product_" + selectedProduct.product.id
        );
        inputDisElement.value = selectedProduct.discount;

        var inputAmountElement = document.getElementById(
            "amount_product_" + selectedProduct.product.id
        );
        inputAmountElement.innerHTML = formatMoney(selectedProduct.amount);
        showToast(0, "El producto se agregó a la tabla con éxito");
        //$('#modal_select_products').modal('hide');
    }
    calcTotal();
}

function selectedProduct(product) {
    $("#name_p").val(product.name);
    $("#unds").val(1);
    $("#discount").val(0);
    $("#price").val(formatMoney(product.price));
    $("#modal_select_products").modal("hide");
    selectedProductForAdd = product;
    showToast(
        1,
        "Ahora clic en añadir para agregar el producto a la tabla, cambie el % de descuento o unidades que requiere el cliente antes de añadir (puede cambiar los valores de cántidad o descuento después de añadir)",
        10000
    );
}

function addSelectedProduct() {
    if (selectedProductForAdd == false) {
        showToast(3, "Debe seleccionar un producto para añadir");
    } else {
        addProduct(
            selectedProductForAdd,
            $("#discount").val() ? $("#discount").val() : 0,
            $("#unds").val() ? $("#unds").val() : 1
        );
        $("#name_p").val("");
        $("#unds").val(0);
        $("#discount").val(0);
        $("#price").val("");
        $("#modal_select_products").modal("hide");
        selectedProductForAdd = false;
    }
}

function changeCantProduct(idProduct) {
    products.forEach((itemProduct) => {
        if (itemProduct.product.id == idProduct) {
            var inputCantElement = document.getElementById(
                "cant_product_" + idProduct
            );
            var inputAmountElement = document.getElementById(
                "amount_product_" + idProduct
            );
            itemProduct.cant = inputCantElement.value
                ? inputCantElement.value
                : 0;
            itemProduct.amount = calculeAmount(
                itemProduct.product.price,
                itemProduct.cant,
                itemProduct.discount,
                itemProduct.product.tax[0].tax
            );
            inputAmountElement.innerHTML = formatMoney(itemProduct.amount);
            //console.log("change cant: "+itemProduct.cant);
        }
    });
    // console.log("change cant2: "+JSON.stringify(products));
    calcTotal();
}

function changeDiscountProduct(idProduct) {
    products.forEach((itemProduct) => {
        if (itemProduct.product.id == idProduct) {
            var inputDiscElement = document.getElementById(
                "discount_product_" + idProduct
            );
            var inputAmountElement = document.getElementById(
                "amount_product_" + idProduct
            );
            itemProduct.discount = inputDiscElement.value
                ? inputDiscElement.value
                : 0;
            itemProduct.amount = calculeAmount(
                itemProduct.product.price,
                itemProduct.cant,
                itemProduct.discount,
                itemProduct.product.tax[0].tax
            );
            inputAmountElement.innerHTML = formatMoney(itemProduct.amount);
            //console.log("change cant: "+itemProduct.cant);
        }
    });
    calcTotal();
}

function checkEmptyTable() {
    if (products.length <= 0) {
        tbodyAppend.html(`
<tr id="default_cell">
<td colspan="8" scope="row" class="text-center">    
<a href="#" class="btn btn-brand btn-elevate btn-icon-sm p-2" onclick="showModalSelectProduct()">
Agrege un producto a la tabla
<i class="la la-plus"></i>
</a>
</td>
</tr>
`);
    } else {
        if (document.getElementById("default_cell"))
            document.getElementById("default_cell").remove();
    }
}

function quitProduct(idProduct) {
    for (var i = 0; i < products.length; i++) {
        if (products[i].product.id == idProduct) {
            products.splice(i, 1);
            document.getElementById("tr_product_add_" + idProduct).remove();
        }
    }
    checkEmptyTable();
    calcTotal();
}

function formatMoney(cant) {
    cant = parseFloat(cant);
    return cant.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + " €";
}

function formatPercent(cant) {
    cant = parseFloat(cant);
    return cant.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + " %";
}

function calcTotal() {
    var total = 0;
    products.forEach((product) => {
        total += parseFloat(product.amount);
    });
    $("#total_import").val(formatMoney(total));
}

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
    if (typeof $toast === "undefined") {
        return;
    }
}

function calculeAmount(price, cant, discount, igic = 0) {
    price = parseFloat(price);
    cant = parseFloat(cant);
    igic = parseFloat(igic);
    discount = parseFloat(discount) <= 0 ? 0 : parseFloat(discount);
    igic = parseFloat(igic) <= 0 ? 0 : parseFloat(igic);

    var cal = price * cant;
    var calDiscount = discount / 100;
    calDiscount = cal * calDiscount;

    var calIgic = igic / 100;
    calIgic = cal * calIgic;

    return cal - calDiscount + calIgic <= 0 ? 0 : cal - calDiscount + calIgic;
}

function modalValidSendInvoice() {
    $("#modal_confirm_invoice").modal("show");
}

function modalValidSendTicket() {
    $("#modal_confirm_ticket").modal("show");
}

function issueInvoice() {
    showOverlay();
    var amountSend = 0;
    products.forEach((product) => {
        amountSend += parseFloat(product.amount);
    });

    $.ajax({
        url: "administration_sale/insert",
        type: "POST",
        data: {
            id: idClient,
            last_name: $("#last_name").val(),
            cif_nif: $("#dni").val(),
            name: $("#name").val(),
            email: $("#email").val(),
            tel: $("#tel").val(),
            address: $("#address").val(),
            method_payment: $("input[name='method_payment']:checked").val(),
            products: products,
            cant_cash: $("#cant-cash").val(),
            cant_tj: $("#cant-tj").val(),
            amount: Math.round((amountSend + Number.EPSILON) * 100) / 100,
            invoice: true,
            _token: $("#token_ajax").val(),
        },
        success: function (res) {
            hideOverlay();
            if (res.error != false) {
                sendErrorsSale(res.error);
            } else {
                resetSale();
                $("#download-print-invoice-btn").attr(
                    "href",
                    res.path_download
                ); // Set herf value
                $("#modal_end_invoice").modal("show");
            }
        },
        error: function (xhr, status, error) {
            hideOverlay();
            console.log(JSON.stringify(xhr));
            sendErrorsSale(["Ocurrio un error, intente de nuevo"]);
        },
    });
}

function issueTicket() {
    showOverlay();
    var amountSend = 0;
    products.forEach((product) => {
        amountSend += parseFloat(product.amount);
    });
    $.ajax({
        url: "administration_sale/insert",
        type: "POST",
        data: {
            id: idClient,
            last_name: $("#last_name").val(),
            cif_nif: $("#dni").val(),
            name: $("#name").val(),
            email: $("#email").val(),
            tel: $("#tel").val(),
            address: $("#address").val(),
            method_payment: $("input[name='method_payment']:checked").val(),
            products: products,
            cant_cash: $("#cant-cash").val(),
            cant_tj: $("#cant-tj").val(),
            amount: Math.round((amountSend + Number.EPSILON) * 100) / 100,
            ticket: true,
            _token: $("#token_ajax").val(),
        },
        success: function (res) {
            hideOverlay();
            if (res.error != false) {
                sendErrorsSale(res.error);
            } else {
                resetSale();
                $("#download-print-ticket-btn").attr("href", res.path_download); // Set herf value
                $("#modal_end_ticket").modal("show");
            }
        },
        error: function (xhr, status, error) {
            hideOverlay();
            console.log(JSON.stringify(xhr));
            sendErrorsSale(["Ocurrio un error, intente de nuevo"]);
        },
    });
}

function resetSale() {
    products = [];
    $("#radio-cash").attr("checked", false);
    $("#id-card").attr("checked", false);
    $("#cant-cash").val(""), $("#cant-tj").val(""), checkEmptyTable();
    calcTotal();
}

function sendErrorsSale(errors) {
    var errorsHtml = "";
    errors.forEach((error) => {
        errorsHtml +=
            `<li class="mt-2" style="text-transform: initial;">` +
            error +
            `</li>`;
    });
    errorsHtml =
        `<div class="alert-text">
<ul class="float-left text-left">
` +
        errorsHtml +
        `
</ul>
</div>`;

    $("#modal_errors_sale").modal("show");
    $("#content-errors-sale").html(errorsHtml);
}

////////////////////////////////////////////////////////////////protected columns
var tablesForProtectedColumns = [
    { name: "kt_table_clients", colums_protected: [3, 8, 9], status: false },
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
