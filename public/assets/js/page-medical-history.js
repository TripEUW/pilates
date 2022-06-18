
"use strict";

var tableClients=null;
var tableDocuments=null;

var clientSelected=((window.localStorage.getItem('clientSelected'))?window.localStorage.getItem('clientSelected'):'-1');
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
                    'targets': 3,
                   visible:false
                },
                {
                    'targets': 8,
                    'orderable': true,
                    visible:false,
                    'class':'text-center',
                    'render': function (data, type, full, meta){

                    return '<a href="#" onclick="showInfoCellInModal(`Dirección`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
                    }
                },
                {
                    'targets': 9,
                   visible:false
                },
                {
                    'targets': 12,
                    'orderable': true,
                    'class':'text-center',
                    'render': function (data, type, full, meta){
                  return '<a href="#" onclick="showInfoCellInModal(`Observaciones`,`'+(data?data:'Ningún dato para mostrar en esta celda')+'`)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true"><i class="flaticon-arrows"></i></a>';
                    }
                },
				{
                    targets: -1,
                    title: 'Seleccionar',
					orderable: false,
					render: function(data, type, full, meta) {
						return `
                        <span class="dropdown">
                            <a href="#"  onclick='loadDocuments(`+JSON.stringify(data)+`)' class="btn btn-sm btn-clean btn-icon btn-icon-md">
                            <i class="kt-nav__link-icon flaticon2-arrow"></i>
                            </a>
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

var KTDatatablesDataSourceAjaxServerDocuments = function() {

	var initTableDocuments = function() {

		// begin first table
		tableDocuments = $('#kt_table_documents').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
            pageLength: 25,
            responsive: false,
            colReorder: false,
            autoWidth:false,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: {
                processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
                searchPlaceholder: "",
                search: "Buscar documento",
                lengthMenu: "Mostrar _MENU_  por página",
                zeroRecords: "Nada encontrado",
                info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
                infoEmpty: "No hay registros para mostrar.",
                infoFiltered: ""
                  },
            ajax: {
                url:"medical_history/dataTable_documents",
                dataType: "json",
                type: "POST",
                data: function(data) {
                    data.id_client = clientSelected;
                    data._token = $('#token_ajax').val();
                }
            },
      
			columns: [
                {data: 'id',width: "10%"},
                {data: 'document',width: "85%"},
				{data: 'actions',width: "5%"},
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'class': 'text-center',
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },
                {
                    'targets': 1,
                    'orderable': false,
                    'render': function (data, type, full, meta){
      
                        var imageFront=false;
                        var imageBack=false;
                        var routePublicImages=data.routePublicImages;
                        var routeDocuments=data.routeDocuments;
                        var htmlBack=``;
                        if(data.front!=null){
                            imageFront= getCover(data.type_front,data.front,routePublicImages,routeDocuments);
                        }
                      
                        if(data.back!=null){
                            imageBack=getCover(data.type_back,data.back,routePublicImages,routeDocuments);
                            htmlBack=` <div class="col-6 col-lg-2 text-center ">
                            <div class="d-inline-block w-100">Reverso</div> 
                            <div class="container-cover-doc d-inline-block">
                            <img class="cover-image-document" src="`+imageBack+`" alt="">
                            </div>
                            <div class="row">
                            <div class="col-6 text-right"><a target="_blank" href="`+(routeDocuments+"/"+data.back)+`" class="btn-action-cover-doc d-inline"><i class="flaticon2-open-text-book"></i></a></div>
                            <div class="col-6 text-left"><a  href="`+(routeDocuments+"/"+data.back)+`" class="btn-action-cover-doc d-inline" download><i class="flaticon2-download-2"></i></a></div>
                            </div>
                            </div>`;
                        }

                           var observaciones='onclick="showInfoCellInModal(`Observaciones`,`'+(data.observation?data.observation:'Ningún dato para mostrar')+'`)"';

                    var html=`
                        <div class="item-document-cover">
                        <div class="row">
                        <div class="col-6 `+((imageBack!=false)?'col-lg-2':'col-lg-4')+` text-center">
                        <div class="d-inline-block w-100">Anverso</div>
                        <div class="container-cover-doc d-inline-block">
                        <img class="cover-image-document" src="`+imageFront+`" alt="">
                        </div>
                        <div class="row">
                        <div class="col-6 text-right"><a target="_blank" href="`+(routeDocuments+"/"+data.front)+`" class="btn-action-cover-doc d-inline"><i class="flaticon2-open-text-book"></i></a></div>
                        <div class="col-6 text-left"><a  href="`+(routeDocuments+"/"+data.front)+`" class="btn-action-cover-doc d-inline" download><i class="flaticon2-download-2"></i></a></div>
                        </div>
                        </div>
                       `+htmlBack+`
                        <div class="col-12 col-lg-8 d-flex justify-content-left align-items-center">
                        <div class="content">
                        <div class="text-left d-inline-block p-1 w-100">
                        <span class="span-table-cover-item d-inline">Nombre:</span>    
                        <span class="span-table-cover-item-data d-inline">`+data.name+`</span>    
                        </div>
                        <div class="text-left d-inline-block p-1 w-100">
                        <span class="span-table-cover-item d-inline">Se agrego:</span>    
                        <span class="span-table-cover-item-data d-inline">`+data.created_at+`</span>    
                        </div>
                        <div class="text-left d-inline-block p-1 w-100">
                        <span class="span-table-cover-item d-inline">Ultima actualización:</span>    
                        <span class="span-table-cover-item-data d-inline">`+data.updated_at+`</span>   
                        <div class="w-100 text-left">
                        <button class="btn btn-primary   d-inline  btn-show-observation-document" type="button" `+observaciones+` >
                        <i class="flaticon-eye p-1"></i> Ver observaciones
                        </button> 
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                    `;

                    
                    return html;
                    }
                },
				{
                    targets: 2,
                    title: 'Actions',
                    'class': 'td-vertical-top',
					orderable: false,
					render: function(data, type, full, meta) {
              
                    
						return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick='editDocument(`+JSON.stringify(data)+`)'><i class="flaticon-edit"></i> Editar documento</a>
                                <a class="dropdown-item" href="#" onclick="deleteDocument(`+data.id+`)"><i class="flaticon-delete"></i> Eliminar documento</a>
                            
                            </div>
                        </span>
                        `;
					},
				}
            ],
            order: [[0, 'desc']]
            
        });
        
        
	};

	return {

		//main function to initiate the module
		init: function() {
			initTableDocuments();
		},

    };

}();


jQuery(document).ready(function() {
 
    KTDatatablesDataSourceAjaxServer.init();
    KTDatatablesDataSourceAjaxServerDocuments.init();

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

//////// documents

// Handle click on "Select all" control
$('#select-all-documents').on('click', function(){
// Get all rows with search applied
var rows = tableDocuments.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableDocuments.on( 'draw', function () {
if($('#select-all-documents').is(":checked")){
var rows = tableDocuments.rows({ 'search': 'applied' }).nodes();
// Check/uncheck checkboxes for all rows in the table
$('input[type="checkbox"]', rows).prop('checked', true);
}
});

if(clientSelected!="-1"){
$("#default-content-documents").hide();
$("#content-documents").show();
$("#name-selected").text(window.localStorage.getItem('clientName'));
}
});

function showInfoCellInModal(title,content){
    $('#modal-info-cell-title').text(((title)? title  : ''));
    $('#modal-info-cell-content').text(((content)? content  : ''));
    $('#modal-info-cell').modal('show');

}


//load docs part
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

var containerDocuments=$("#documents-container");

function getCover(type,name,routePublicImages,routeDocuments){
    var typicals=['jpg','png','gif','svg','jpeg'];
     var image=false;
     typicals.forEach(typeTypical => {
         if(type.toLowerCase()==typeTypical.toLowerCase())
         image= routeDocuments+"/"+name;
     });

     if(image!=false){ return image; }

     DocImages.forEach(docs => {
        if(type.toLowerCase()==docs.type.toLowerCase())
        image= routePublicImages+"/"+docs.image;
    });
    if(image!=false){ return image; }

    return routePublicImages+"/"+'unknown-document.png';
}

function loadDocuments(client) {
   
    clientSelected=client.id;
    window.localStorage.setItem('clientSelected', client.id);
    window.localStorage.setItem('clientName',"Historial de "+client.name+" "+client.last_name);

    tableDocuments.search("");
    tableDocuments.ajax.reload(null, false).draw('full-reset');

    $("#default-content-documents").hide();
    $("#content-documents").show();
    $("#name-selected").text("Historial de "+client.name+" "+client.last_name);
    
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
    $toastlast = $toast;
    if(typeof $toast === 'undefined'){
    return;
    }
    }
    
//end load part


//Uploads part
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



function deleteSelectedDocuments(){
    $('#container-ids-documents-delete').html('');
    // Iterate over all checkboxes in the table
    tableDocuments.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-documents-delete').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });

$('#modal_delete_documents').modal('show');
}

function deleteDocument(id){
    $('#id_delete_document').val(id);
    $('#modal_delete_document').modal('show');
}

function editDocument(data){

var imageFront=false;
var imageBack=false;
var routePublicImages=data.routePublicImages;
var routeDocuments=data.routeDocuments;

$('#preview-front-document-edit').attr("src",routePublicImages+"/doc-front-default.png");
$('#preview-back-document-edit').attr("src",routePublicImages+"/doc-front-default.png");
$('#id-add-edit-doc').val("");
$('#name-document-edit').val("");
$('#observation-edit').text("");
$('#btn-delete-back').hide();
$('#confirm-delete-back-doc').val('false');

if(data.front!=null){
imageFront= getCover(data.type_front,data.front,routePublicImages,routeDocuments);
$('#preview-front-document-edit').attr("src",imageFront);
}

if(data.back!=null){
imageBack=getCover(data.type_back,data.back,routePublicImages,routeDocuments);
$('#preview-back-document-edit').attr("src",imageBack);
$('#btn-delete-back').show();
}

$('#id-add-edit-doc').val(data.id);
$('#name-document-edit').val(data.name);
$('#observation-edit').text(data.observation);

$("#modal_edit_document_client").modal("show");

}

function confirmDeleteBack(){
    $('#btn-delete-back').hide();
    $('#confirm-delete-back-doc').val('true');
    $('#preview-back-document-edit').attr("src",routePublicImages+"/doc-front-default.png");
}



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

function addDocumentClient(){
    if(clientSelected!="-1"){
        $("#id-client-add-doc").val(clientSelected);
        $("#modal_add_document_client").modal("show");
    }else{
        showToast('info',"Seleccione un cliente para agregar un documento")
    }
   
}



function compressAll(){
$('#modal_compress_all').modal('show');
}


function compressByClients(){
    $('#container-ids-compress-clients').html('');
    // Iterate over all checkboxes in the table
    tableClients.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-compress-clients').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });

$('#modal_pass_compress_by_clients').modal('show');
}
function compressByClient(){

    $('#container-ids-compress-documents').html('');
    // Iterate over all checkboxes in the table
    tableDocuments.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-compress-documents').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });


$('#modal_pass_compress_by_client').modal('show');

}

$("#modal_pass_compress_by_client_form,#modal_pass_compress_by_clients_form,#modal_compress_all_form").submit(function(){ 
    $('#modal_pass_compress_by_client').modal('hide');
    $('#modal_pass_compress_by_clients').modal('hide');
    $('#modal_compress_all').modal('hide');
    hideOverlay();
    return true;
});



////////////////////////////////////////////////////////////////protected columns
var tablesForProtectedColumns=[
    {name:"kt_table_clients",colums_protected:[3,8,9],status:false}
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
