

    var dataCalendarSession=[];
    var dataCalendarSessionByAccess=[];
    var serialDays=['monday','tuesday','wednesday','thursday','friday'];

    var  defaultTimes=[
        {'start':'08:05:00','end':'09:00:00'},
        {'start':'09:05:00','end':'10:00:00'},
        {'start':'10:05:00','end':'11:00:00'},
        {'start':'11:05:00','end':'12:00:00'},
        {'start':'12:05:00','end':'13:00:00'},
        {'start':'13:05:00','end':'14:00:00'},
        {'start':'14:05:00','end':'15:00:00'},
        {'start':'15:05:00','end':'16:00:00'},
        {'start':'16:05:00','end':'17:00:00'},
        {'start':'17:05:00','end':'18:00:00'},
        {'start':'18:05:00','end':'19:00:00'},
        {'start':'19:05:00','end':'20:00:00'},
        {'start':'20:05:00','end':'21:00:00'},
        {'start':'21:05:00','end':'22:00:00'}
    ];

    var tabs=['monthly','weekly','day','list'];
    var tabInit='weekly';
    var tabSelected=tabInit;
    var tabList=false;
    var startDate=false;
    var endDate=false;
    var local='en';

    var textTitleRange="";
    actualTime=false;
    var containerCalendar='session-calendar';

    var  dataForNewSession={
        group_selected: '',
        client_selected: '',
        clients_selected:[],
        date_start: '',
        timepicker_start: '',
        timepicker_end: '',
        observation:'',
        serie_days_selected:[],
        day:null,
        id_template:null,
        _token: $('#token_ajax').val()
    };


    var tabTypeData='all';

    var timeOutHover=null;


    var flagDroppableSame=null;
    function resetElementsDraggable(){

        $(".event-session").draggable(
            {
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
                var day = $(this).attr('data-day');

                var timepicker_start = $(this).attr('data-start');
                var timepicker_end = $(this).attr('data-end');

                var day_previous = $(ui.draggable).attr('data-day');
                var timepicker_start_previous = $(ui.draggable).attr('data-start');
                var timepicker_end_previous = $(ui.draggable).attr('data-end');

                if (typeof timepicker_start === typeof undefined || timepicker_end === false) {

                    timepicker_start = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].time_start;
                    timepicker_end = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].time_end;
                    timepicker_start_previous = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].time_start;
                    timepicker_end_previous = dataCalendarSessionByAccess[$(ui.draggable).attr('data-group-key')].time_end;

                }



                timepicker_start = moment(timepicker_start, 'HH:mm:ss').format('HH:mm');
                timepicker_end = moment(timepicker_end,'HH:mm:ss').format('HH:mm');
                timepicker_start_previous = moment(timepicker_start_previous, 'HH:mm:ss').format('HH:mm');
                timepicker_end_previous = moment(timepicker_end_previous, 'HH:mm:ss').format('HH:mm');




                if(
                !($(flagDroppableSame).attr('data-day')==$(this).attr('data-day') &&
                $(flagDroppableSame).attr('data-start')==$(this).attr('data-start') &&
                $(flagDroppableSame).attr('data-end')==$(this).attr('data-end')
                )){
                editGroupSessionForDrag(
                    id_group,
                    day,
                    timepicker_start,
                    timepicker_end,
                    day_previous,
                    timepicker_start_previous,
                    timepicker_end_previous).then((response) => {

                        $(ui.draggable).appendTo(this);
                        $(ui.draggable).attr('style', 'position:relative;');

                        getDataByCalendar();


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

            sessionsMoveSelected=[];
            var id_session = $(ui.draggable).attr('data-id-session');
           // $(ui.draggable).remove();
            sessionsMoveSelected.push(id_session);


            var key = $(this).attr("data-group-key");

            var day = dataCalendarSessionByAccess[key].day;
            var timepicker_start = dataCalendarSessionByAccess[key].time_start;
            var timepicker_end = dataCalendarSessionByAccess[key].time_end;
            var id_group = dataCalendarSessionByAccess[key].group.id;
            moveSessions(day,timepicker_start,timepicker_end,id_group);


            resetDrag();
            }
        });

    }


    function resetDrag(){

        $(".event-session").draggable(
            {
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

    $.contextMenu({
        selector: '.event-session',
        callback: function (actions, options) {
            var key = options.$trigger.attr("data-group-key");

            if(actions=='add'){


                clientSelected = null;
                groupSelected = null;

                groupSelected = dataCalendarSessionByAccess[key].group;

              var day = dataCalendarSessionByAccess[key].day;
              var timepicker_startp = dataCalendarSessionByAccess[key].time_start;
              var timepicker_endp = dataCalendarSessionByAccess[key].time_end;

                dataForNewSession = {
                    group_selected: '',
                    client_selected: '',
                    clients_selected:[],
                    date_start: null,
                    timepicker_start: timepicker_startp,
                    timepicker_end: timepicker_endp,
                    observation: '',
                    serie_days_selected:[],
                    day:day,
                    id_template:templateSelected,
                    _token: $('#token_ajax').val()
                };



                $("#client-name-2").val("");
                clientSelected=null;
                clientsSelected=[];
                reloadTableSelectedClients();
                $("#modal_add_new_session").modal("show");

            }else if(actions=='edit'){



                var day = dataCalendarSessionByAccess[key].day;
                var timepicker_startp = dataCalendarSessionByAccess[key].time_start;
                var timepicker_endp = dataCalendarSessionByAccess[key].time_end;
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


            $("#timepicker_start_edit").timepicker('setTime',timepicker_startp);
            $("#timepicker_end_edit").timepicker('setTime',timepicker_endp);


            $('#timepicker_start1').timepicker('setTime', timepicker_startp);
            $('#timepicker_end1').timepicker('setTime',timepicker_endp);
            $("#employee-name-edit").val(name_employee);

                groupSelected = dataCalendarSessionByAccess[key].group;
                employeeSelectedId = id_employee;
                employeeSelectedName = name_employee;



            $("#timepicker_start_edit_previous").timepicker('setTime',timepicker_startp);
            $("#timepicker_end_edit_previous").timepicker('setTime',timepicker_endp);


            $("#group-id-1-edit").val(id_group);
            $("#group-name-edit").val(name_group);
            $("#room-name-edit").val(name_room);
            $("#room-type-edit").val(type_room);
            $("#group-observation-edit").text(observation_group);

            serialDays.forEach(dayF => { $(`#radio-${dayF}`).prop('checked', false); });

            $(`#day-edit-previous`).val(day);
            $(`#radio-${day}`).prop('checked', true);

            showModalEditGroupSessions();
            //$("#modal_edit_group_session").modal("show");
            }else if(actions=='delete'){
                var day = dataCalendarSessionByAccess[key].day;
                var timepicker_startp = dataCalendarSessionByAccess[key].time_start;
                var timepicker_endp = dataCalendarSessionByAccess[key].time_end;
                var id_group = dataCalendarSessionByAccess[key].group.id;

                deleteGroupSessions(day,timepicker_startp,timepicker_endp,id_group);


            }else if(actions=='move_session'){
                var day = dataCalendarSessionByAccess[key].day;
                var timepicker_start = dataCalendarSessionByAccess[key].time_start;
                var timepicker_end = dataCalendarSessionByAccess[key].time_end;
                var id_group = dataCalendarSessionByAccess[key].group.id;
                moveSessions(day,timepicker_start,timepicker_end,id_group);
            }
        },
        items: {
            "add": { name: "Agregar sesiones", icon: "add" },
            "edit": { name: "Editar", icon: "edit" },
            "delete": { name: "Eliminar", icon: "delete" },
            "move_session": { name: "Mover sesión aquí ", icon:  `fa fa fa-arrow-down`,disabled: function(key, options){ if(sessionsMoveSelected.length<=0)return true; return false;} },
            }

    });



    $(".event-session-default.event-session-default-unlock").click(function(){
        var day = $(this).attr("data-day");

        var timeStart= false;
        var timeEnd= false;

        var attrStart=$(this).attr('data-start');
        var attrEnd=$(this).attr('data-end');

       if (typeof attrStart !== typeof undefined && attrStart !== false)
        timeStart=attrStart;

        if (typeof attrEnd !== typeof undefined && attrEnd !== false)
        timeEnd=attrEnd;
        //addNewGroupSession(day,date,timeStart,timeEnd);
        addNewSession(day,timeStart,timeEnd);
    });

    $(".time-cell-session").click(function(e){
      e.preventDefault();

      var h3title = $(this).find('.time').text();
      console.log(h3title);

        var timeStart= h3title.substr(0,5);
        var timeEnd= h3title.substr(-5);
       //
        // var attrStart=$(this).attr('data-start');
        // var attrEnd=$(this).attr('data-end');
        // console.log(attrStart,'yy');
       // console.log(attrEnd,'hh');
       //
       // if (typeof attrStart !== typeof undefined && attrStart !== false)
       //  timeStart=attrStart;
       //
       //  if (typeof attrEnd !== typeof undefined && attrEnd !== false)
       //  timeEnd=attrEnd;
       //  //addNewGroupSession(day,date,timeStart,timeEnd);
       //  addNewSession(day,timeStart,timeEnd);
       $("#part-one").hide();
       $("#part-two").hide();
       $("#part-three").hide();
       $("#part-four").hide();
       $("#part-five").hide();
       $("#part-six").hide();
       $("#part-seven").show();
       $("#data-hour").val(h3title);
       $('#timepicker_start1').timepicker('setTime',timeStart);
       $('#timepicker_end1').timepicker('setTime',timeEnd);

        $("#modal_add_group_session").modal("show");

    });

    $('.event-session').on('click', function(e) {
        e.stopPropagation();
        loadSessionsGroupModal(this);
    });
    $('.droppable-cell-session').on('click', function(e) {
        e.stopPropagation();
       hideShowPanelLeft(false);
    });

    }





    generateTable();

    function generateTable(){
        actualTime = moment();
        getDataByCalendar();
    }



    initDefaultSchedule();
    function initDefaultSchedule(){
        defaultTimes.forEach(function(time, index) {
            $(`#timepicker_schedule_start_${index},#timepicker_schedule_end_${index}`).timepicker({
                minuteStep: 1,
                showSeconds: false,
                showMeridian: false,
                disableFocus: false,
                defaultTime: 'current',
                modalBackdrop: false,
               // appendWidgetTo: '#container-timepicker-fix'
            });
            $(`#timepicker_schedule_start_${index}`).timepicker('setTime',time.start);
            $(`#timepicker_schedule_end_${index}`).timepicker('setTime',time.end);
        });
    }

    function changeEditSchedule(key){
        $(`#timepicker_start_edit`).timepicker('setTime',defaultTimes[key].start);
        $(`#timepicker_end_edit`).timepicker('setTime',defaultTimes[key].end);
        $("#modal-schedule-default").modal("hide");
    }


$("#btn-sw-pilates").click(function(){
$("#btn-sw-pilates").addClass('active-type-calendar');
$("#btn-sw-all").removeClass('active-type-calendar');
$("#btn-sw-physiotherapy").removeClass('active-type-calendar');
tabTypeData='pilates';
getDataByCalendar();
});

$("#btn-sw-physiotherapy").click(function(){
$("#btn-sw-pilates").removeClass('active-type-calendar');
$("#btn-sw-all").removeClass('active-type-calendar');
$("#btn-sw-physiotherapy").addClass('active-type-calendar');
tabTypeData='physiotherapy';
getDataByCalendar();
});


$("#btn-sw-all").click(function(){
$("#btn-sw-pilates").removeClass('active-type-calendar');
$("#btn-sw-physiotherapy").removeClass('active-type-calendar');
$("#btn-sw-all").addClass('active-type-calendar');
tabTypeData='all';
getDataByCalendar();
});



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

        text = document.createTextNode("Lunes");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        text = document.createTextNode("Martes");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);

        text = document.createTextNode("Miércoles");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        text = document.createTextNode("Jueves");
        th.appendChild(text);
        tr.appendChild(th);

        th = document.createElement('th');
        th.setAttribute('class', classForTh);
        text = document.createTextNode("Viernes");
        th.appendChild(text);
        tr.appendChild(th);

        thead.appendChild(tr);
        //end thead
        //start tbody
        tbody = document.createElement('tbody');

        dataCalendarSession.forEach(function (item, key) {

            if(!item.is_default){

            var timeStartItem = item.sessions.start;
            var timeEndItem = item.sessions.end;
            var dayItem =item.sessions.day;



            tr = document.createElement('tr');

            td = document.createElement('td');
            td.setAttribute('class', classForCellTime);

            span = document.createElement('span');
            span.setAttribute('class', classForStartTime);
            text = document.createTextNode(moment(timeStartItem, "HH:mm:ss").format("HH:mm"));
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForToTime);
            text = document.createTextNode('a');
            span.appendChild(text);
            td.appendChild(span);
            span = document.createElement('span');
            span.setAttribute('class', classForEndTime);
            text = document.createTextNode(moment(timeEndItem, "HH:mm:ss").format("HH:mm"));
            span.appendChild(text);
            td.appendChild(span);
            tr.appendChild(td);


            for (let day = 0; day < 5; day++) {
                td = document.createElement('td');
                td.setAttribute('class', classForDroppableCell);
                td.setAttribute('data-day', serialDays[day]);
                td.setAttribute('data-start',timeStartItem);
                td.setAttribute('data-end',timeEndItem);

                dataCalendarSession.forEach(function (subItem, key2) {

                    if ((subItem.time_start == timeStartItem) && (subItem.time_end == timeEndItem) && (serialDays[day]==subItem.day)) {
                        var statusClass='';
                        if(subItem.group.status==1){
                            statusClass='full';
                        }else if(subItem.group.status==2){
                            statusClass='free-spaces';
                        }else if(subItem.group.status==3){
                            statusClass='empty';
                        }


                        div = document.createElement('div');
                        div.setAttribute('class', classForSession+" "+statusClass+" "+((!subItem.status_employee)?"not-employee-border-quit":""));
                        div.setAttribute('id', key2);
                        div.setAttribute('data-container', "#"+key2);
                        div.setAttribute('data-toggle', "kt-tooltip");
                        div.setAttribute('data-placement', "top");
                        div.setAttribute('data-original-title', subItem.room.name);
                        div.setAttribute('data-employee-status', subItem.status_employee);



                        div.setAttribute('data-group-key', key2);
                        div.setAttribute('data-day', serialDays[day]);
                        div.setAttribute('data-start',timeStartItem);
                        div.setAttribute('data-end',timeEndItem);

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


                //event add sessions start
                div = document.createElement('div');
                div.setAttribute('class', `${classForDefaultEvent} event-session-default-unlock`);
                div.setAttribute('data-day',  serialDays[day]);
                div.setAttribute('data-start', timeStartItem);
                div.setAttribute('data-end',timeEndItem);
                img = document.createElement('img');
                img.setAttribute('class', classForIcon);
                img.setAttribute('src',routePublicImages+'cross_black.png');
                div.appendChild(img);
                td.appendChild(div);
                //event add sessions end
                tr.appendChild(td);
            }

        }else{
            var timeStartItemDefault = item.time_start;
            var timeEndItemDefault = item.time_end;
            /// is default
                tr = document.createElement('tr');
                td = document.createElement('td');
                td.setAttribute('class', classForCellTime);

                span = document.createElement('span');
                span.setAttribute('class', classForStartTime);
                text = document.createTextNode(moment(timeStartItemDefault, "HH:mm:ss").format("HH:mm"));
                span.appendChild(text);
                td.appendChild(span);
                span = document.createElement('span');
                span.setAttribute('class', classForToTime);
                text = document.createTextNode('a');
                span.appendChild(text);
                td.appendChild(span);
                span = document.createElement('span');
                span.setAttribute('class', classForEndTime);
                text = document.createTextNode(moment(timeEndItemDefault, "HH:mm:ss").format("HH:mm"));
                span.appendChild(text);
                td.appendChild(span);
                tr.appendChild(td);


                for (let day = 0; day < 5; day++) {
                td = document.createElement('td');
                td.setAttribute('class', classForDroppableCell);
                td.setAttribute('data-day', serialDays[day]);
                td.setAttribute('data-start',timeStartItemDefault);
                td.setAttribute('data-end',timeEndItemDefault);



                    //event add sessions start
                    div = document.createElement('div');
                    div.setAttribute('class', `${classForDefaultEvent} event-session-default-unlock`);
                    div.setAttribute('data-day', serialDays[day]);
                    div.setAttribute('data-start', timeStartItemDefault);
                    div.setAttribute('data-end',timeEndItemDefault);
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

        table.appendChild(thead);
        table.appendChild(tbody);

        container.appendChild(table);

    }



    function getDataByCalendar(){
        showOverlay();
        if(templateSelected!=null){

        $("#templates-view").show(); $("#default-view").hide();
        reloadListTemplates();
        $.ajax({
            url: "template/get_data",
            type: 'POST',
            data: {
                template_selected:templateSelected,
                tab_type_data:tabTypeData,
                _token: $('#token_ajax').val()
            },
            success: function (res) {

$("#name-template-selected").html(res.template.name);
$("#template-name-edit").val(res.template.name);
if(res.template.status=='true'){
$("#text-btn-enable-disable").html(`Desactivar`);

$("#status-template-selected").html(`<div class="template-enable">Activa</div>`);
}else{
$("#text-btn-enable-disable").html(`Activar`);

$("#status-template-selected").html(`<div class="template-disable">Inactiva</div>`);
}



                dataCalendarSession=res.groups;
                dataCalendarSessionByAccess=$.map(res.groups, function (obj) {
                    return $.extend(true, {}, obj);
                });
                reloadTableWeekly();
                reloadContextMenu();
                resetElementsDraggable();

                $('[data-toggle="kt-tooltip"]').tooltip();
                hideOverlay();

            },
            error: function (xhr, status, error) {
               hideOverlay();
                dataCalendarSession=[];
                dataCalendarSessionByAccess=[];
                console.log(JSON.stringify(xhr));
                sendErrorsShow([error]);
            },
        });

        }else{
        $("#templates-view").hide(); $("#default-view").show();
        hideOverlay();
        }
    }

    function createTemplate(){
        showOverlay();

        $.ajax({
            url: "template/create_template",
            type: 'POST',
            data: {
                name:$('#template-name-add').val(),
                _token: $('#token_ajax').val()
            },
            success: function (res) {

            if (res.status == false) {
            hideOverlay();
            sendErrorsShow(res.response);
            }else{
            templateSelected=res.data.id;
            getDataByCalendar();
            $('#template-name-add').val("")
            $("#modal_add_template").modal("hide");
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

    function reloadListTemplates(){
        $.ajax({
            url: "template/get_template_list",
            type: 'POST',
            data: {
                _token: $('#token_ajax').val()
            },
            success: function (res) {
                var html = ``;
                var htmlItems = ``;

                if (res.status) {
                    res.data.forEach(template => {
                        var isActive=``;
                        if(template.status=='true'){
                         isActive=` - <span class="status-green">Activa</span>`;
                        }else{
                        isActive=``;
                        }
                        htmlItems += ` <option `+((template.id == templateSelected) ? 'selected="selected"' : '')+` value="${template.id}" >${template.name}${isActive}</option>`;
                    });
                }

                html = `<label for="exampleSelectd">Plantillas</label>
                <select class="form-control" id="exampleSelectd" onchange="setTemplate(this.options[this.selectedIndex].value)">
                 ${htmlItems}
                </select>`;
                $("#container-select-template").html(html);
            },
            error: function (xhr, status, error) {
                console.log(JSON.stringify(xhr));
            },
        });
    }
    function setTemplate(id){
        templateSelected=id;
        getDataByCalendar();
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


function addNewSession(day,timeStart,timeEnd){
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
        $('#timepicker_start1').timepicker('setTime',moment(timeStart,'HH:mm:ss').format('HH:mm'));
        $('#timepicker_end1').timepicker('setTime',moment(timeEnd,'HH:mm:ss').format('HH:mm'));
    }else{
        $('#timepicker_start1').timepicker('setTime',moment().format('HH:mm'));
        $('#timepicker_end1').timepicker('setTime',moment().add(1, 'hours').format('HH:mm'));
    }

    //$("#date_start1").val(date.toString());
    serialDays.forEach(dayU => { $(`#check-${dayU}`).prop('checked', false); });

    $(`#check-${day}`).prop('checked', true);
    $("#part-one").show();
    $("#part-two").show();
    $("#part-three").show();
    $("#part-four").show();
    $("#part-five").show();
    $("#part-six").show();
    $("#part-seven").hide();

    $("#modal_add_group_session").modal("show");

}
