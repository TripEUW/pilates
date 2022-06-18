
"use strict";

var tableClients=null;
var KTDatatablesDataSourceAjaxServer = function() {

	var initTableClients = function() {

		// begin first table
		tableClients = $('#kt_table_clients').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
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
                search: "Buscar cliente",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"management_client/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },
      
			columns: [
                {data: 'id',responsivePriority: 1},
                {data: 'last_name',responsivePriority: 2},
                {data: 'name',responsivePriority: 3},
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
                    'orderable': true,
                    visible:false,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                    return '<a href="#" onclick="showInfoCellInModal(`Dirección`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
                    }
                },
                {
                    'targets': 10,
                   visible:false
                },
                {
                    'targets': 13,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){
                  return '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
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


jQuery(document).ready(function() {
 
    KTDatatablesDataSourceAjaxServer.init();
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
// var head_item = table.columns(0).header();
// $(head_item ).addClass('clean-icon-table');
});

function showInfoCellInModal(title,content){
    $('#modal-info-cell-title').text(((title)? title  : ''));
    $('#modal-info-cell-content').text(((content)? content  : ''));
    $('#modal-info-cell').modal('show');

}

function deleteSelectedClients(){
    $('#container-ids-clients-delete').html('');
    // Iterate over all checkboxes in the table
    tableClients.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-clients-delete').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });

$('#modal_delete_clients').modal('show');
}

function deleteClient(id){
    $('#id_delete_client').val(id);
    $('#modal_delete_client').modal('show');
}

function editClient(data){

$('#id-client-edit').val(data.id);

if((data.picture!=null && data.picture!="")){
    $("#img-change-profile2").attr("src",routePublicStorage+"clients/"+data.picture);
  
}else{
    $("#img-change-profile2").attr("src", routePublicImages+"user_default.png");
      
}


$('#recipient-name-edit').val(data.name);
$('#recipient-last-name-edit').val(data.last_name);
$('#group-level-edit').val(data.level);
$('#recipient-email-edit').val(data.email);



if(data.sex=='fmale'){
    $('#sex-male-edit').attr('checked', false);
    $('#sex-fmale-edit').attr('checked', true);
}else{
    $('#sex-male-edit').attr('checked', true);
    $('#sex-fmale-edit').attr('checked', false);
}

$('#recipient-date-of-birth-edit').val(moment(data.date_of_birth, 'DD/MM/YYYY').format('YYYY-MM-DD') );
$('#recipient-dni-edit').val(data.dni);
$('#recipient-address-edit').val(data.address);
$('#recipient-tel-edit').val(data.tel);
$('#group-sessions-machine-edit').val(data.sessions_machine);
$('#group-sessions-floor-edit').val(data.sessions_floor);
$('#group-sessions-individual-edit').val(data.sessions_individual);
$('#observation-edit').text(data.observation);

$('#modal_edit_client').modal("show");

}


 //Uploads part
var DocImages=[
    {type:'pdf',image:'pdf.png'},
    {type:'doc',image:'word.png'},
    {type:'docm',image:'word.png'},
    {type:'docx',image:'word.png'},
    {type:'dotm',image:'word.png'},
    {type:'xlsx',image:'excel.png'},
    {type:'xlsm',image:'excel.png'},
    {type:'xlsb',image:'excel.png'},
    {type:'xls',image:'excel.png'}
];

function readURLFrontDocument(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
   
        if (input.files[0].type.match('image.*')) {
            reader.onload = function (e) {
                $('#preview-front-document').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            var extension = input.files[0].name.split('.').pop().toLowerCase();  //file extension from input file
            var itemFound=null;
            DocImages.forEach(types => {
                if(extension==types.type){
                    itemFound=types.image;
                }
            });
            itemFound=((itemFound!=null)?itemFound:'unknown-document.png');

            reader.onload = function (e) {
                $('#preview-front-document').attr('src', routePublicImages+itemFound);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}


$("#img-change-front").change(function(){
    readURLFrontDocument(this);
});


function readURLBackDocument(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);
        if (input.files[0].type.match('image.*')) {
            reader.onload = function (e) {
                $('#preview-back-document').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); 
    }else{
        var extension = input.files[0].name.split('.').pop().toLowerCase();  //file extension from input file
        var itemFound=null;
        DocImages.forEach(types => {
            if(extension==types.type){
                itemFound=types.image;
            }
        });
        itemFound=((itemFound!=null)?itemFound:'unknown-document.png');

        reader.onload = function (e) {
            $('#preview-back-document').attr('src', routePublicImages+itemFound);
        }
        reader.readAsDataURL(input.files[0]);
    }
    }
}

$("#img-change-back").change(function(){
    readURLBackDocument(this);
});

///////////////////////////////////////////////add document 

function readURLFrontDocumentEdit(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
   
        if (input.files[0].type.match('image.*')) {
            reader.onload = function (e) {
                $('#preview-front-document-edit').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            var extension = input.files[0].name.split('.').pop().toLowerCase();  //file extension from input file
            var itemFound=null;
            DocImages.forEach(types => {
                if(extension==types.type){
                    itemFound=types.image;
                }
            });
            itemFound=((itemFound!=null)?itemFound:'unknown-document.png');

            reader.onload = function (e) {
                $('#preview-front-document-edit').attr('src', routePublicImages+itemFound);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}


$("#img-change-front-edit").change(function(){
    readURLFrontDocumentEdit(this);
});


function readURLBackDocumentEdit(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);
        if (input.files[0].type.match('image.*')) {
            reader.onload = function (e) {
                $('#preview-back-document-edit').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); 
    }else{
        var extension = input.files[0].name.split('.').pop().toLowerCase();  //file extension from input file
        var itemFound=null;
        DocImages.forEach(types => {
            if(extension==types.type){
                itemFound=types.image;
            }
        });
        itemFound=((itemFound!=null)?itemFound:'unknown-document.png');

        reader.onload = function (e) {
            $('#preview-back-document-edit').attr('src', routePublicImages+itemFound);
        }
        reader.readAsDataURL(input.files[0]);
    }
    }
}

$("#img-change-back-edit").change(function(){
    readURLBackDocumentEdit(this);
});

//////////////////////////////////////////////end add document

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img-change-profile').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}



$("#img-change").change(function(){
    readURL(this);
});


function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img-change-profile2').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}



$("#img-change2").change(function(){
    readURL2(this);
});

function openAddClientModal(){
    $("#modal_add_document_register_client").modal("show");
}

function addDocumentClient(id){
    
    $("#id-client-add-doc").val(id);
    $("#modal_add_document_client").modal("show");
}


////////////////////////////////////////////////////////////////protected columns
var tablesForProtectedColumns=[
    {name:"kt_table_clients",colums_protected:[4,9,10],status:false}
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


////////////////////////////////////////////////////////////////protected edit
var protectedFieldsEdit=[
    {name:"recipient-dni-edit-container",status:false},
    {name:"recipient-address-edit-container",status:false},
    {name:"recipient-tel-edit-container",status:false},
    {name:"inputs_balance",status:false}
    ];

protectedFieldsEdit.forEach(field => {
$(`#${field.name}`).hide();
field.status=false;
});

function showHiddenFieldsEdit(btn){

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
                var status=false;
                
                protectedFieldsEdit.forEach(field => {
                 
                    if(field.status==false){
                      $(`#${field.name}`).show();
                      field.status=true;
                      status=true;
                        
                    }else if(field.status==true){
                        $(`#${field.name}`).hide();
                        field.status=false;
                        status=false;
                       
                    }
                    
                });

                if(status){
                    $(btn).html("Ocultar campos protegidos");
                    
                    showToast(0,"Ahora pude ver los campos protegidos");
                }else{
                    $(btn).html("Editar campos protegidos");
                    showToast(0,"Ahora estan ocultos los campos protegidos");
                }
              
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
////////////////////////////////////////////////////////////////protected edit

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