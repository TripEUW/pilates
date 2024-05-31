

    var dataCalendarSession=[];
    var dataCalendarSessionByAccess=[];
    var serialDays=['monday','tuesday','wednesday','thursday','friday'];

    
    var tabs=['monthly','weekly','day','list'];
    var tabInit='weekly';
    var tabSelected=tabInit;
    var tabList=false;
    var startDate=false;
    var endDate=false;
    var local='en';
    var statusActiveSessionClick="disable";

    var textTitleRange="";
    actualTime=false;
    var containerCalendar='session-calendar';
    var actualDaysOfWeek={monday:false,tuesday:false,wednesday:false,thursday:false,friday:false};
    var dataForNewSession= {
        group_selected: '',
        client_selected: '',
        clients_selected:[],
        date_start: '',
        timepicker_start: '',
        timepicker_end: '',
        observation:'',
        serie_days_selected:[],
        _token: $('#token_ajax').val()
    };

    var tabTypeData='all';
 
    var timeOutHover=null;
    var elementSpinnerHover = null;
    
    var flagDroppableSame=null;
    var enableStaticMonth='true';
    function resetElementsDraggable(){

        $(".event-session").draggable(
            {
                helper: 'original',
                appendTo: 'body',
                //snap: ".droppable-cell-session",
                //snapMode: "inner",
                opacity: .8,
                zIndex: 1000,
                revert: function (event, ui) {
                    $(this).data("uiDraggable").originalPosition = {
                        top: 0,
                        left: 0
                    };
                   
                    return !event;
                },drag: function() {
                  
                },
                stop: function(event, ui) {
                   
                }
            }
        );

        $(".droppable-cell-session").droppable({
            hoverClass: "ui-state-active-custom",
            accept:function(element){
              
                flagDroppableSame=$(element).parent();
                if($(element).hasClass('event-session')){
                return true;
                }else{
                return false;
                }
                
            },
            drop: function (event, ui) {
                $( ".event-session" ).off( "click");
                //$(ui.draggable).appendTo(this);
                //$(ui.draggable).attr('style', 'position:relative;');
    
                var id_group = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].group.id;
                var date_start = $(this).attr('data-date');
                var timepicker_start = $(this).attr('data-start');
                var timepicker_end = $(this).attr('data-end');
    
                var date_start_previous = $(ui.draggable).attr('data-date');
                var timepicker_start_previous = $(ui.draggable).attr('data-start');
                var timepicker_end_previous = $(ui.draggable).attr('data-end');
    
                if (typeof timepicker_start === typeof undefined || timepicker_end === false) {
    
                    timepicker_start = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].timepicker_start;
                    timepicker_end = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].timepicker_end;
                    timepicker_start_previous = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].timepicker_start;
                    timepicker_end_previous = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].timepicker_end;
    
                }
    
    
    
                timepicker_start = moment(timepicker_start, 'h:m A').format('h:mm A');
                timepicker_end = moment(timepicker_end, 'h:m A').format('h:mm A');
                timepicker_start_previous = moment(timepicker_start_previous, 'h:m A').format('h:mm A');
                timepicker_end_previous = moment(timepicker_end_previous, 'h:m A').format('h:mm A');
    


                if(
                !($(flagDroppableSame).attr('data-date')==$(this).attr('data-date') &&
                $(flagDroppableSame).attr('data-start')==$(this).attr('data-start') &&
                $(flagDroppableSame).attr('data-end')==$(this).attr('data-end')
                )){
                editGroupSessionForDrag(
                    id_group,
                    date_start,
                    timepicker_start,
                    timepicker_end,
                    date_start_previous,
                    timepicker_start_previous,
                    timepicker_end_previous).then((response) => {
    
                        $(ui.draggable).appendTo(this);
                        $(ui.draggable).attr('style', 'position:relative;');
    
                        getRangeDates();
    
    
                    }).catch(function (reason) {
                        $(ui.draggable).animate({
                            top: "0px",
                            left: "0px"
                        });

                        reloadContextMenu();
                        
                    });
                    resetDrag();
                }else{
                    $(ui.draggable).animate({
                        top: "0px",
                        left: "0px"
                    }, {
                        duration: 500,
                        complete: function () {
                            reloadContextMenu();
                            resetDrag();
                        }
                    });

                  
                }
    
               
            }
        });

        $(".event-session").droppable({
            hoverClass: "ui-state-active-custom",
            accept:'.drag-move-session',
            drop: function (event, ui) {
                if (($(this)[0].hasAttribute("data-session-status"))) {
                    if ($(this).attr("data-session-status") == "enable") {

                        sessionsMoveSelected = [];
                        var id_session = $(ui.draggable).attr('data-id-session');
                        // $(ui.draggable).remove();
                        sessionsMoveSelected.push(id_session);


                        var key = $(this).attr("data-group-key");

                        var date_start = dataCalendarSessionByAccess[key].date_start;
                        var timepicker_start = dataCalendarSessionByAccess[key].timepicker_start;
                        var timepicker_end = dataCalendarSessionByAccess[key].timepicker_end;
                        var id_group = dataCalendarSessionByAccess[key].group.id;
                        moveSessions(date_start, timepicker_start, timepicker_end, id_group);


                        resetDrag();
                    } else {
                        showToast(1, "Accion no permitida, este grupo de sesiones ya ha terminado.", 2000);
                    }
                }

            }
        });

    }
      

    function resetDrag(){
      
        $(".event-session").draggable(
            {
                helper: 'original',
                appendTo: 'body',
                //snap: ".droppable-cell-session",
                //snapMode: "inner",
                opacity: .8,
                zIndex: 1000,
                revert: function (event, ui) {
                    $(this).data("uiDraggable").originalPosition = {
                        top: 0,
                        left: 0
                    };
                   
                    return !event;
                }, drag: function () {
                  //  $( ".event-session" ).off( "click");
                },
                stop: function (event, ui) {
              
                
                }
            }
        );

    }

 

    function reloadContextMenu(){
        $.contextMenu('destroy');
   /* $.contextMenu({
        selector: '.droppable-cell-session',
        callback: function (key, options) {
           var day = options.$trigger.attr("data-day");
           var date = options.$trigger.attr("data-date");

           var timeStart= false;
           var timeEnd= false;

           var attrStart=options.$trigger.attr('data-start');
           var attrEnd=options.$trigger.attr('data-end');

          if (typeof attrStart !== typeof undefined && attrStart !== false)
           timeStart=attrStart;

           if (typeof attrEnd !== typeof undefined && attrEnd !== false)
           timeEnd=attrEnd;

           if(key=='add-new-group-session'){
            addNewGroupSession(day,date,timeStart,timeEnd);
           }else if(key=='add-new-session'){
            addNewSession(day,date,timeStart,timeEnd);
           }

        },
        items: (((dataCalendarSession.length<=0 || tabSelected=='monthly' ))?{
        //is empty
        "add-new-group-session": { name: "Agregar nueva sesión", icon: "add" },
        }
        :
        {
        "add-new-group-session": { name: "Agregar nueva sesión", icon: "add" },
        "add-new-session": { name: "Agregar nueva sesión en este horario", icon: "add" },
        }
        )
    });*/


    $.contextMenu({
        selector: '.event-session',
        callback: function (actions, options) {
            var key = options.$trigger.attr("data-group-key");
           
            if(actions=='add'){

            
                clientSelected = null;
                groupSelected = null;
    
                groupSelected = dataCalendarSessionByAccess[key].group;
    
              var date_startp = dataCalendarSessionByAccess[key].date_start;
              var timepicker_startp = dataCalendarSessionByAccess[key].timepicker_start;
              var timepicker_endp = dataCalendarSessionByAccess[key].timepicker_end;

                dataForNewSession = {
                    group_selected: '',
                    client_selected: '',
                    clients_selected:[],
                    date_start: date_startp,
                    timepicker_start: timepicker_startp,
                    timepicker_end: timepicker_endp,
                    observation: '',
                    serie_days_selected:[],
                    _token: $('#token_ajax').val()
                };
                $("#client-name-2").val("");
                clientSelected=null;
                clientsSelected=[];
                reloadTableSelectedClients();
                $("#modal_add_new_session").modal("show");

            }else if(actions=='edit'){

        

                var date_startp = dataCalendarSessionByAccess[key].date_start;
                var timepicker_startp = dataCalendarSessionByAccess[key].timepicker_start;
                var timepicker_endp = dataCalendarSessionByAccess[key].timepicker_end;
                var name_group = dataCalendarSessionByAccess[key].group.name;
                var id_group = dataCalendarSessionByAccess[key].group.id;
                var level_group = dataCalendarSessionByAccess[key].group.level;
                var name_room = dataCalendarSessionByAccess[key].room.name;
                var type_room =dataCalendarSessionByAccess[key].room.type_room;
                var observation_group=dataCalendarSessionByAccess[key].group.observation;
                var status=dataCalendarSessionByAccess[key].group.status_format;

               
                var name_employee=`${dataCalendarSessionByAccess[key].group.name_employee} ${dataCalendarSessionByAccess[key].group.last_name_employee}`;
                var id_employee= dataCalendarSessionByAccess[key].group.id_employee;

                if(dataCalendarSessionByAccess[key].group.name_employee==null || dataCalendarSessionByAccess[key].group.last_name_employee==null)
                name_employee="";

                if(status=='Completo'){
                $("#status-edit").html(`<div class="status-red p-1 text-center">`+status+`</div>`);
                }else if(status=='Vacío'){
                $("#status-edit").html(`<div class="status-blue p-1 text-center">`+status+`</div>`);
                }else{
                $("#status-edit").html(`<div class="status-green p-1 text-center">`+status+`</div>`);
                }

            $("#level-edit").text("Nivel: "+level_group);
            $("#date_start_edit").val(date_startp);

            $("#timepicker_start_edit").timepicker('setTime',timepicker_startp);
            $("#timepicker_end_edit").timepicker('setTime',timepicker_endp);

            $("#date_start1").val(date_startp);
            $('#timepicker_start1').timepicker('setTime', timepicker_startp);
            $('#timepicker_end1').timepicker('setTime',timepicker_endp);
            $("#employee-name-edit").val(name_employee);

                groupSelected = dataCalendarSessionByAccess[key].group;
                employeeSelectedId = id_employee;
                employeeSelectedName = name_employee;
            

            $("#date_start_edit_previous").val(date_startp);
            $("#timepicker_start_edit_previous").timepicker('setTime',timepicker_startp);
            $("#timepicker_end_edit_previous").timepicker('setTime',timepicker_endp);

            
            $("#group-id-1-edit").val(id_group);
            $("#group-name-edit").val(name_group);
            $("#room-name-edit").val(name_room);
            $("#room-type-edit").val(type_room);
            $("#group-observation-edit").text(observation_group);

            
            showModalEditGroupSessions();
            //$("#modal_edit_group_session").modal("show");
            }else if(actions=='delete'){
                var date_startp = dataCalendarSessionByAccess[key].date_start;
                var timepicker_startp = dataCalendarSessionByAccess[key].timepicker_start;
                var timepicker_endp = dataCalendarSessionByAccess[key].timepicker_end;
                var id_group = dataCalendarSessionByAccess[key].group.id;

                deleteGroupSessions(date_startp,timepicker_startp,timepicker_endp,id_group);
               
        
            }else if(actions=='move_session'){
                var date_start = dataCalendarSessionByAccess[key].date_start;
                var timepicker_start = dataCalendarSessionByAccess[key].timepicker_start;
                var timepicker_end = dataCalendarSessionByAccess[key].timepicker_end;
                var id_group = dataCalendarSessionByAccess[key].group.id;
                moveSessions(date_start,timepicker_start,timepicker_end,id_group);
            }
        },
        items: {
            "add": { name: "Agregar sesiones", icon: "add",disabled:function(key,options){ if(options.$trigger[0].hasAttribute("data-session-status")){ if(options.$trigger.attr("data-session-status")=="enable"){return false}else{return true} }else{return true}} },
            "edit": { name: "Editar", icon: "edit" ,disabled:function(key,options){ if(options.$trigger[0].hasAttribute("data-session-status")){ if(options.$trigger.attr("data-session-status")=="enable"){return false}else{return true} }else{return true}} },
            "delete": { name: "Eliminar", icon: "delete" },
            "move_session": { name: "Mover sesión aquí ", icon:  `fa fa fa-arrow-down`, disabled:function(key,options){ if(options.$trigger[0].hasAttribute("data-session-status")){ if(options.$trigger.attr("data-session-status")=="enable"){if(sessionsMoveSelected.length<=0)return true; return false;}else{return true} }else{return true}} },
        },
        events: {
            show : function(options){
               
            if(options.$trigger[0].hasAttribute("data-session-status")){ 
                if(options.$trigger.attr("data-session-status")=="disable"){
                    showToast(1,"Este grupo de sesiones ya ha terminado, puede ver las sesiones que tuvo dando clic izquierdo o eliminarla dando clic derecho.",5500);
                }
            } 
           }

        }
 
        
    });

    $.contextMenu({
        selector: '.event-session-default',
        callback: function (actions, options) {

            var data_date = options.$trigger.attr("data-date");
            var data_start = options.$trigger.attr("data-start");
            var data_end = options.$trigger.attr("data-end");

            if(actions=='lock'){
            lockAddGroupSession(data_date,  moment(data_start, 'h:mm A').format('h:mm A'),  moment(data_end, 'h:mm A').format('h:mm A'),true);
            }else{
            lockAddGroupSession(data_date,  moment(data_start, 'h:mm A').format('h:mm A'),  moment(data_end, 'h:mm A').format('h:mm A'),false);
            }

        },
        items: (
            {
            "lock": { name: "Bloquear", icon:  `fa fa fa-lock`,disabled: function(key, options){return false;}  },
            "unlock": { name: "Desbloquear ", icon:  `fa fa fa-lock-open`,disabled: function(key, options){return false;} },

            }
        )
    });

    $(".event-session-default.event-session-default-unlock").click(function(){
        var day = $(this).attr("data-day");
        var date = $(this).attr("data-date");

        var timeStart= false;
        var timeEnd= false;

        var attrStart=$(this).attr('data-start');
        var attrEnd=$(this).attr('data-end');

       if (typeof attrStart !== typeof undefined && attrStart !== false)
        timeStart=attrStart;

        if (typeof attrEnd !== typeof undefined && attrEnd !== false)
        timeEnd=attrEnd;
        //addNewGroupSession(day,date,timeStart,timeEnd);
        addNewSession(day,date,timeStart,timeEnd);
    });

    $('.event-session').on('click', function(e) {

        if($(this)[0].hasAttribute("data-session-status")){ 
            if($(this).attr("data-session-status")=="disable"){
                statusActiveSessionClick="disable";
            }else{
                statusActiveSessionClick="enable";
            }
        } 

        e.stopPropagation();
        loadSessionsGroupModal(this);
    });
    $('.droppable-cell-session').on('click', function(e) {
        e.stopPropagation();
       hideShowPanelLeft(false);
    });
    /*
    elementSpinnerHover = document.createElement('div');
    elementSpinnerHover.setAttribute('class', `btn btn-brand btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light custom-spinner-hover`);

    $('.event-session').hover(function () {   
        var element=this;
        element.appendChild(elementSpinnerHover);
        timeOutHover=setTimeout(function(){ loadSessionsGroupModal(element); elementSpinnerHover.remove(); }, 1000);       
    },function (e) {
        if(timeOutHover!=null)
        clearTimeout(timeOutHover);
        elementSpinnerHover.remove();
        //hideShowPanelLeft(false);
    });
    */
    }





    generateTable();

    function generateTable(){

        actualTime = moment();
        //actualTime.locale(local);
        getRangeDates();

    }

    function getRangeDates(){

    if(tabSelected=='weekly'){

        var monday = actualTime.clone().weekday(1);
        var friday = actualTime.clone().weekday(5);

        actualDaysOfWeek[0]=monday.format('YYYY-MM-DD');
        actualDaysOfWeek[1]=actualTime.clone().weekday(2).format('YYYY-MM-DD');
        actualDaysOfWeek[2]=actualTime.clone().weekday(3).format('YYYY-MM-DD');
        actualDaysOfWeek[3]=actualTime.clone().weekday(4).format('YYYY-MM-DD');
        actualDaysOfWeek[4]=friday.format('YYYY-MM-DD');

        var isNowWeekday = actualTime.isBetween(monday, friday, null, '[]');

    startDate=monday;
    endDate=friday;
       

    textTitleRange= `${monday.format('MMMM DD')}, ${monday.format('YYYY')} - ${friday.format('MMMM DD')}, ${friday.format('YYYY')}`;
    textTitleRange=textTitleRange.toUpperCase();
  
  

    $("#text-title-range").text(textTitleRange);

    if(tabList){
        getDataByRange('list');
    }else{
        getDataByRange(tabSelected);
    }
  
    }else if(tabSelected=='monthly'){

    var first = actualTime.clone().startOf('month');
    var last = actualTime.clone().endOf('month');



    startDateMonday=first.clone().weekday(1);
    var endDateMoreDays=last.clone();
    var diffDays=moment.duration(first.clone().diff(startDateMonday)).asDays();

    if(diffDays<0){
        diffDays=diffDays*(-1);
        endDateMoreDays.add((diffDays), 'days');
    }else{
        endDateMoreDays.add(diffDays, 'days');
    }

    var diffDaysRest=startDateMonday.clone().add(diffDays, 'days').format('YYYY-MM-DD');
    var fridayLast=moment(diffDaysRest).clone().weekday(5).format('YYYY-MM-DD');
    var lastDiff=moment.duration(moment(fridayLast).diff(moment(diffDaysRest))).asDays();
    endDateMoreDays.add(lastDiff,'days');


    startDate=first;
    endDate=last;

    if(tabList){
    tabSelected='weekly';
    actualTime=first;
    getRangeDates();
    }else{
    textTitleRange= `${first.format('MMMM YYYY')}`;
    textTitleRange=textTitleRange.toUpperCase();
    $("#text-title-range").text(textTitleRange);  
    getDataByRange(tabSelected,startDateMonday,endDateMoreDays.clone().add(1, 'days'));
    }
    

    }else if(tabSelected=='day'){

 
    if(actualTime.clone().startOf('day').weekday(0).format('MMMM DD, YYYY')==actualTime.clone().startOf('day').format('MMMM DD, YYYY')){
     actualTime=actualTime.clone().add(1,'days');
    }
     
    if(actualTime.clone().startOf('day').weekday(6).format('MMMM DD, YYYY')==actualTime.clone().startOf('day').format('MMMM DD, YYYY')){
    actualTime=actualTime.clone().subtract(1,'days');
    }

    var first = actualTime.clone().startOf('day');
    var last = actualTime.clone().endOf('day');


    startDate=first;
    endDate=last;

    

    if(tabList){
    tabSelected='weekly';
    actualTime=first;
    getRangeDates();
    }else{
        textTitleRange= `${first.format('MMMM DD, YYYY')}`;
        textTitleRange=textTitleRange.toUpperCase();
      
        $("#text-title-range").text(textTitleRange);
    
        getDataByRange(tabSelected);
    }

    }
    
    loadItineraryDates();
    }

    $('#modal_print_itinerary').on('show.bs.modal', function(e) {
        $("#content-itinerary-action").html(``);
    });

    function loadItineraryDates(){
        var  actualDaysOfWeekIti=[];

        var monday = actualTime.clone().weekday(1);
        var friday = actualTime.clone().weekday(5);

        actualDaysOfWeekIti[0]=monday.format('DD/MM/YYYY');
        actualDaysOfWeekIti[1]=actualTime.clone().weekday(2).format('DD/MM/YYYY');
        actualDaysOfWeekIti[2]=actualTime.clone().weekday(3).format('DD/MM/YYYY');
        actualDaysOfWeekIti[3]=actualTime.clone().weekday(4).format('DD/MM/YYYY');
        actualDaysOfWeekIti[4]=friday.format('DD/MM/YYYY');

        var htmlDates=``;

        actualDaysOfWeekIti.forEach(element => {
            htmlDates+=`<option value="${element}" >${element}</option>`;
        });
        $("#itinerary-select").html(htmlDates);

    }

    function printItinerary(){

        showOverlay();
     
        $.ajax({
            url: "dashboard/print_itinerary",
            type: 'POST',
            data: {
                date:$("#itinerary-select").val(),
                mode_static:enableStaticMonth,
                _token: $('#token_ajax').val()
            },
            success: function (res) {
    
                if (res.status == false) {
                    hideOverlay();
                    sendErrorsShow(res.response);
                } else {
                    $("#content-itinerary-action").html(`
                    <a  href="${res.response}" target="_blank"  class="btn btn-primary d-inline-block"><i class="fa fa-print"></i> Imprimir Itinerario</a>
                    <a  href="${res.response}"  class="btn btn-primary d-inline-block" download><i class="fa fa-download"></i> Descargar Itinerario</a>
                    `);
                    hideOverlay();
                    
                }
            
            },
            error: function (xhr, status, error) {
               hideOverlay();
                console.log(JSON.stringify(xhr));
                sendErrorsShow([error]);
            },
        });
    }

    $("#btn-back-time-session").click(function(){
        if(tabSelected=='monthly'){
            actualTime=endDate.subtract(startDate.clone().daysInMonth(), 'days');
           
        }else if(tabSelected=='weekly'){
            actualTime=startDate.subtract(5, 'days');
          
        }else if(tabSelected=='day'){
            actualTime=startDate.subtract(1, 'days');
        while (
            actualTime.clone().weekday(0).format('MMMM DD, YYYY')==actualTime.clone().format('MMMM DD, YYYY')
            ||
            actualTime.clone().weekday(6).format('MMMM DD, YYYY')==actualTime.clone().format('MMMM DD, YYYY')
            ) {
            actualTime=actualTime.subtract(1,'days');
            }
           
        }
     
        getRangeDates();
   
    });

    $("#btn-next-time-session").click(function(){

        if(tabSelected=='monthly'){
            actualTime= startDate.clone().add(1, 'months').clone().startOf('month')
          
        }else if(tabSelected=='weekly'){
            actualTime=endDate.add(5, 'days');
            
        }else if(tabSelected=='day'){
            actualTime=endDate.add(1, 'days');
            while (
            actualTime.clone().weekday(0).format('MMMM DD, YYYY')==actualTime.clone().format('MMMM DD, YYYY')
            ||
            actualTime.clone().weekday(6).format('MMMM DD, YYYY')==actualTime.clone().format('MMMM DD, YYYY')
            ) {
            actualTime=actualTime.add(1,'days');
            }    
           
        }
        getRangeDates();

    });

    $("#btn-now-time-session").click(function(){
        actualTime=moment();      
        getRangeDates();
    });

  
$("#btn-sw-monthly").click(function(){
$("#btn-sw-monthly").addClass('active-type-calendar');
$("#btn-sw-weekly").removeClass('active-type-calendar');
$("#btn-sw-day").removeClass('active-type-calendar');
$("#btn-sw-list").removeClass('active-type-calendar');
tabSelected='monthly';
tabList=false;
getRangeDates();
});

$("#btn-sw-weekly").click(function(){
$("#btn-sw-monthly").removeClass('active-type-calendar');
$("#btn-sw-weekly").addClass('active-type-calendar');
$("#btn-sw-day").removeClass('active-type-calendar');
$("#btn-sw-list").removeClass('active-type-calendar');
tabSelected='weekly';
tabList=false;
getRangeDates();
});

$("#btn-sw-day").click(function(){
$("#btn-sw-monthly").removeClass('active-type-calendar');
$("#btn-sw-weekly").removeClass('active-type-calendar');
$("#btn-sw-day").addClass('active-type-calendar');
$("#btn-sw-list").removeClass('active-type-calendar');
tabSelected='day';
tabList=false;
getRangeDates();
});

$("#btn-sw-list").click(function(){

$("#btn-sw-monthly").removeClass('active-type-calendar');
$("#btn-sw-weekly").removeClass('active-type-calendar');
$("#btn-sw-day").removeClass('active-type-calendar');
$("#btn-sw-list").addClass('active-type-calendar');
tabList=true;
getRangeDates();
});

function setDaySelect(date){
actualTime=moment(date);
$("#btn-sw-monthly").removeClass('active-type-calendar');
$("#btn-sw-weekly").removeClass('active-type-calendar');
$("#btn-sw-day").addClass('active-type-calendar');
$("#btn-sw-list").removeClass('active-type-calendar');
tabSelected='day';
tabList=false;
getRangeDates();
}


$("#btn-sw-pilates").click(function(){
$("#btn-sw-pilates").addClass('active-type-calendar');
$("#btn-sw-all").removeClass('active-type-calendar');
$("#btn-sw-physiotherapy").removeClass('active-type-calendar');
tabTypeData='pilates';
getRangeDates();
});

$("#btn-sw-physiotherapy").click(function(){
$("#btn-sw-pilates").removeClass('active-type-calendar');
$("#btn-sw-all").removeClass('active-type-calendar');
$("#btn-sw-physiotherapy").addClass('active-type-calendar');
tabTypeData='physiotherapy';
getRangeDates();
});


$("#btn-sw-all").click(function(){
$("#btn-sw-pilates").removeClass('active-type-calendar');
$("#btn-sw-physiotherapy").removeClass('active-type-calendar');
$("#btn-sw-all").addClass('active-type-calendar');
tabTypeData='all';
getRangeDates();
});

! function(a) { a.fn.datepicker.dates.es = { days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"], daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"], daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"], monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"], today: "Hoy", monthsTitle: "Meses", clear: "Borrar", weekStart: 1, format: "dd/mm/yyyy" } }(jQuery);
$('#kt_datepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight: true,
    language: 'es',
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>',
    },
});

$('#kt_datepicker2').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight: true,
    language: 'es',
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>',
    },
});

$("#btn_search").click(function() {
    tabelGroupsSessions.search("");
    tabelGroupsSessions.ajax.reload(); 
});

$("#btn_search_move").click(function() {
    tabelGroupsSessions2.search("");
    tabelGroupsSessions2.ajax.reload(); 
});




function reloadTableDay(){

    var container = document.getElementById(containerCalendar);
    container.innerHTML = "";

    var classForTable = 'session-calendar-table-weekly';
    var classForCellTime = 'time-cell-session-list';
    var classForTh = 'cell-session-th';
    var classForStartTime = 'time';
    var classForEndTime = 'time';
    var classForToTime = 'to-time';
    var classForDroppableCell = 'droppable-cell-session';
    var classForSession = 'event-session w-100-force';
    var classForIcon = 'icon-session';
    var classForDefaultEvent ='event-session-default w-100-force';

    var th, td, tbody, thead, tr, table, text, span, div,img;

    var table = document.createElement('table');
    table.setAttribute('class', classForTable);

    //thead
    thead = document.createElement('thead');

    tr = document.createElement('tr');
    th = document.createElement('th');
    th.setAttribute('class', classForCellTime);
    text = document.createTextNode("Hora");
    th.appendChild(text);
    tr.appendChild(th);

    var actualWeekNameDay=startDate.clone().format('YYYY-MM-DD');

    
    if(startDate.clone().weekday(1).format('YYYY-MM-DD') == actualWeekNameDay || endDate.clone().weekday(1).format('YYYY-MM-DD') == actualWeekNameDay){
    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Lunes " + moment(startDate.clone().weekday(1)).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);
     }

     else if(startDate.clone().weekday(2).format('YYYY-MM-DD') == actualWeekNameDay || endDate.clone().weekday(2).format('YYYY-MM-DD') == actualWeekNameDay){

    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Martes " + moment(startDate.clone().weekday(2)).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);
     }

     else if(startDate.clone().weekday(3).format('YYYY-MM-DD') == actualWeekNameDay || endDate.clone().weekday(3).format('YYYY-MM-DD') == actualWeekNameDay){
    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Miércoles " + moment(startDate.clone().weekday(3)).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);
     }

     else if(startDate.clone().weekday(4).format('YYYY-MM-DD') == actualWeekNameDay || endDate.clone().weekday(4).format('YYYY-MM-DD') == actualWeekNameDay){
    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Jueves " + moment(startDate.clone().weekday(4)).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);
     }

     else if(startDate.clone().weekday(5).format('YYYY-MM-DD') == actualWeekNameDay || endDate.clone().weekday(5).format('YYYY-MM-DD') == actualWeekNameDay){
    th = document.createElement('th');
    th.setAttribute('class', classForTh);
    text = document.createTextNode("Viernes " + moment(startDate.clone().weekday(5)).format('DD/MM'));
    th.appendChild(text);
    tr.appendChild(th);
     }

    thead.appendChild(tr);
    //end thead
    //start tbody
    tbody = document.createElement('tbody');

    dataCalendarSession.forEach(function (item, key) {

       



        if(!item.is_default){

        //var dateFirstItem=moment(item.sessions[0].date_start).format('DD/MM/YYYY')
        var timeStartItem = moment(item.sessions[0].date_start).format('hh:mm A')
        var timeEndItem = moment(item.sessions[0].date_end).format('hh:mm A')

        tr = document.createElement('tr');

        td = document.createElement('td');
        td.setAttribute('class', classForCellTime);

        span = document.createElement('span');
        span.setAttribute('class', classForStartTime);
        text = document.createTextNode(moment(timeStartItem, "hh:mm A").format("HH:mm"));
        span.appendChild(text);
        td.appendChild(span);
        span = document.createElement('span');
        span.setAttribute('class', classForToTime);
        text = document.createTextNode('a');
        span.appendChild(text);
        td.appendChild(span);
        span = document.createElement('span');
        span.setAttribute('class', classForEndTime);
        text = document.createTextNode(moment(timeEndItem, "hh:mm A").format("HH:mm"));
        span.appendChild(text);
        td.appendChild(span);
        tr.appendChild(td);

            td = document.createElement('td');
            td.setAttribute('class', classForDroppableCell);
            td.setAttribute('data-day', '1');
            td.setAttribute('data-date', startDate.clone().format('YYYY-MM-DD'));
            td.setAttribute('data-start',moment(timeStartItem, "hh:mm A").format("h:m A"));
            td.setAttribute('data-end',moment(timeEndItem, "hh:mm A").format("h:m A") );

            dataCalendarSession.forEach(function (subItem, key2) {

                var startTmp = moment(subItem.time_start).format('YYYY-MM-DD hh:mm A');
                var endTmp = moment(subItem.time_end).format('YYYY-MM-DD hh:mm A');

                var startTmp2 = moment(startDate).format('YYYY-MM-DD') + " " + timeStartItem;
                var endTmp2 = moment(endDate).format('YYYY-MM-DD') + " " + timeEndItem;

                if ((startTmp == startTmp2) && (endTmp == endTmp2)) {
                    var statusClass='';
                    if(subItem.group.status==1){
                        statusClass='full';
                    }else if(subItem.group.status==2){
                        statusClass='free-spaces';
                    }else if(subItem.group.status==3){
                        statusClass='empty';
                    }
                    div = document.createElement('div');
                    div.setAttribute('class', classForSession+" "+statusClass+" "+((!subItem.status_employee)?"not-employee-border":"")+" "+subItem.status );
                    div.setAttribute('id', key2);
                    div.setAttribute('data-container', "#"+key2);
                    div.setAttribute('data-toggle', "kt-tooltip");
                    div.setAttribute('data-placement', "top");
                    div.setAttribute('data-original-title', subItem.room.name);

                    div.setAttribute('data-group-key', key2);
                    div.setAttribute('data-date', moment(subItem.time_start).format('YYYY-MM-DD'));
                    div.setAttribute('data-start', moment(timeStartItem, "hh:mm A").format("h:m A"));
                    div.setAttribute('data-end', moment(timeEndItem, "hh:mm A").format("h:m A"));
                    div.setAttribute('data-session-status', subItem.status);

                    img = document.createElement('img');
                    img.setAttribute('class', classForIcon);
                    var tmpIconName='';
                    if(subItem.room.type_room=='Máquina'){
                        tmpIconName='equipo-de-pilates.png';
                    }else if(subItem.room.type_room=='Suelo'){
                        tmpIconName='pilates.png';
                    }else if(subItem.room.type_room=='Camilla'){
                        tmpIconName='fisioterapia.png';
                    }
                    img.setAttribute('src',routePublicImages+tmpIconName);
                    div.appendChild(img);
                    td.appendChild(div);
                    delete dataCalendarSession[key2];
                }
            });

            var lock=false;
            var dateTmpStart=startDate.clone().format('YYYY-MM-DD')+" "+moment(timeStartItem, "hh:mm A").format("HH:mm:ss");
            var dateTmpEnd=startDate.clone().format('YYYY-MM-DD')+" "+moment(timeEndItem, "hh:mm A").format("HH:mm:ss");
            item.locks.forEach(lockItem => {if(lockItem.date_start==dateTmpStart && lockItem.date_end==dateTmpEnd)lock=true;});
            var classLock=(lock)?"event-session-default-lock":"event-session-default-unlock";
            //event add sessions start
            div = document.createElement('div');
            div.setAttribute('class', `${classForDefaultEvent} ${classLock}`);
            div.setAttribute('data-date',startDate.clone().format('YYYY-MM-DD'));
            div.setAttribute('data-start', moment(timeStartItem, "hh:mm A").format("h:m A"));
            div.setAttribute('data-end', moment(timeEndItem, "hh:mm A").format("h:m A"));
            img = document.createElement('img');
            img.setAttribute('class', classForIcon);
            img.setAttribute('src', routePublicImages + 'cross_black.png');
            div.appendChild(img);
            td.appendChild(div);
            //event add sessions end

        tr.appendChild(td);
        tbody.appendChild(tr);
        }else{

            //default
            var timeStartItemDefault = moment(item.time_start).format('hh:mm A')
            var timeEndItemDefault = moment(item.time_end).format('hh:mm A')
            tr = document.createElement('tr');
            td = document.createElement('td');
            td.setAttribute('class', classForCellTime);

            span = document.createElement('span');
            span.setAttribute('class', classForStartTime);
            text = document.createTextNode(moment(timeStartItemDefault, "hh:mm A").format("HH:mm"));
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForToTime);
            text = document.createTextNode('a');
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForEndTime);
            text = document.createTextNode(moment(timeEndItemDefault, "hh:mm A").format("HH:mm"));
            span.appendChild(text);
            td.appendChild(span);
            tr.appendChild(td);


            td = document.createElement('td');
            td.setAttribute('class', classForDroppableCell);
            td.setAttribute('data-day', '1');
            td.setAttribute('data-date', startDate.clone().format('YYYY-MM-DD'));
            td.setAttribute('data-start', moment(timeStartItemDefault, "hh:mm A").format("h:m A"));
            td.setAttribute('data-end',moment(timeEndItemDefault, "hh:mm A").format("h:m A") );


            var lock=false;
            var dateTmpStart=startDate.clone().format('YYYY-MM-DD')+" "+moment(timeStartItemDefault, "hh:mm A").format("HH:mm:ss");
            var dateTmpEnd=startDate.clone().format('YYYY-MM-DD')+" "+moment(timeEndItemDefault, "hh:mm A").format("HH:mm:ss");
            item.locks.forEach(lockItem => {if(lockItem.date_start==dateTmpStart && lockItem.date_end==dateTmpEnd)lock=true;});
            var classLock=(lock)?"event-session-default-lock":"event-session-default-unlock";
            //event add sessions start
            div = document.createElement('div');
            div.setAttribute('class', `${classForDefaultEvent} ${classLock}`);
            div.setAttribute('data-date',startDate.clone().format('YYYY-MM-DD'));
            div.setAttribute('data-start', moment(timeStartItemDefault, "hh:mm A").format("h:m A"));
            div.setAttribute('data-end', moment(timeEndItemDefault, "hh:mm A").format("h:m A"));
            img = document.createElement('img');
            img.setAttribute('class', classForIcon);
            img.setAttribute('src', routePublicImages + 'cross_black.png');
            div.appendChild(img);
            td.appendChild(div);
            //event add sessions end

            tr.appendChild(td);
            
            tbody.appendChild(tr);
            
        }

   
    });

    if (dataCalendarSession.length <= 0) {

       
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


            
                td = document.createElement('td');
                td.setAttribute('class', classForDroppableCell);
                td.setAttribute('data-day', '1');
                td.setAttribute('data-date', startDate.clone().format('YYYY-MM-DD'));
            tr.appendChild(td);
            
            tbody.appendChild(tr);
        

     
    }


    table.appendChild(thead);
    table.appendChild(tbody);

    container.appendChild(table);

}

    function reloadTableWeekly(){

        var container = document.getElementById(containerCalendar);
        container.innerHTML = "";

        var classForTable = 'session-calendar-table-weekly';
        var classForCellTime = 'time-cell-session';
        var classForTh = 'cell-session-th hover-click';
        var classForStartTime = 'time';
        var classForEndTime = 'time';
        var classForToTime = 'to-time';
        var classForDroppableCell = 'droppable-cell-session';
        var classForSession = 'event-session';
        var classForIcon = 'icon-session';
        var classForDefaultEvent = 'event-session-default';

        var th, td, tbody, thead, tr, table, text, span, div,img;

        var table = document.createElement('table');
        table.setAttribute('class', classForTable);

        //thead
        thead = document.createElement('thead');

        tr = document.createElement('tr');
        th = document.createElement('th');
        th.setAttribute('class', classForCellTime);
        text = document.createTextNode("Hora");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        th.setAttribute('onclick', "setDaySelect('"+moment(actualDaysOfWeek[0]).format('YYYY-MM-DD')+"')");

        text = document.createTextNode("Lunes " + moment(actualDaysOfWeek[0]).format('DD/MM'));
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        th.setAttribute('onclick', "setDaySelect('"+moment(actualDaysOfWeek[1]).format('YYYY-MM-DD')+"')");
        text = document.createTextNode("Martes " + moment(actualDaysOfWeek[1]).format('DD/MM'));
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        th.setAttribute('onclick', "setDaySelect('"+moment(actualDaysOfWeek[2]).format('YYYY-MM-DD')+"')");
        text = document.createTextNode("Miércoles " + moment(actualDaysOfWeek[2]).format('DD/MM'));
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        th.setAttribute('onclick', "setDaySelect('"+moment(actualDaysOfWeek[3]).format('YYYY-MM-DD')+"')");
        text = document.createTextNode("Jueves " + moment(actualDaysOfWeek[3]).format('DD/MM'));
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        th.setAttribute('onclick', "setDaySelect('"+moment(actualDaysOfWeek[4]).format('YYYY-MM-DD')+"')");
        text = document.createTextNode("Viernes " + moment(actualDaysOfWeek[4]).format('DD/MM'));
        th.appendChild(text);
        tr.appendChild(th);

        thead.appendChild(tr);
        //end thead
        //start tbody
        tbody = document.createElement('tbody');

        dataCalendarSession.forEach(function (item, key) {

            if(!item.is_default){

            //var dateFirstItem=moment(item.sessions[0].date_start).format('DD/MM/YYYY')
            var timeStartItem = moment(item.sessions[0].date_start).format('hh:mm A')
            var timeEndItem = moment(item.sessions[0].date_end).format('hh:mm A')

            tr = document.createElement('tr');

            td = document.createElement('td');
            td.setAttribute('class', classForCellTime);

            span = document.createElement('span');
            span.setAttribute('class', classForStartTime);
            text = document.createTextNode(moment(timeStartItem, "hh:mm A").format("HH:mm"));
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForToTime);
            text = document.createTextNode('a');
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForEndTime);
            text = document.createTextNode(moment(timeEndItem, "hh:mm A").format("HH:mm"));
            span.appendChild(text);
            td.appendChild(span);
            tr.appendChild(td);


            for (let day = 0; day < 5; day++) {
                td = document.createElement('td');
                td.setAttribute('class', classForDroppableCell);
                td.setAttribute('data-day', '1');
                td.setAttribute('data-date', actualDaysOfWeek[day]);
                td.setAttribute('data-start', moment(timeStartItem, "hh:mm A").format("h:m A"));
                td.setAttribute('data-end',moment(timeEndItem, "hh:mm A").format("h:m A") );
                dataCalendarSession.forEach(function (subItem, key2) {

                    var startTmp = moment(subItem.time_start).format('YYYY-MM-DD hh:mm A');
                    var endTmp = moment(subItem.time_end).format('YYYY-MM-DD hh:mm A');

                    var startTmp2 = moment(actualDaysOfWeek[day]).format('YYYY-MM-DD') + " " + timeStartItem;
                    var endTmp2 = moment(actualDaysOfWeek[day]).format('YYYY-MM-DD') + " " + timeEndItem;

                    if ((startTmp == startTmp2) && (endTmp == endTmp2)) {
                        var statusClass='';
                        if(subItem.group.status==1){
                            statusClass='full';
                        }else if(subItem.group.status==2){
                            statusClass='free-spaces';
                        }else if(subItem.group.status==3){
                            statusClass='empty';
                        }

                    
                        div = document.createElement('div');
                        div.setAttribute('class', classForSession+" "+statusClass+" "+((!subItem.status_employee)?"not-employee-border":"")+" "+subItem.status);
                        div.setAttribute('id', key2);
                        div.setAttribute('data-container', "#"+key2);
                        div.setAttribute('data-toggle', "kt-tooltip");
                        div.setAttribute('data-placement', "top");
                        div.setAttribute('data-original-title', subItem.room.name);
                        div.setAttribute('data-employee-status', subItem.status_employee);
                        div.setAttribute('data-session-status', subItem.status);

                        

                        div.setAttribute('data-group-key', key2);
                        div.setAttribute('data-date', moment(actualDaysOfWeek[day]).format('YYYY-MM-DD'));
                        div.setAttribute('data-start',moment(timeStartItem, "hh:mm A").format("h:m A") );
                        div.setAttribute('data-end',moment(timeEndItem, "hh:mm A").format("h:m A"));

                        img = document.createElement('img');
                        img.setAttribute('class', classForIcon);
                        var tmpIconName='';
                        if(subItem.room.type_room=='Máquina'){
                            tmpIconName='equipo-de-pilates.png';
                        }else if(subItem.room.type_room=='Suelo'){
                            tmpIconName='pilates.png';
                        }else if(subItem.room.type_room=='Camilla'){
                            tmpIconName='fisioterapia.png';
                        }
                        img.setAttribute('src',routePublicImages+tmpIconName);
                        div.appendChild(img);
                        td.appendChild(div);
                        delete dataCalendarSession[key2];
                    }
                });

                var lock=false;
                var dateTmpStart=actualDaysOfWeek[day]+" "+moment(timeStartItem, "hh:mm A").format("HH:mm:ss");
                var dateTmpEnd=actualDaysOfWeek[day]+" "+moment(timeEndItem, "hh:mm A").format("HH:mm:ss");
                item.locks.forEach(lockItem => {if(lockItem.date_start==dateTmpStart && lockItem.date_end==dateTmpEnd)lock=true;});
                var classLock=(lock)?"event-session-default-lock":"event-session-default-unlock";
                //event add sessions start
                div = document.createElement('div');
                div.setAttribute('class', `${classForDefaultEvent} ${classLock}`);
                div.setAttribute('data-date', actualDaysOfWeek[day]);
                div.setAttribute('data-start', moment(timeStartItem, "hh:mm A").format("h:m A"));
                div.setAttribute('data-end',moment(timeEndItem, "hh:mm A").format("h:m A") );
                img = document.createElement('img');
                img.setAttribute('class', classForIcon);
                img.setAttribute('src',routePublicImages+'cross_black.png');
                div.appendChild(img);
                td.appendChild(div);
                //event add sessions end

                tr.appendChild(td);
            }

        }else{
            var timeStartItemDefault = moment(item.time_start).format('hh:mm A')
            var timeEndItemDefault = moment(item.time_end).format('hh:mm A')
            /// is default
                tr = document.createElement('tr');
                td = document.createElement('td');
                td.setAttribute('class', classForCellTime);
    
                span = document.createElement('span');
                span.setAttribute('class', classForStartTime);
                text = document.createTextNode(moment(timeStartItemDefault, "hh:mm A").format("HH:mm"));
                span.appendChild(text);
                td.appendChild(span);
                span = document.createElement('span');
                span.setAttribute('class', classForToTime);
                text = document.createTextNode('a');
                span.appendChild(text);
                td.appendChild(span);
                span = document.createElement('span');
                span.setAttribute('class', classForEndTime);
                text = document.createTextNode(moment(timeEndItemDefault, "hh:mm A").format("HH:mm"));
                span.appendChild(text);
                td.appendChild(span);
                tr.appendChild(td);
    
    
                for (let day = 0; day < 5; day++) {
                td = document.createElement('td');
                td.setAttribute('class', classForDroppableCell);
                td.setAttribute('data-day', '1');
                td.setAttribute('data-date', actualDaysOfWeek[day]);
                td.setAttribute('data-start', moment(timeStartItemDefault, "hh:mm A").format("h:m A"));
                td.setAttribute('data-end',moment(timeEndItemDefault, "hh:mm A").format("h:m A") );


                var lock=false;
                var dateTmpStart=actualDaysOfWeek[day]+" "+moment(timeStartItemDefault, "hh:mm A").format("HH:mm:ss");
                var dateTmpEnd=actualDaysOfWeek[day]+" "+moment(timeEndItemDefault, "hh:mm A").format("HH:mm:ss");
                item.locks.forEach(lockItem => {if(lockItem.date_start==dateTmpStart && lockItem.date_end==dateTmpEnd)lock=true;});
                var classLock=(lock)?"event-session-default-lock":"event-session-default-unlock";
                    //event add sessions start
                    div = document.createElement('div');
                    div.setAttribute('class', `${classForDefaultEvent} ${classLock}`);
                    div.setAttribute('data-date', actualDaysOfWeek[day]);
                    div.setAttribute('data-start', moment(timeStartItemDefault, "hh:mm A").format("h:m A"));
                    div.setAttribute('data-end', moment(timeEndItemDefault, "hh:mm A").format("h:m A"));
                    img = document.createElement('img');
                    img.setAttribute('class', classForIcon);
                    img.setAttribute('src', routePublicImages + 'cross_black.png');
                    div.appendChild(img);
                    td.appendChild(div);
                    //event add sessions end

                tr.appendChild(td);
                }
                tbody.appendChild(tr);
            

        }


            tbody.appendChild(tr);
        });

        if (dataCalendarSession.length <= 0) {

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
    
    
                for (let day = 0; day < 5; day++) {
                    td = document.createElement('td');
                    td.setAttribute('class', classForDroppableCell);
                    td.setAttribute('data-day', '1');
                    td.setAttribute('data-date', actualDaysOfWeek[day]);
                    tr.appendChild(td);
                }
                tbody.appendChild(tr);
            }

         
        }


        table.appendChild(thead);
        table.appendChild(tbody);

        container.appendChild(table);
       
    }

    
    function reloadTableList(){

        var container = document.getElementById(containerCalendar);
        container.innerHTML = "";

        var classForTable = 'session-calendar-table-weekly table-list-session';
        var classForCellTime = 'time-cell-session-list';
        var classForTh = 'cell-session-th hover-click';
        var classForStartTime = 'time';
        var classForEndTime = 'time';
        var classForToTime = 'to-time';
        var classForDroppableCell = 'droppable-cell-session';
        var classForSession = 'event-session';
        var classForIcon = 'icon-session';
        var classForDefaultEvent = 'event-session-default';

        var th, td, tbody, thead, tr, table, text, span, div,img;

        const dataCalendarSessionClone=$.map(dataCalendarSession, function (obj) {
            return $.extend(true, {}, obj);
        });
      
        let names=['Lunes','Martes','Miércoles','Jueves','Viernes'];
        
        for (let index = 1; index < 6; index++) {

        dataCalendarSession=$.map(dataCalendarSessionClone, function (obj) {
            return $.extend(true, {}, obj);
        });
        var isEmptyRow=true;
   
        var actualDayOfWeek=startDate.clone().weekday(index);
       

        var table = document.createElement('table');
        table.setAttribute('class', classForTable);

        //thead
        thead = document.createElement('thead');

        tr = document.createElement('tr');
        th = document.createElement('th');
        th.setAttribute('class', classForCellTime);
        text = document.createTextNode("Hora");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        th.setAttribute('onclick', "setDaySelect('"+moment(actualDayOfWeek).format('YYYY-MM-DD')+"')");

        text = document.createTextNode(names[index-1] +" "+moment(actualDayOfWeek).format('DD/MM'));
        th.appendChild(text);
        tr.appendChild(th);

        thead.appendChild(tr);
        //end thead
        //start tbody
        tbody = document.createElement('tbody');


        var dataCalendarSessionTmp=dataCalendarSession;

        dataCalendarSession.forEach(function (item, key) {

            if(!item.is_default){
            //var dateFirstItem=moment(item.sessions[0].date_start).format('DD/MM/YYYY')
            var timeStartItem = moment(item.time_start).format('hh:mm A')
            var timeEndItem = moment(item.time_end).format('hh:mm A')

            tr = document.createElement('tr');

            td = document.createElement('td');
            td.setAttribute('class', classForCellTime);

            span = document.createElement('span');
            span.setAttribute('class', classForStartTime);
            text = document.createTextNode(moment(timeStartItem, "hh:mm A").format("HH:mm"));
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForToTime);
            text = document.createTextNode('a');
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForEndTime);
            text = document.createTextNode(moment(timeEndItem, "hh:mm A").format("HH:mm"));
            span.appendChild(text);
            td.appendChild(span);
            tr.appendChild(td);


                td = document.createElement('td');
                td.setAttribute('class', classForDroppableCell);
                td.setAttribute('data-day', '1');
                td.setAttribute('data-date', moment(actualDayOfWeek).format('YYYY-MM-DD'));
                td.setAttribute('data-start', moment(timeStartItem, "hh:mm A").format("h:m A") );
                td.setAttribute('data-end', moment(timeEndItem, "hh:mm A").format("h:m A") );

                var countFind=0;
                dataCalendarSessionTmp.forEach(function (subItem, key2) {

                   
                 
                    var startTmp = moment(subItem.time_start).format('YYYY-MM-DD hh:mm A');
                    var endTmp = moment(subItem.time_end).format('YYYY-MM-DD hh:mm A');

                    var startTmp2 = moment(actualDayOfWeek).format('YYYY-MM-DD') + " " + timeStartItem;
                    var endTmp2 = moment(actualDayOfWeek).format('YYYY-MM-DD') + " " + timeEndItem;

                    if ((startTmp == startTmp2) && (endTmp == endTmp2)) {
                        
                        var statusClass='';
                        if(subItem.group.status==1){
                            statusClass='full';
                        }else if(subItem.group.status==2){
                            statusClass='free-spaces';
                        }else if(subItem.group.status==3){
                            statusClass='empty';
                        }
                        div = document.createElement('div');
                        div.setAttribute('class', classForSession+" "+statusClass+" "+((!subItem.status_employee)?"not-employee-border":"")+" "+subItem.status);
                        div.setAttribute('id', key2);
                        div.setAttribute('data-container', "#"+key2);
                        div.setAttribute('data-toggle', "kt-tooltip");
                        div.setAttribute('data-placement', "top");
                        div.setAttribute('data-original-title', subItem.room.name);

                        div.setAttribute('data-group-key', key2);
                        div.setAttribute('data-date', moment(actualDayOfWeek).format('YYYY-MM-DD'));
                        div.setAttribute('data-start',moment(timeStartItem, "hh:mm A").format("h:m A")  );
                        div.setAttribute('data-end', moment(timeEndItem, "hh:mm A").format("h:m A"));
                        div.setAttribute('data-session-status', subItem.status);


                        img = document.createElement('img');
                        img.setAttribute('class', classForIcon);
                        var tmpIconName='';
                        if(subItem.room.type_room=='Máquina'){
                            tmpIconName='equipo-de-pilates.png';
                        }else if(subItem.room.type_room=='Suelo'){
                            tmpIconName='pilates.png';
                        }else if(subItem.room.type_room=='Camilla'){
                            tmpIconName='fisioterapia.png';
                        }
                        img.setAttribute('src',routePublicImages+tmpIconName);
                        div.appendChild(img);
                        td.appendChild(div);
                        delete dataCalendarSessionTmp[key2];
                        
                        countFind++;
                        isEmptyRow=false;
                    }
               
                 
                });
              
                if(countFind<=0){
                //dataCalendarSession.length=0;
                }else{

                    var lock=false;
                    var dateTmpStart=moment(actualDayOfWeek).format('YYYY-MM-DD')+" "+moment(timeStartItem, "hh:mm A").format("HH:mm:ss");
                    var dateTmpEnd=moment(actualDayOfWeek).format('YYYY-MM-DD')+" "+moment(timeEndItem, "hh:mm A").format("HH:mm:ss");
                    item.locks.forEach(lockItem => {if(lockItem.date_start==dateTmpStart && lockItem.date_end==dateTmpEnd)lock=true;});
                    var classLock=(lock)?"event-session-default-lock":"event-session-default-unlock";
                    //event add sessions start
                    div = document.createElement('div');
                    div.setAttribute('class', `${classForDefaultEvent} ${classLock}`);
                    div.setAttribute('data-date', moment(actualDayOfWeek).format('YYYY-MM-DD'));
                    div.setAttribute('data-start', moment(timeStartItem, "hh:mm A").format("h:m A"));
                    div.setAttribute('data-end', moment(timeEndItem, "hh:mm A").format("h:m A"));
                    img = document.createElement('img');
                    img.setAttribute('class', classForIcon);
                    img.setAttribute('src', routePublicImages + 'cross_black.png');
                    div.appendChild(img);
                    td.appendChild(div);
                    //event add sessions end
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                }       

            }else{
                //default

                var timeStartItemDefault = moment(item.time_start).format('hh:mm A')
                var timeEndItemDefault = moment(item.time_end).format('hh:mm A')
    
                tr = document.createElement('tr');
    
                td = document.createElement('td');
                td.setAttribute('class', classForCellTime);
    
                span = document.createElement('span');
                span.setAttribute('class', classForStartTime);
                text = document.createTextNode(moment(timeStartItemDefault, "hh:mm A").format("HH:mm"));
                span.appendChild(text);
                td.appendChild(span);
                span = document.createElement('span');
                span.setAttribute('class', classForToTime);
                text = document.createTextNode('a');
                span.appendChild(text);
                td.appendChild(span);
                span = document.createElement('span');
                span.setAttribute('class', classForEndTime);
                text = document.createTextNode(moment(timeEndItemDefault, "hh:mm A").format("HH:mm"));
                span.appendChild(text);
                td.appendChild(span);
                tr.appendChild(td);
    
    
                td = document.createElement('td');
                td.setAttribute('class', classForDroppableCell);
                td.setAttribute('data-day', '1');
                td.setAttribute('data-date', moment(actualDayOfWeek).format('YYYY-MM-DD'));
                td.setAttribute('data-start', moment(timeStartItemDefault, "hh:mm A").format("h:m A") );
                td.setAttribute('data-end', moment(timeEndItemDefault, "hh:mm A").format("h:m A") );



                    var lock=false;
                    var dateTmpStart=moment(actualDayOfWeek).format('YYYY-MM-DD')+" "+moment(timeStartItemDefault, "hh:mm A").format("HH:mm:ss");
                    var dateTmpEnd=moment(actualDayOfWeek).format('YYYY-MM-DD')+" "+moment(timeEndItemDefault, "hh:mm A").format("HH:mm:ss");
                    item.locks.forEach(lockItem => {if(lockItem.date_start==dateTmpStart && lockItem.date_end==dateTmpEnd)lock=true;});
                    var classLock=(lock)?"event-session-default-lock":"event-session-default-unlock";
                 //event add sessions start
                 div = document.createElement('div');
                 div.setAttribute('class', `${classForDefaultEvent} ${classLock}`);
                 div.setAttribute('data-date', moment(actualDayOfWeek).format('YYYY-MM-DD'));
                 div.setAttribute('data-start', moment(timeStartItemDefault, "hh:mm A").format("h:m A"));
                 div.setAttribute('data-end', moment(timeEndItemDefault, "hh:mm A").format("h:m A"));
                 img = document.createElement('img');
                 img.setAttribute('class', classForIcon);
                 img.setAttribute('src', routePublicImages + 'cross_black.png');
                 div.appendChild(img);
                 td.appendChild(div);
                 //event add sessions end

                tr.appendChild(td);
                tbody.appendChild(tr);
            }
        });

        //is empty
        
        if(false){
          
            tr = document.createElement('tr');
            td = document.createElement('td');
            td.setAttribute('class', classForCellTime);
            tr.appendChild(td);

            td = document.createElement('td');
            td.setAttribute('class', classForDroppableCell);
            td.setAttribute('data-day', '1');
            td.setAttribute('data-date', moment(actualDayOfWeek).format('YYYY-MM-DD'));
            tr.appendChild(td);
            tbody.appendChild(tr);
        }

      
        table.appendChild(thead);
        table.appendChild(tbody);
        container.appendChild(table);


    }
  
    }

    function reloadTableMonthly(){

        var container = document.getElementById(containerCalendar);
        container.innerHTML = "";

        var classForTable = 'session-calendar-table-weekly';
        var classForCellTime = 'time-cell-session';
        var classForTh = 'cell-session-th';
        var classForStartTime = 'time';
        var classForEndTime = 'time';
        var classForToTime = 'to-time';
        var classForDroppableCell = 'droppable-cell-session';
        var classForOtherCell = 'default-empty-cell-session';
        var classForSession = 'event-session';
        var classForIcon = 'icon-session';
        var classDayCalendar = 'btn-day-calentar-session';

        var th, td, tbody, thead, tr, table, text, span, div,img;

        var table = document.createElement('table');
        table.setAttribute('class', classForTable);

        //thead
       //thead
       thead = document.createElement('thead');

       tr = document.createElement('tr');

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        text = document.createTextNode("Lunes ");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        text = document.createTextNode("Martes ");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        text = document.createTextNode("Miércoles ");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        text = document.createTextNode("Jueves ");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        text = document.createTextNode("Viernes ");
        th.appendChild(text);
        tr.appendChild(th);

        thead.appendChild(tr);
        //end thead
        //start tbody
        tbody = document.createElement('tbody');

             var numDays=startDate.daysInMonth();
          
             daysInMonthCount=0;
             var tmpMonthDay=startDate.clone().weekday(1);

          
       
             var diffDays=moment.duration(startDate.clone().diff(tmpMonthDay)).asDays();

             if(diffDays<0){
              numDays=numDays-(diffDays*(-1));
             }else{
             numDays=numDays+diffDays;
             }

             var diffDaysRest=tmpMonthDay.clone().add(numDays-1, 'days').format('YYYY-MM-DD');
             var fridayLast=moment(diffDaysRest).clone().weekday(5).format('YYYY-MM-DD');
             var lastDiff=moment.duration(moment(fridayLast).diff(moment(diffDaysRest))).asDays();
             numDays+=lastDiff;

             tr = document.createElement('tr');
      
            for (let day = -1; day < 5 && daysInMonthCount < numDays; day++) {

              
              

                if(day==4 ){
                    daysInMonthCount++;
                    daysInMonthCount++;
                    tmpMonthDay.add(2, 'days');

                    day=-1;
                    tbody.appendChild(tr);
                    tr = document.createElement('tr');
                }
                var thisMonth=moment(tmpMonthDay).format('MM')==moment(startDate.clone()).format('MM');
                
               
            
                td = document.createElement('td');
                if(thisMonth){
                    td.setAttribute('class', classForDroppableCell);
                }else{
                    td.setAttribute('class', classForOtherCell);
                 // numDays++;
                }
              
                td.setAttribute('data-day', '1');
                td.setAttribute('data-date', moment(tmpMonthDay).format('YYYY-MM-DD'));

                div = document.createElement('div');
                div.setAttribute('class', classDayCalendar);
                div.setAttribute('onclick', "setDaySelect('"+moment(tmpMonthDay).format('YYYY-MM-DD')+"')");

                
                text = document.createTextNode(moment(tmpMonthDay).format('DD'));
                div.appendChild(text);
                td.appendChild(div);
                dataCalendarSession.forEach(function (subItem, key2) {


                    if(!subItem.is_default){
                  
                    var endTmp = moment(subItem.time_start).format('YYYY-MM-DD');
                    var endTmp2 = moment(tmpMonthDay).format('YYYY-MM-DD');
               
                    if ((endTmp == endTmp2)) {
                        var statusClass='';
                        if(subItem.group.status==1){
                            statusClass='full';
                        }else if(subItem.group.status==2){
                            statusClass='free-spaces';
                        }else if(subItem.group.status==3){
                            statusClass='empty';
                        }
                        div = document.createElement('div');
                        div.setAttribute('class', classForSession+" "+statusClass+" "+((!subItem.status_employee)?"not-employee-border":"")+" "+subItem.status);
                        div.setAttribute('id', key2);
                        div.setAttribute('data-container', "#"+key2);
                        div.setAttribute('data-toggle', "kt-tooltip");
                        div.setAttribute('data-placement', "top");
                        div.setAttribute('data-original-title', subItem.room.name+' '+moment(subItem.time_start).format('HH:mm')+" a "+moment(subItem.time_end).format('HH:mm'));
                        div.setAttribute('data-session-status', subItem.status);
                        div.setAttribute('data-group-key', key2);
                        div.setAttribute('data-date', moment(tmpMonthDay).format('YYYY-MM-DD'));
                      

                        img = document.createElement('img');
                        img.setAttribute('class', classForIcon);
                        var tmpIconName='';
                        if(subItem.room.type_room=='Máquina'){
                            tmpIconName='equipo-de-pilates.png';
                        }else if(subItem.room.type_room=='Suelo'){
                            tmpIconName='pilates.png';
                        }else if(subItem.room.type_room=='Camilla'){
                            tmpIconName='fisioterapia.png';
                        }
                        img.setAttribute('src',routePublicImages+tmpIconName);
                        div.appendChild(img);
                        td.appendChild(div);
                        delete dataCalendarSession[key2];
                    }
                }
                });
                tr.appendChild(td);

                if(day!=4){
                    daysInMonthCount++;
                     
                  
                    tmpMonthDay.add(1, 'days');
                    

                    tbody.appendChild(tr);
                    //tr = document.createElement('tr');
                }
             
            }
        table.appendChild(thead);
        table.appendChild(tbody);

        container.appendChild(table);
        
    }

    function getDataByRange(tab,pStartDate=false,pEndDate=false){
      
     var  startDateTmp=(pStartDate!=false)?pStartDate.format('YYYY-MM-DD 00:00:00'):startDate.format('YYYY-MM-DD 00:00:00');
     var  endDateTmp=(pEndDate!=false)?pEndDate.format('YYYY-MM-DD 23:59:59'):endDate.format('YYYY-MM-DD 23:59:59');

    showOverlay();
    
    $.ajax({
        url: "dashboard/get_data_by_range",
        type: 'POST',
        data: {
            start_date:startDateTmp,
            end_date:endDateTmp,
            tab_type_data:tabTypeData,
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            try {
                
                dataCalendarSession=res;
                dataCalendarSessionByAccess=$.map(res, function (obj) {
                    return $.extend(true, {}, obj);
                });
                if(tab=='weekly'){
                    reloadTableWeekly();
                }else if(tab=='monthly'){
                    reloadTableMonthly();
                }else if(tab=='day'){
                   reloadTableDay();
                }else if(tab=='list'){
                   reloadTableList();
                }
             
                reloadContextMenu();
                resetElementsDraggable();
                $('[data-toggle="kt-tooltip"]').tooltip();
                hideOverlay();
               
            } catch (error) {
                location.reload();   
            }

        },
        error: function (xhr, status, error) {
           hideOverlay();
            dataCalendarSession=[];
            dataCalendarSessionByAccess=[];
            console.log(JSON.stringify(xhr));
            sendErrorsShow([error]);
        },
    });
    
    }

    function loadTemplate(pStartDate=false,pEndDate=false){
        showOverlay();
    var  startDateTmp=(pStartDate!=false)?pStartDate.format('YYYY-MM-DD 00:00:00'):startDate.format('YYYY-MM-DD 00:00:00');
    var  endDateTmp=(pEndDate!=false)?pEndDate.format('YYYY-MM-DD 23:59:59'):endDate.format('YYYY-MM-DD 23:59:59');
    
    $.ajax({
        url: "dashboard/load_template_check",
        type: 'POST',
        data: {
            month:$("#month-load-template").val(),
            start_date:startDateTmp,
            end_date:endDateTmp,
            mode_static:enableStaticMonth,
            _token: $('#token_ajax').val()
        },
        success: function (res) {

            if (res.status == false) {
                hideOverlay();

                if(enableStaticMonth=='true'){
                    $("#message-check-load-template").html(`¡Las sesiones creadas en este mes serán eliminadas!`);
                }else{
                    $("#message-check-load-template").html(`¡Las sesiones creadas serán eliminadas!`);
                }
           
            $("#modal_check_load_template").modal("show");
            } else {
            initLoadTemplate();
            }
        
        },
        error: function (xhr, status, error) {
           hideOverlay();
            console.log(JSON.stringify(xhr));
            sendErrorsShow([error]);
        },
    });

    }

    function initLoadTemplate(pStartDate=false,pEndDate=false){
   
        var  startDateTmp=(pStartDate!=false)?pStartDate.format('YYYY-MM-DD 00:00:00'):startDate.format('YYYY-MM-DD 00:00:00');
        var  endDateTmp=(pEndDate!=false)?pEndDate.format('YYYY-MM-DD 23:59:59'):endDate.format('YYYY-MM-DD 23:59:59');

        
    showOverlay();
  
    $.ajax({
        url: "dashboard/load_template",
        type: 'POST',
        data: {
            month:$("#month-load-template").val(),
            start_date:startDateTmp,
            end_date:endDateTmp,
            mode_static:enableStaticMonth,
            _token: $('#token_ajax').val()
        },
        success: function (res) {

            if (res.status == false) {
            hideOverlay();
            sendErrorsShow([res.response]);
            } else {
            getRangeDates();
            showToast(0,res.response);
            }
        
        },
        error: function (xhr, status, error) {
           hideOverlay();

            console.log(JSON.stringify(xhr));
            sendErrorsShow([error]);
        },
    });

    }

 


///////////////////////////////////////////////////////////////tables selected 


function addNewGroupSession(day,date,timeStart,timeEnd){

    groupSelected=null;
   

    employeeSelectedId=null;
    employeeSelectedName=null;
    clientSelected=null;
    clientsSelected=[];
    reloadTableSelectedClients();
                $("#modal-check-balance").modal("hide");
                $('#client-id-1').val("");
                $('#client-name-1').val("");
                $('#employee-name-1').val("");
                $('#group-id-1').val("");
                $('#group-name-1').val("");

                $('#client-id-2').val("");
                $('#client-name-2').val("");


    $('#form-group-date-session').show();
    $('#form-group-time-session').show();
    //console.log(`Dia: ${day} fecha: ${date} inicial:${timeStart} final:${timeEnd}`);

    if(timeStart!=false && timeEnd!=false){
        $('#timepicker_start1').timepicker('setTime',timeStart);
        $('#timepicker_end1').timepicker('setTime',timeEnd);
    }else{
        $('#timepicker_start1').timepicker('setTime',moment().format('hh:mm A'));
        $('#timepicker_end1').timepicker('setTime',moment().add(1, 'hours').format('hh:mm A'));
    }

    $("#date_start1").val(date.toString());
    

    serialDays.forEach(day => { $(`#check-${day}`).prop('checked', false); });

    $("#modal_add_group_session").modal("show");
}
function addNewSession(day,date,timeStart,timeEnd){
    clientsSelected=[];
    reloadTableSelectedClients();
    groupSelected=null;
                clientSelected=null;
                employeeSelectedId=null;
                employeeSelectedName=null;
                $("#modal-check-balance").modal("hide");
                $('#client-id-1').val("");
                $('#client-name-1').val("");
                $('#employee-name-1').val("");
                $('#group-id-1').val("");
                $('#group-name-1').val("");

                $('#client-id-2').val("");
                $('#client-name-2').val("");
   
   // $('#form-group-date-session').hide();
   // $('#form-group-time-session').hide();

    if(timeStart!=false && timeEnd!=false){
        $('#timepicker_start1').timepicker('setTime',timeStart);
        $('#timepicker_end1').timepicker('setTime',timeEnd);
    }else{
        $('#timepicker_start1').timepicker('setTime',moment().format('hh:mm A'));
        $('#timepicker_end1').timepicker('setTime',moment().add(1, 'hours').format('hh:mm A'));
    }

    $("#date_start1").val(date.toString());
    serialDays.forEach(day => { $(`#check-${day}`).prop('checked', false); });
    $("#modal_add_group_session").modal("show");

}

