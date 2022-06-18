"use strict";
var tableEmployee=null;
var enableWeekend=false;
var daysForm=['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
////////////////////////////////////////////////////////////part of shedule calendar
var tabs = ['monthly', 'weekly', 'day', 'list'];
var tabInit = 'weekly';
var tabSelected = tabInit;
var startDate = false;
var endDate = false;

var textTitleRange = "";
var actualTime = false;
var containerCalendar = 'session-calendar';

var actualDaysOfWeek = { monday: false, tuesday: false, wednesday: false, thursday: false, friday: false };

var dataCalendarSchedule = [];
var dataCalendarScheduleByAccess = [];

if(!enableWeekend){
daysForm.pop();
daysForm.pop();
}

$("#btn-back-time").click(function () {
    if (tabSelected == 'weekly') {
    actualTime = startDate.subtract(7, 'days');
    }
    getRangeDates();

});

$("#btn-next-time").click(function () {
    if(tabSelected == 'weekly') {
    actualTime = startDate.add(7, 'days');
    } 
    getRangeDates();
});

$("#btn-now-time").click(function () {
    actualTime = moment();
    //actualTime.locale(local);
    getRangeDates();
});

! function(a) { a.fn.datepicker.dates.es = { days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"], daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"], daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"], monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"], today: "Hoy", monthsTitle: "Meses", clear: "Borrar", weekStart: 1, format: "dd/mm/yyyy" } }(jQuery);
$('#kt_datepicker').datepicker({
    todayHighlight: true,
    language: 'es',
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>',
    },
});

generateTable();

function generateTable() {
    actualTime = moment();
    getRangeDates();
}

function getRangeDates() {

    if (tabSelected == 'weekly') {

        var monday = actualTime.clone().startOf('isoweek')
        var tuesday = actualTime.clone().startOf('isoweek').add(1,'days');
        var wednesday = actualTime.clone().startOf('isoweek').add(2,'days');
        var thursday = actualTime.clone().startOf('isoweek').add(3,'days');
        var friday = actualTime.clone().startOf('isoweek').add(4,'days');
        var saturday = actualTime.clone().startOf('isoweek').add(5,'days');
        var sunday = actualTime.clone().startOf('isoweek').add(6,'days');

        actualDaysOfWeek[0] = monday.format('YYYY-MM-DD');
        actualDaysOfWeek[1] = tuesday.format('YYYY-MM-DD');
        actualDaysOfWeek[2] = wednesday.format('YYYY-MM-DD');
        actualDaysOfWeek[3] = thursday.format('YYYY-MM-DD');
        actualDaysOfWeek[4] = friday.format('YYYY-MM-DD');
        actualDaysOfWeek[5] = saturday.format('YYYY-MM-DD');
        actualDaysOfWeek[6] = sunday.format('YYYY-MM-DD');

        startDate = monday;
        endDate = sunday;


      
        textTitleRange = `${monday.format('MMMM DD')}, ${monday.format('YYYY')} - ${sunday.format('MMMM DD')}, ${sunday.format('YYYY')}`;
        textTitleRange = textTitleRange.toUpperCase();
        if(!enableWeekend){
        
        startDate = monday;
        endDate = sunday;
    
        textTitleRange = `${monday.format('MMMM DD')}, ${monday.format('YYYY')} - ${friday.format('MMMM DD')}, ${friday.format('YYYY')}`;
        textTitleRange = textTitleRange.toUpperCase();
        }

        $("#text-title-range").text(textTitleRange);

        getDataByRange(tabSelected);
    }
   

}



function reloadTableWeekly() {

    var container = document.getElementById(containerCalendar);
    container.innerHTML = "";

    var classForTable = 'schedule-calendar-table-weekly';
    var classForCellTime = 'time-cell-session';
    var classForTh = 'cell-session-th';
    var classForStartTime = 'time';
    var classForEndTime = 'time';
    var classForToTime = 'to-time';
    var classForDroppableCell = 'droppable-cell-shedule';
    var classForDroppableTr = 'droppable-tr-shedule';

    
    var classForSchedule = 'event-shedule';

    var th, td, tbody, thead, tr, table, text, span, div, img;

    var table = document.createElement('table');
    table.setAttribute('class', classForTable);
    table.setAttribute('oncontextmenu', "return false;");

    //thead
    thead = document.createElement('thead');

    tr = document.createElement('tr');
    th = document.createElement('th');
    th.setAttribute('class', classForCellTime);
    text = document.createTextNode("Turno");
    th.appendChild(text);
    tr.appendChild(th);

    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    th.setAttribute('onclick', "setDaySelect('" + moment(actualDaysOfWeek[0]).format('YYYY-MM-DD') + "')");

    text = document.createTextNode("Lunes " + moment(actualDaysOfWeek[0]).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);

    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Martes " + moment(actualDaysOfWeek[1]).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);

    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Miércoles " + moment(actualDaysOfWeek[2]).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);

    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Jueves " + moment(actualDaysOfWeek[3]).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);

    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Viernes " + moment(actualDaysOfWeek[4]).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);
    if(enableWeekend){
    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Sábado " + moment(actualDaysOfWeek[5]).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);

    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Domingo " + moment(actualDaysOfWeek[6]).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);
    }

    thead.appendChild(tr);
    //end thead
    //start tbody
    tbody = document.createElement('tbody');
    tbody.setAttribute('id', 'container-table-schedule-inner');
    if(dataCalendarSchedule.length<=0){
    tbody.setAttribute('onclick', "emptyTableSchedule()");
    tbody.setAttribute('oncontextmenu', "emptyTableSchedule()");
    }

    dataCalendarSchedule.forEach(function (item, key) {

        var timeStartItem = moment(item.start,"HH:mm:ss").format('HH:mm');
        var timeEndItem = moment(item.end,"HH:mm:ss").format('HH:mm');

        var count=0;
        item.groups.forEach(function (schedule, key2) {
            var daysTmp=[schedule.monday,schedule.tuesday,schedule.wednesday,schedule.thursday,schedule.friday,schedule.saturday,schedule.sunday];
            if(!enableWeekend){
                daysTmp.pop();
                daysTmp.pop();
                }
            tr = document.createElement('tr');

            if(count==0){
            count++;
            td = document.createElement('td');
            td.setAttribute('class', classForCellTime);
            td.setAttribute('rowspan', item.groups.length );
    
            span = document.createElement('span');
            span.setAttribute('class', classForStartTime);
            text = document.createTextNode(timeStartItem);
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForToTime);
            text = document.createTextNode('a');
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForEndTime);
            text = document.createTextNode(timeEndItem);
            span.appendChild(text);
            td.appendChild(span);
            tr.appendChild(td);
        }
            
        var consecutiveStart=0;
        var consecutiveDays=0;




        var consecutiveElements=[];
        daysTmp.forEach(function (day, index) {


            if(day=='true'){
            consecutiveDays++;
            }else{
            if(consecutiveDays>=2){
            consecutiveElements.push({start:((index)-consecutiveDays),elements:consecutiveDays});
            consecutiveStart=0;
            consecutiveDays=0;
            }
            if(consecutiveDays>=1)consecutiveDays--;
            }
            if(consecutiveDays>=2 && index==(daysTmp.length-1)){
            consecutiveElements.push({start:(index-(consecutiveDays-1)),elements:consecutiveDays});
            }

         });

       
      


         var flagTd=true;
         var cantElementsTmp=0;
         
         var flagIndex=false;
         var cantElements=0;
        for (let index = 0; index < daysTmp.length; index++) {


           
         

            flagIndex=false;
            cantElements=0;

            consecutiveElements.forEach(element => {
            if(element.start==index){
            flagIndex=true;
            cantElements=element.elements;
            }
            });
         
        
            td = document.createElement('td');
            td.setAttribute('style', "border:solid 1px #efecec;position:relative;");

            if(flagIndex){
                var tmpDaysEnable=[];


                for (let tdI = index; tdI < (index+cantElements); tdI++) {
                tmpDaysEnable.push(daysForm[tdI]);
                }

                //td.setAttribute('colspan', cantElements);
                div = document.createElement('div');
                div.setAttribute('data-id-schedule',schedule.id_schedule);
                div.setAttribute('data-schedule', JSON.stringify(tmpDaysEnable));
                div.setAttribute('data-id-employee', schedule.id_employee);
                div.setAttribute('class', `${classForSchedule} event-schedule-employee-${schedule.id_employee} event-schedule-for-snap`);
                div.setAttribute('style', `background:${schedule.color}; width:calc(${((cantElements)*100)}% + ${(cantElements-1)*5}px);`);
                div.setAttribute('data-toggle', "kt-tooltip");
                div.setAttribute('data-placement', "top");
                div.setAttribute('data-original-title', `${ moment(schedule.start,"HH:mm:ss").format('HH:mm')} a ${moment(schedule.end,"HH:mm:ss").format('HH:mm')}`);


                td.appendChild(div);

                

                div = document.createElement('div');
                div.setAttribute('data-day',tmpDaysEnable[0]);
                div.setAttribute('data-start',schedule.start);
                div.setAttribute('data-end',schedule.end);
                div.setAttribute('class', `event-schedule-for-snap`);
                div.setAttribute('style', `background:#0000; width:100%; height: 15px; min-height: 15px; max-height: 15px;position: absolute;margin: auto;top: 0;bottom: 0;left:0;right:0;`);
                td.appendChild(div);
                
                tr.appendChild(td);

                for (let tdI = index; tdI < (index+cantElements)-1; tdI++) {
                    td = document.createElement('td');
                    td.setAttribute('style', "border:solid 1px #e6e6e6;");

                    var daySnapTmp='';

                    if(tdI+1==0)daySnapTmp='monday';
                    if(tdI+1==1)daySnapTmp='tuesday';
                    if(tdI+1==2)daySnapTmp='wednesday';
                    if(tdI+1==3)daySnapTmp='thursday';
                    if(tdI+1==4)daySnapTmp='friday';
                    if(tdI+1==5)daySnapTmp='saturday';
                    if(tdI+1==6)daySnapTmp='sunday';

                    div = document.createElement('div');
                    div.setAttribute('data-day',daySnapTmp);
                    div.setAttribute('data-start',schedule.start);
                    div.setAttribute('data-end',schedule.end);
                    div.setAttribute('class', `event-schedule-for-snap`);
                    div.setAttribute('style', `background:#0000; width:100%; height: 15px; min-height: 15px; max-height: 15px;left: 0; right: 0;margin: auto;`);
                    td.appendChild(div);

                    tr.appendChild(td);
                }
                index=index+cantElements-1;

                
            }else{

                var daySnapTmp='';

                if(index==0)daySnapTmp='monday';
                if(index==1)daySnapTmp='tuesday';
                if(index==2)daySnapTmp='wednesday';
                if(index==3)daySnapTmp='thursday';
                if(index==4)daySnapTmp='friday';
                if(index==5)daySnapTmp='saturday';
                if(index==6)daySnapTmp='sunday';

                        
            if(daysTmp[index]=='true'){
            div = document.createElement('div');
            div.setAttribute('data-id-schedule',schedule.id_schedule);
            div.setAttribute('data-schedule', JSON.stringify([daySnapTmp]));
            div.setAttribute('data-id-employee', schedule.id_employee);
            div.setAttribute('class',  `${classForSchedule} event-schedule-employee-${schedule.id_employee} event-schedule-for-snap`);
            div.setAttribute('style', `background:${schedule.color};`);
          
            div.setAttribute('data-toggle', "kt-tooltip");
            div.setAttribute('data-placement', "top");
            div.setAttribute('data-original-title', `${ moment(schedule.start,"HH:mm:ss").format('HH:mm')} a ${moment(schedule.end,"HH:mm:ss").format('HH:mm')}`);
       

            td.appendChild(div);
            }else{
                

                div = document.createElement('div');
                div.setAttribute('data-day',daySnapTmp);
                div.setAttribute('data-start',schedule.start);
                div.setAttribute('data-end',schedule.end);
                div.setAttribute('class', `event-schedule-for-snap`);
                div.setAttribute('style', `background:#0000; width:100%; height: 15px; min-height: 15px; max-height: 15px;left: 0; right: 0;margin: auto;`);
                td.appendChild(div);
            }
            tr.appendChild(td);
            cantElementsTmp=0;
            }
    
            
        }
       
        tr.setAttribute('data-key-group',key );
        tr.setAttribute('data-key-element-group',key2);
        tbody.appendChild(tr);
        
                //var startTmp = moment(subItem.time_start).format('YYYY-MM-DD hh:mm A');
               // var endTmp = moment(subItem.time_end).format('YYYY-MM-DD hh:mm A');
          
        });
          
        

    });

    if (dataCalendarSchedule.length <= 0 ) {

        for (let index = 0; index < 4; index++) {
            tr = document.createElement('tr');
            td = document.createElement('td');
            td.setAttribute('class', classForCellTime);

            span = document.createElement('span');
            span.setAttribute('class', classForStartTime);
            text = document.createTextNode('');
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForToTime);
            text = document.createTextNode('');
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForEndTime);
            text = document.createTextNode('');
            span.appendChild(text);
            td.appendChild(span);
            tr.appendChild(td);


            var daysWeekFor=(enableWeekend)?7:5;


            for (let day = 0; day < daysWeekFor; day++) {
                td = document.createElement('td');
                td.setAttribute('class', classForDroppableCell);
                td.setAttribute('data-date', actualDaysOfWeek[day]);
                tr.appendChild(td);
            }
            tbody.appendChild(tr);
        }


    }


    table.appendChild(thead);
    table.appendChild(tbody);

    container.appendChild(table);
    getDataHolidays();
}

function reloadTableHolidays(data){
var containerHolidaysDates=$("#container-holidays-dates");

if(data.length>0){
var htmlData=``;
data.forEach(holiday => {
htmlData+=`
<div class="col-12 col-sm-2 col-md-2 col-lg-4 col-xl-4 ">
<div class="form-group p-3" >
${holiday.status_formated}
<div class="kt-checkbox-inline text-center">
<label class="kt-checkbox unselect-text d-inline-block">
<input class="check-holidays" data-id="${holiday.id}" type="checkbox" ${(holiday.status=="accept")?'checked="checked" disabled' :''}>Del ${moment(holiday.start, "YYYY-MM-DD").format("DD MMMM")} al ${moment(holiday.end, "YYYY-MM-DD").format("DD MMMM YYYY")} 
<span></span>
</label>
</div>
</div>
</div>
`;
});
containerHolidaysDates.html(htmlData);
}else{
containerHolidaysDates.html(`<div class="w-100 text-center"><p class="mt-4">Por el momento no cuenta con vacaciones agregadas.</p></div>`);
}
$('[data-toggle="kt-tooltip"]').tooltip();
}

function deleteHolidays(){
var ids=[];
$(".check-holidays").each(function() {
if($(this).prop('checked'))
ids.push($(this).attr("data-id"));
});

showOverlay();
$.ajax({
    url: "schedule/delete_holidays",
    type: 'POST',
    data: {
        id:ids,
        _token: $('#token_ajax').val()
    },
    success: function (res) {
        hideOverlay();
        if (res.status == false) {
            sendErrorsShow(res.response);
        } else {
            getDataHolidays();
            showToast(0,res.response,1500);
        }
    },
    error: function (xhr, status, error) {
        hideOverlay();
        $("#modal_add_holidays").modal("hide");
        console.log(JSON.stringify(xhr));
        sendErrorsShow([error]);
    },
});
}

function getDataByRange(tab, pStartDate = false, pEndDate = false) {

var startDateTmp = (pStartDate != false) ? pStartDate.format('YYYY-MM-DD') : startDate.format('YYYY-MM-DD');
var endDateTmp = (pEndDate != false) ? pEndDate.format('YYYY-MM-DD') : endDate.format('YYYY-MM-DD');

    showOverlay();

    $.ajax({
        url: "schedule/get_data_schedule",
        type: 'POST',
        data: {
            employee_selected:employeeSelected,
            date_start: startDateTmp,
            date_end: endDateTmp,
            tab_type_data: 'weekly',
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            hideOverlay();
            dataCalendarSchedule = res;
            dataCalendarScheduleByAccess = $.map(res, function (obj) {
                return $.extend(true, {}, obj);
            });
            $('.tooltip').remove();
            if (tab == 'weekly') {
                reloadTableWeekly();
            }

            $('[data-toggle="kt-tooltip"]').tooltip();

            if(res.length<=0)
            showToast(1,"Aun no se ha establecido tu horario de trabajo de esta semana.",10000);

        },
        error: function (xhr, status, error) {
            hideOverlay();
            dataCalendarSchedule = [];
            dataCalendarScheduleByAccess = [];
            sendErrorsShow([error]);
        },
    });

}

function getDataHolidays() {
        $.ajax({
            url: "schedule/get_data_holidays",
            type: 'POST',
            data: {
                id:employeeSelected,
                _token: $('#token_ajax').val()
            },
            success: function (res) {
            reloadTableHolidays(res.data);
            $("#count-days-holidays").text(res.days);
            },
            error: function (xhr, status, error) {
               
                sendErrorsShow([error]);
            },
        });
}



function applyForHolidays(){

showOverlay();
$.ajax({
    url: "schedule/add_holidays",
    type: 'POST',
    data: {
        start_date:$("#start_date").val(),
        end_date:$("#end_date").val(),
        id:employeeSelected,
        _token: $('#token_ajax').val()
    },
    success: function (res) {
        hideOverlay();
        if (res.status == false) {
            sendErrorsShow(res.response);
        } else {
            $("#modal_add_holidays").modal("hide");
            getDataHolidays();
            showToast(0,res.response,10000);
        }
       
    },
    error: function (xhr, status, error) {
        hideOverlay();
        $("#modal_add_holidays").modal("hide");
        console.log(JSON.stringify(xhr));
        sendErrorsShow([error]);
    },
});

}


//////////////////////////////////////////////////////messages
function emptyTableSchedule(){
    showToast(1,"Aun no se ha establecido tu horario de trabajo, contacta a un administrador para organizar tu horario.",2500);
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