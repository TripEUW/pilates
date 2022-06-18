function generateInvoice(route) {
    showOverlay();
    $.ajax({
        url: route,
        type: 'POST',
        data: {
            id_client: $('#id_client').val(),
            id_sale: $('#id_sale').val(),
            last_name: $('#last_name').val(),
            cif_nif: $('#cif_nif').val(),
            name: $('#name').val(),
            email: $('#email').val(),
            tel: $('#tel').val(),
            address: $('#address').val(),
            _token: $('#token_ajax').val()
        },
        success: function(res) {
            hideOverlay();
            if (res.error != false) {
                sendErrorsSale(res.error);
            } else {
            
                window.open(res.redirect, "_blank");
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            hideOverlay();
            sendErrorsSale(['Ocurrio un error, intente de nuevo']);
        },
    });
}

function sendErrorsSale(errors) {
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

    $("#modal_errors_sale").modal("show");
    $("#content-errors-sale").html(errorsHtml);

}