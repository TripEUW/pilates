"use strict";
var tableNotifications = null;
var KTDatatablesNotification = function() {
    var initTableNotification = function() {
        // begin first table
        tableNotifications = $('#kt_table_notifications').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todo"]
            ],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            autoWidth: false,
            pageLength: 25,
            responsive: false,
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
                search: "Buscar notificación",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
            },
            ajax: {
                url: "notification/dataTable",
                dataType: "json",
                type: "POST",
                data: { _token: $('#token_ajax').val() }
            },

            columns: [
                {data: 'id',responsivePriority: 1, width: "5%"},
                { data: 'notification',width: "85%"},
                { data: 'actions', responsivePriority: -1,width: "10%"},
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
                    'targets': 1,
                    'orderable': false,
                    'className': '',
                    'render': function(data, type, full, meta) {
                        var iconCode = '';
                        //html-class, image-public, image-profile
                        if (data.type_icon == "html-class") iconCode = `<i class="${data.icon}"></i>`;
                        if (data.type_icon == "image-public") iconCode = `<img src="${routePublicImagesConfig + data.icon}" class="image-notification">`;
                        if (data.type_icon == "image-profile") iconCode = `<img src="${routePublicStorageProfilesConfig + data.icon}" class="image-notification">`;

                        return `
                        <div  class="kt-notification__item_table ${data.status}">
                        <div class="kt-notification__item-icon-table">${iconCode}</div>
                        <div class="kt-notification__item-details-table" onclick="readOpenNotification(${data.id},this)">
                        <div class="kt-notification__item-title-table">
                        ${data.title}
                        </div>
                        ${(data.message!='')?`<div class="kt-notification__item-time-table">`+data.message+`</div>`:``}
                        <div class="kt-notification__item-time-table">
                        ${data.date}
                        </div>
                        </div>
                        <button type="button" onclick="deleteNotification(${data.id})" class="btn-delete-notification"><i class="flaticon2-delete"></i></button>
                        </div>
                        `;
                    }
                },
                {
                    targets: -1,
                    title: 'Actions',
                    visible: false,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `Eliminar`;
                    },
                }
            ],
            drawCallback: function(settings) {
                    $('#kt_table_notifications').show();
            },
            order: [
                [0, 'desc']
            ]

        });


    };

    return {

        //main function to initiate the module
        init: function() {
            initTableNotification();
        },

    };

}();


jQuery(document).ready(function() {
    KTDatatablesNotification.init();

$('#select-all-notifications').on('click', function(){
// Get all rows with search applied
var rows = tableNotifications.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableNotifications.on( 'draw', function () {
if($('#select-all-notifications').is(":checked")){
var rows = tableNotifications.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});
});




function deleteSelectedNotifications(){
$('#container-ids-notifications').html('');
tableNotifications.$('input[type="checkbox"]').each(function(){
if(this.checked){
$('#container-ids-notifications').append(
$('<input>')
.attr('type', 'hidden')
.attr('name', this.name)
.val(this.value)
);
}
});

$('#modal_delete_notifications').modal('show');
}

function deleteNotification(id){
$('#id_delete_notification').val(id);
$('#modal_delete_notification').modal('show');
}


