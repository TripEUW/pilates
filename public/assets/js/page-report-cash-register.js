
var earningsTj=0;
var earningsMetalic=0;
var totalEarnings=0;
var actualDate=moment().format('DD/MM/YYYY');
var dateSearch=actualDate;
var fieldsCount = [
    {id:0,valMoney:500},
    {id:1,valMoney:200},
    {id:2,valMoney:100},
    {id:3,valMoney:50},
    {id:4,valMoney:20},
    {id:5,valMoney:10},
    {id:6,valMoney:5},
    {id:7,valMoney:2},
    {id:8,valMoney:1},
    {id:9,valMoney:0.5},
    {id:10,valMoney:0.2},
    {id:11,valMoney:0.1},
    {id:12,valMoney:0.05},
    {id:13,valMoney:0.02},
    {id:14,valMoney:0.01}
];
var totalCounts=0;
var initialBalance=0;
var receiptsExpenses=0;
var statusResult='';


! function(a) { a.fn.datepicker.dates.es = { days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"], daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"], daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"], monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"], today: "Hoy", monthsTitle: "Meses", clear: "Borrar", weekStart: 1, format: "dd/mm/yyyy" } }(jQuery);

/*$('#kt_datepicker').datepicker({
    todayHighlight: true,
    language: 'es',
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>',
    },
});
*/

getData(actualDate);


function getData(date) {
  
    //$("#kt_datepicker").datepicker('setDate', date);
    $("#kt_datepicker").val(date);
    dateSearch=date;
    showOverlay();
    $.ajax({
        url: "report_cash_register_get_data",
        type: 'POST',
        data: {
            date:date,
            _token: $('#token_ajax').val()
        },
        success: function (res) {

        $("#total-earnings").val(res.total_earnings_formated);
        $("#earnings-tj").val(res.earnings_tj_formated);
        $("#earnings-metalic").val(res.earnings_metalic_formated);

earningsTj=parseFloat(res.earnings_tj);
earningsMetalic=parseFloat(res.earnings_metalic);
totalEarnings=parseFloat(res.total_earnings);

        hideOverlay();
        },
        error: function (xhr, status, error) {
        hideOverlay();
        sendErrorsShow([error]);
        },
    });
}

function checkEditableNumber(event,element){
if (isNaN(String.fromCharCode(event.which))){
event.preventDefault();
showToast(3,'Solo puede ingresar números enteros.');
}else{
var cant=($(element).val())?$(element).val():0;
if(cant<0){
event.preventDefault();
showToast(3,'La cantidad debe ser mayor o igual a 0');
} 
}
}

function checkPositiveNumber(event,element){
if(!isNaN($(element).val())){
var cant=($(element).val())?$(element).val():0;
if(cant<0){
event.preventDefault();
showToast(3,'La cantidad debe ser mayor o igual a 0');
}
}else{
element.preventDefault();
showToast(3,'Solo puede ingresar números.');
}
}

function setResult(){
    $("#continaer-ids-counts").html('');
    totalCounts=0;
    fieldsCount.forEach(element => {
        if (isNaN($(`#field-count-${element.id}`).val()) || $(`#field-count-${element.id}`).val()==""){
            $('#continaer-ids-counts').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', 'counts[]')
                   .val(0)
             );
        }else{
        totalCounts+=(parseFloat(element.valMoney)*parseInt($(`#field-count-${element.id}`).val()));
        $('#continaer-ids-counts').append(
            $('<input>')
               .attr('type', 'hidden')
               .attr('name', 'counts[]')
               .val(parseInt($(`#field-count-${element.id}`).val()))
         );
        }
    });
    $("#total-box").val(formatMoney(totalCounts));

    initialBalance=(!isNaN($(`#initial-balance`).val()))?$(`#initial-balance`).val():0;
    receiptsExpenses=(!isNaN($(`#receipts-expenses`).val()))?$(`#receipts-expenses`).val():0;
    if(parseFloat(initialBalance<0))initialBalance=0;
    if(parseFloat(receiptsExpenses<0))receiptsExpenses=0;

    var calc1=parseFloat(totalCounts)+parseFloat(receiptsExpenses);
    var calc2=parseFloat(earningsMetalic)+parseFloat(initialBalance);

    if(calc1==calc2){
    statusResult='true';
    $("#status-result").html(`<h4 class="" style="color:green;">!Arqueo Correcto!</h4>`);
    }else{
    statusResult='false';
    $("#status-result").html(`<h4 class="" style="color:red;">!Arqueo Incorrecto!</h4>`);
    }

    ///post sets

$("#initial-balance-p").val(initialBalance);
$("#receipts-expenses-p").val(receiptsExpenses);
$("#earnings-tj-p").val(earningsTj);
$("#earnings-metalic-p").val(earningsMetalic);
$("#date-p").val(dateSearch);
$("#status-p").val(statusResult);
}

function submitFormReport(){
    if(statusResult!=''){
    $("#form-report-print-or-save").submit();
    location.reload();
    }else{
    sendErrorsShow(['Para imprimir debe haber un resultado de arqueo.']);
    }
}


function formatMoney(cant) {
    cant = parseFloat(cant);
    return cant.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') + " €";
}
//////////////////////////////////////////////////////messages

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