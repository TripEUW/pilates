"use strict";
var tableEmployee=null;
var tableEmployeeSelected=null;
var daysForm=['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
var addOrEdit="add";
var colorSelected="#fff";
var enableWeekend=false;

var startDate = false;
var endDate = false;

if(!enableWeekend){
	daysForm.pop();
	// daysForm.pop();
}

var modeTime='simple';
var employeesSelected=[];

var DatatableDataEmployeeSelectedServer = function() {
	var initTableEmployeeSelected = function() {
		// begin first table
		tableEmployeeSelected = $('#kt_table_employee_selected').DataTable({
			lengthMenu: [[5,10, 25, 50,100, -1], [5,10, 25, 50,100, "Todo"]],
			dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
			pageLength: 5,
			responsive: true,
			colReorder: true,
			autoWidth: false,
			/* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
			serverSide: true,
			serverMethod: 'post',
			language: {
				processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
				searchPlaceholder: "",
				search: "Buscar empleado",
				lengthMenu: "Mostrar _MENU_  por página",
				zeroRecords: "Nada encontrado",
				info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
				infoEmpty: "No hay registros para mostrar.",
				infoFiltered: ""
			},
			ajax: {
				url:"schedule/dataTable_employee_select",
				dataType: "json",
				type: "POST",
				data:function(data) {
					data.date_start = startDate.format('YYYY-MM-DD');
					data.date_end = endDate.format('YYYY-MM-DD');
					data.employee_selected = $('#id-employee-schedule-add').val();
					data._token = $('#token_ajax').val();
				}
			},

			columns: [
				{data: 'employee'},
				{data: 'actions',responsivePriority: -1},
			],
			columnDefs: [
				{
					"width": "75%",
					'targets': 0,
					'orderable': true,
					'class': 'text-center',
					'render': function (data, type, row){
						var html=`<div class="selected-table-employee"  style="background:${data.color};">
						<div class="selected-table-employee-item"   style="background:${data.color};">${data.name}</div>
						<div class="${((parseInt(data.count_schedule)>0)?"status-shedule-employee-active":"status-shedule-employee-default")}"></div>

						</div>`;

						return html;
					}
				},
				{
					"width": "25%",
					targets: -1,
					title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {



						return `
						<a href="#" onclick='setEmployeeClose(`+JSON.stringify(data)+`);' class="btn btn-brand btn-elevate btn-icon-sm p-1 mb-2 w-90"><i class="la la-plus"></i>Elegir</a>
						<a href="#" onclick='setEmployee(`+JSON.stringify(data)+`);' class="btn btn-brand btn-elevate btn-icon-sm p-1 w-90 mb-2"><i class="la la-plus"></i>Agregar</a>
						`;
					},
				}
			],
			drawCallback: function( settings ) {

				$('#kt_table_employee_selected').show();
			},
			order: [[0, 'desc']]
		});

	};

	return {
		//main function to initiate the module
		init: function() {
			initTableEmployeeSelected();
		},

	};
}();



var KTDatatablesEmployee = function() {

	var initTableEmployee = function() {

		// begin first table
		tableEmployee = $('#kt_table_employees_shedule').DataTable({
			lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "Todo"]],
			dom:  'lftpr',
			pageLength: 11,
			pagingType: "simple",
			responsive: true,
			colReorder: true,
			lengthChange: false,
			/* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
			serverSide: true,
			serverMethod: 'post',
			language: {
				processing: `Procesando el contenido <br><br> <button class="btn btn-success btn-icon btn-circle kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light"></button>`,
				searchPlaceholder: "Buscar empleado",
				search: "",
				lengthMenu: "Mostrar _MENU_  por página",
				zeroRecords: "Nada encontrado",
				info: "Página _PAGE_ de _PAGES_  (filtrado de _MAX_ registros totales)",
				infoEmpty: "No hay registros para mostrar.",
				infoFiltered: ""
			},
			ajax: {
				url:"schedule/dataTableEmployee",
				dataType: "json",
				type: "POST",
				data: function(data) {

					data.date_start = startDate.format('YYYY-MM-DD');
					data.date_end = endDate.format('YYYY-MM-DD');
					data._token = $('#token_ajax').val();
				}
			},
			columns: [
				{data: 'employee'}
			],
			columnDefs: [
				{
					'targets': 0,
					'orderable': true,
					'class': 'text-center',
					'render': function (data, type, row){
						var html=`<div data-name-employee="${data.name}" data-id-employee="${data.id}" data-color-employee="${data.color}" data-status-employee="${parseInt(data.count_schedule)}" class="draggable-employee employee-element-shedule"  style="background:${data.color};">
						<div class="draggable-employee-item" data-status-schedule="${parseInt(data.count_schedule)}" data-employee-id="${data.id}" data-employee-name="${data.name}" style="background:${data.color};">${data.name}</div>
						<div class="${((parseInt(data.count_schedule)>0)?"status-shedule-employee-active":"status-shedule-employee-default")}"></div>
						<div class="filter-shedule-employee" onclick="setEmployeeFilter(${data.id})"><i class="fa fa-filter"></i></div>
						</div>`;

						return html;
					}
				},
			],
			order: [[0, 'desc']],
			drawCallback: function(settings) {
				flagTableEmployee=true;
				$( ".employee-element-shedule" ).dblclick(function() {
					var idEmployee=$(this).attr("data-id-employee");

					employeeSelected=idEmployee;
					showOverlay();
					getDataHolidays();
				});
				resetElementsDraggable();
			}
		});
	};

	return {
		//main function to initiate the module
		init: function() {
			initTableEmployee();
		},

	};
}();

jQuery(document).ready(function() {
	DatatableDataEmployeeSelectedServer.init();
	/*
	$('#select-all-sessions-edit').on('click', function () {
	var rows = tableSessionsGroup.rows({ 'search': 'applied' }).nodes();
	$('input[type="checkbox"]', rows).prop('checked', this.checked);
});

tableSessionsGroup.on('draw', function () {
if ($('#select-all-sessions-edit').is(":checked")) {
var rows = tableSessionsGroup.rows({ 'search': 'applied' }).nodes();
$('input[type="checkbox"]', rows).prop('checked', true);
}
});
*/
});

// modal select employee
function showModalSelectEmployees(){
	$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').hide();
	$('#modal_select_employees').modal('show');
	tableEmployeeSelected.search("");
	tableEmployeeSelected.ajax.reload();

}

$('#modal_select_employees').on('show.bs.modal', function(e) {
	tableEmployeeSelected.responsive.recalc();
	setTimeout(function() {
		$('div.dataTables_wrapper thead,div.dataTables_wrapper tbody').show();
		tableEmployeeSelected.responsive.recalc();
	}, 300);
});

function setEmployee(data){

	var flagExist=true;
	employeesSelected.forEach(employee => {if(employee.id==data.id)flagExist=false;});

	if(flagExist){

		employeesSelected.push(data);
		reloadTableSelectedEmployees();
		showToast(0,"El empleado fue agregado a la tabla, ahora puede cerrar esta ventana o seguir agregando.",3000);
	}else{
		showToast(1,"El cliente ya fue agregado.");

	}

}

function setEmployeeClose(data) {


	var flagExist = true;
	employeesSelected.forEach(employee => { if (employee.id == data.id) flagExist = false; });

	if (flagExist) {
		employeesSelected.push(data);
		reloadTableSelectedEmployees() ;
		$("#modal_select_employees").modal("hide");

	} else {
		showToast(1, "El empleado ya fue agregado.");
	}

}


function reloadTableSelectedEmployees() {
	$("#container-table-employees-add").html("");

	var htmlTmp = ``;
	employeesSelected.forEach(employee => {
		htmlTmp += `<tr><td>${employee.name + " " + employee.last_name}</td><td style="width: 55px;" class="text-center">
		<a href="#" onclick="removeEmployeeFromList(${employee.id})" class="btn btn-brand btn-elevate btn-icon-sm p-1"><i class="flaticon2-delete"></i>Quitar</a>
		</td></tr>`;
	});


	var htmlTable=`
	<div class="container">
	<table class="table-bordered table-hover table-data-custom w-100">
	<thead>
	<tr>
	<th>Cliente</th>
	<th style="width: 55px;">Quitar</th>
	</tr>
	</thead>
	<tbody>
	${htmlTmp}
	</tbody>
	</table>
	</div>
	`;

	if(htmlTmp==``){
		$("#container-table-employees-add").html("");
	}else{
		$("#container-table-employees-add").html(htmlTable);
	}



}

function removeEmployeeFromList(id){


	for(var i = employeesSelected.length - 1; i >= 0; i--) {
		if(employeesSelected[i].id == id) {
			employeesSelected.splice(i, 1);
		}
	}

	reloadTableSelectedEmployees();

}



//end  modal select client

var flagTableEmployee=false;
////////////////////////////////////////////////////////////part of shedule calendar
var tabs = ['monthly', 'weekly', 'day', 'list'];
var tabInit = 'weekly';
var tabSelected = tabInit;


var textTitleRange = "";
var actualTime = false;
var containerCalendar = 'session-calendar';

var actualDaysOfWeek = { monday: false, tuesday: false, wednesday: false, thursday: false, friday: false, saturday: false};

var dataCalendarSchedule = [];
var dataCalendarScheduleByAccess = [];

var employeeSelected = null;

var joe=colorjoe.rgb('extraPicker', colorSelected, [
	'currentColor',
	['fields', { space: 'RGB', limit: 255, fix: 2 }],
	'hex',
	'text',
	['text', { text: 'param demo' }]
]).on('change', function(c) {
	colorSelected=c.css();
}).update();
daysForm.forEach(day => {
	$(`#timepicker_start1_${day}`).timepicker({
		minuteStep: 1,
		showSeconds: false,
		showMeridian: false,
		disableFocus: false,
		defaultTime: 'current',
		modalBackdrop: false,
		appendWidgetTo: `#container-timepicker-fix-left-${day}`
	}).on('show.timepicker', function(e) {});
	$(`#timepicker_end1_${day}`).timepicker({
		minuteStep: 1,
		showSeconds: false,
		showMeridian: false,
		disableFocus: false,
		defaultTime: 'current',
		modalBackdrop: false,
		appendWidgetTo: `#container-timepicker-fix-right-${day}`
	}).on('show.timepicker', function(e) {});

	$(`#timepicker_start1_one_${day}`).timepicker({
		minuteStep: 1,
		showSeconds: false,
		showMeridian: false,
		disableFocus: false,
		defaultTime: 'current',
		modalBackdrop: false,
		appendWidgetTo: `#container-timepicker-fix-left-one-${day}`
	}).on('show.timepicker', function(e) {});
	$(`#timepicker_end1_one_${day}`).timepicker({
		minuteStep: 1,
		showSeconds: false,
		showMeridian: false,
		disableFocus: false,
		defaultTime: 'current',
		modalBackdrop: false,
		appendWidgetTo: `#container-timepicker-fix-right-one-${day}`
	}).on('show.timepicker', function(e) {});

	$(`#timepicker_start1_two_${day}`).timepicker({
		minuteStep: 1,
		showSeconds: false,
		showMeridian: false,
		disableFocus: false,
		defaultTime: 'current',
		modalBackdrop: false,
		appendWidgetTo: `#container-timepicker-fix-left-two-${day}`
	}).on('show.timepicker', function(e) {});
	$(`#timepicker_end1_two_${day}`).timepicker({
		minuteStep: 1,
		showSeconds: false,
		showMeridian: false,
		disableFocus: false,
		defaultTime: 'current',
		modalBackdrop: false,
		appendWidgetTo: `#container-timepicker-fix-right-two-${day}`
	}).on('show.timepicker', function(e) {});
});

$(`#timepicker_start1_simple`).timepicker({
	minuteStep: 1,
	showSeconds: false,
	showMeridian: false,
	disableFocus: false,
	defaultTime: 'current',
	modalBackdrop: false,
	appendWidgetTo: `#container-timepicker-fix-left-simple`
}).on('show.timepicker', function(e) {});
$(`#timepicker_end1_simple`).timepicker({
	minuteStep: 1,
	showSeconds: false,
	showMeridian: false,
	disableFocus: false,
	defaultTime: 'current',
	modalBackdrop: false,
	appendWidgetTo: `#container-timepicker-fix-right-simple`
}).on('show.timepicker', function(e) {});

$(`#timepicker_start1_one_simple`).timepicker({
	minuteStep: 1,
	showSeconds: false,
	showMeridian: false,
	disableFocus: false,
	defaultTime: 'current',
	modalBackdrop: false,
	appendWidgetTo: `#container-timepicker-fix-left-one-simple`
}).on('show.timepicker', function(e) {});
$(`#timepicker_end1_one_simple`).timepicker({
	minuteStep: 1,
	showSeconds: false,
	showMeridian: false,
	disableFocus: false,
	defaultTime: 'current',
	modalBackdrop: false,
	appendWidgetTo: `#container-timepicker-fix-right-one-simple`
}).on('show.timepicker', function(e) {});

$(`#timepicker_start1_two_simple`).timepicker({
	minuteStep: 1,
	showSeconds: false,
	showMeridian: false,
	disableFocus: false,
	defaultTime: 'current',
	modalBackdrop: false,
	appendWidgetTo: `#container-timepicker-fix-left-two-simple`
}).on('show.timepicker', function(e) {});
$(`#timepicker_end1_two_simple`).timepicker({
	minuteStep: 1,
	showSeconds: false,
	showMeridian: false,
	disableFocus: false,
	defaultTime: 'current',
	modalBackdrop: false,
	appendWidgetTo: `#container-timepicker-fix-right-two-simple`
}).on('show.timepicker', function(e) {});

function hideShowRowsTime(){
	if(modeTime=='simple'){
		$("#btn-mode-advanced").show();
		$("#btn-mode-simple").hide();
		$("#simple-row-time").show();
		$("#btn-one-new-date").show();

		daysForm.forEach(day => {
			$(`#${day}-row-time`).hide();
			$(`#${day}-one-date-in-row`).hide();
			$(`#${day}-one-date-out-row`).hide();
			$(`#${day}-one-date-delete-row`).hide();
			$(`#${day}-two-date-in-row`).hide();
			$(`#${day}-two-date-out-row`).hide();
			$(`#${day}-two-date-delete-row`).hide();
		});
	}else{
		$("#btn-mode-advanced").hide();
		$("#btn-mode-simple").show();
		$("#simple-row-time").hide();

		daysForm.forEach(day => {
			$(`#${day}-btn-one-new-date`).show();
			$(`#${day}-btn-two-new-date`).hide();
			if($(`#check-${day}`).prop('checked')){
				$(`#${day}-row-time`).show();
			}else{
				$(`#${day}-row-time`).hide();
			}
		});
	}
	//new code
	$("#btn-two-new-date").hide();
	$("#one-date-in-row").hide();
	$("#one-date-out-row").hide();
	$("#one-date-delete-row").hide();
	$("#two-date-in-row").hide();
	$("#two-date-out-row").hide();
	$("#two-date-delete-row").hide();

}





function resetElementsDraggable() {

	$(".draggable-employee-item").draggable(
		{
			helper: 'clone',
			revert: function (event, ui) {
				$(this).data("uiDraggable").originalPosition = {
					top: 0,
					left: 0
				};
				return !event;
			}
		}
	);

	$(".event-shedule").draggable(
		{
			snap: ".event-schedule-for-snap",
			snapMode: 'inner',
			opacity: .8,
			zIndex: 1000,
			snapTolerance: 35,
			// cursorAt: { left: 0 },
			containment: $('#container-table-schedule-inner'),
			revert: function (event, ui) {
				$(this).data("uiDraggable").originalPosition = {
					top: 0,
					left: 0
				};
				return !event;
			}
		}
	);

	$(".event-schedule-for-snap:not(.event-shedule)").droppable({
		greedy: false,
		tolerance: "touch",
		accept:'.event-shedule',
		drop: function (event, ui) {

			$( ".event-schedule-for-snap:not(.event-shedule)" ).droppable( "disable" );

			daysForm.forEach(day => { $(`#check-${day}`).prop('checked', false);});

			var start=$(this).attr('data-start');
			var end=$(this).attr('data-end');

			var daysGroup=JSON.parse($(ui.draggable).attr('data-schedule'));
			var idEmployee=$(ui.draggable).attr('data-id-employee');
			var daysIgnore=daysGroup;
			var daysAccepts=[];
			for (let index = 0; index < daysForm.length; index++) {
				if($(this).attr('data-day')==daysForm[index]){
					$(`#check-${daysForm[index]}`).prop('checked', true);
					daysAccepts.push(daysForm[index]);
					$(`#timepicker_start1_${daysForm[index]}`).timepicker('setTime', moment(start, 'HH:mm:ss').format('hh:mm A'));
					$(`#timepicker_end1_${daysForm[index]}`).timepicker('setTime', moment(end, 'HH:mm:ss').format('hh:mm A'));
					for (let i = 0; i < daysGroup.length-1; i++) {
						$(`#check-${daysForm[++index]}`).prop('checked', true);
						daysAccepts.push(daysForm[index]);
						$(`#timepicker_start1_${daysForm[index]}`).timepicker('setTime', moment(start, 'HH:mm:ss').format('hh:mm A'));
						$(`#timepicker_end1_${daysForm[index]}`).timepicker('setTime', moment(end, 'HH:mm:ss').format('hh:mm A'));
					}
				}

			}



			$("#id-employee-schedule-add").val(idEmployee);
			$("#date-schedule").val(null);
			getDataEditScheduleForDrag(idEmployee,start,end,daysIgnore,daysAccepts);

		}
	});


	$(".droppable-cell-shedule").droppable({
		accept:'.draggable-employee-item',
		drop: function (event, ui) {

			modeTime="simple";

			var idEmployee = $(ui.draggable).attr('data-employee-id');
			var nameEmployee = $(ui.draggable).attr('data-employee-name');

			var date = $(this).attr('data-date');
			var dayOfWeek=moment(date).clone().day();
			var timeStart = $(this).attr('data-start');
			var timeEnd = $(this).attr('data-end');

			$("#check-monday").prop('checked', false);
			$("#check-tuesday").prop('checked', false);
			$("#check-wednesday").prop('checked', false);
			$("#check-thursday").prop('checked', false);
			$("#check-friday").prop('checked', false);
			$("#check-saturday").prop('checked', false);
			$("#check-sunday").prop('checked', false);
			switch (dayOfWeek) {
				case 1:
				$("#check-monday").prop('checked', true);
				break;
				case 2:
				$("#check-tuesday").prop('checked', true);
				break;
				case 3:
				$("#check-wednesday").prop('checked', true);
				break;
				case 4:
				$("#check-thursday").prop('checked', true);
				break;
				case 5:
				$("#check-friday").prop('checked', true);
				break;
				case 6:
				$("#check-saturday").prop('checked', true);
				break;
				case 0:
				$("#check-sunday").prop('checked', true);
				break;
				default:
				$("#check-monday").prop('checked', true);
				break;
			}



			if (typeof timeStart === typeof undefined || typeof timeEnd === typeof undefined ) {
				var timeStart = moment().format('hh:mm A');
				var timeEnd = moment().clone().add(8,'hours').format('hh:mm A');
			}



			daysForm.forEach(day => {
				$(`#timepicker_start1_simple`).timepicker('setTime', timeStart);
				$(`#timepicker_end1_simple`).timepicker('setTime', timeEnd);

				$(`#timepicker_start1_${day}`).timepicker('setTime', timeStart);
				$(`#timepicker_end1_${day}`).timepicker('setTime', timeEnd);
			});


			$("#id-employee-schedule-add").val(idEmployee);
			$("#employee-name-selected").val(nameEmployee);
			$("#date-schedule").val(date);

			hideShowRowsTime();
			addOrEdit="add";
			employeesSelected=[];
			reloadTableSelectedEmployees();
			$("#btn-add-more-employee").show();
			$("#modal_add_shedule").modal("show");
		}
	});


	$(".droppable-tr-shedule").droppable({
		accept:'.draggable-employee-item',
		drop: function (event, ui) {
			modeTime="simple";

			var idEmployee = $(ui.draggable).attr('data-employee-id');
			var nameEmployee = $(ui.draggable).attr('data-employee-name');
			var status =$(ui.draggable).attr('data-status-schedule');
			//
			// if(status=="pending"){
			var scheduleElement = dataCalendarScheduleByAccess[parseInt($(this).attr('data-key-group'))].groups[parseInt($(this).attr('data-key-element-group'))];

			daysForm.forEach(day => { $(`#check-${day}`).prop('checked', false);});
			daysForm.forEach(day => { if(scheduleElement[day] == 'true')$(`#check-${day}`).prop('checked', true);});

			daysForm.forEach(day => {

				$(`#timepicker_start1_simple`).timepicker('setTime', moment(scheduleElement.start, 'HH:mm:ss').format('hh:mm A'));
				$(`#timepicker_end1_simple`).timepicker('setTime', moment(scheduleElement.end, 'HH:mm:ss').format('hh:mm A'));

				$(`#timepicker_start1_${day}`).timepicker('setTime', moment(scheduleElement.start, 'HH:mm:ss').format('hh:mm A'));
				$(`#timepicker_end1_${day}`).timepicker('setTime', moment(scheduleElement.end, 'HH:mm:ss').format('hh:mm A'));
			});


			$("#id-employee-schedule-add").val(idEmployee);
			$("#employee-name-selected").val(nameEmployee);
			$("#date-schedule").val(scheduleElement.date);
			hideShowRowsTime();
			addOrEdit="add";
			employeesSelected=[];
			reloadTableSelectedEmployees();
			$("#btn-add-more-employee").show();
			$("#modal_add_shedule").modal("show");
			// }else{
			//     showToast(1,"El horario de este empleado ya ha sido establecido en esta semana, puede dar clic derecho para editarlo.",4000)
			// }

		}
	});
	$( ".event-shedule" ).dblclick(function() {
		var idEmployee=$(this).attr("data-id-employee");
		employeeSelected=idEmployee;
		showOverlay();
		getDataHolidays();
	});



	reloadContextMenu();

}



function reloadContextMenu() {

	$.contextMenu('destroy');
	$.contextMenu({
		selector: '.event-shedule',
		callback: function (key, options) {

			var scheduleElement = dataCalendarScheduleByAccess[parseInt(options.$trigger.parent('td').parent('tr').attr('data-key-group'))].groups[parseInt(options.$trigger.parent('td').parent('tr').attr('data-key-element-group'))];
			if (key == 'edit') {
				$("#employee-name-selected").val(`${scheduleElement.name} ${scheduleElement.last_name}`);
				$("#date-schedule").val(scheduleElement.date);
				$("#id-employee-schedule-add").val(scheduleElement.id_employee);


				addOrEdit="edit";
				var start =moment(scheduleElement.start, 'HH:mm:ss').format('hh:mm A');
				var end =moment(scheduleElement.end, 'HH:mm:ss').format('hh:mm A');

				var star_complete = scheduleElement.start;
				var end_complete = scheduleElement.end;
				var monday = scheduleElement.monday;
				var tuesday = scheduleElement.tuesday;
				var wednesday = scheduleElement.wednesday;
				var thursday = scheduleElement.thursday;
				var friday = scheduleElement.friday;
				var saturday = scheduleElement.saturday;
				var sunday = scheduleElement.sunday;
				getDataEditSchedule(scheduleElement.id_employee, start, end, star_complete, end_complete, monday, tuesday, wednesday, thursday, friday, saturday, sunday);

			}else if (key == 'delete') {

				var daysToDisable=JSON.parse(options.$trigger.attr('data-schedule'));

				deleteSchedule(daysToDisable,scheduleElement.id_schedule,scheduleElement.id_employee);

			}else if(key == 'changeColor'){
				joe.set(scheduleElement.color);
				$("#color-change-employee").val(scheduleElement.id_employee);
				$("#modal_change_color").modal("show");
			}else if(key == 'filter'){
				setEmployeeFilter(scheduleElement.id_employee);
			}else if(key == 'reset'){
				$("#id-employee-reset-schedule").val(scheduleElement.id_employee);
				$("#modal_reset_schedule").modal("show");
			}else if(key == 'holidays'){
				employeeSelected=scheduleElement.id_employee;
				showOverlay();
				getDataHolidays();
			}

		},
		items: (
			{
				"changeColor": { name: "Cambiar Color", icon: "paste" },
				"edit": { name: "Editar", icon: "edit"},
				"delete": { name: "Eliminar", icon: "delete" },
				"filter": { name: "Filtrar", icon: `fa fa fa-filter` },
				"reset": { name: "Reiniciar Horario", icon: "delete" },
				"holidays": { name: "Revisar Vacaciones", icon: "loading"},
			}
		)
	});

	$.contextMenu({
		selector: '.employee-element-shedule',
		callback: function (key, options) {

			// var scheduleElement = dataCalendarScheduleByAccess[parseInt(options.$trigger.attr('data-key-group'))].groups[parseInt(options.$trigger.attr('data-key-element-group'))];

			if (key == 'edit') {
				var employeeId=options.$trigger.attr('data-id-employee');
				var employeeName=options.$trigger.attr('data-name-employee');
				$("#employee-name-selected").val(employeeName);
				$("#date-schedule").val(null);
				$("#id-employee-schedule-add").val(employeeId);

				addOrEdit="edit";
				var start =null;
				var end =null;
				getDataEditSchedule(employeeId,start,end);

			}else if(key == 'changeColor'){
				var employeeColor=options.$trigger.attr('data-color-employee');
				var employeeId=options.$trigger.attr('data-id-employee');
				joe.set(employeeColor);

				$("#color-change-employee").val(employeeId);
				$("#modal_change_color").modal("show");
			}else if(key == 'add'){
				modeTime='simple';

				daysForm.forEach(day => { $(`#check-${day}`).prop('checked', false);});

				var timeStart = moment().clone().format('hh:mm A');
				var timeEnd = moment().clone().add(8,'hours').format('hh:mm A');

				var employeeId=options.$trigger.attr('data-id-employee');
				var employeeName=options.$trigger.attr('data-name-employee');

				daysForm.forEach(day => {
					$(`#timepicker_start1_simple`).timepicker('setTime', timeStart);
					$(`#timepicker_end1_simple`).timepicker('setTime', timeEnd);

					$(`#timepicker_start1_${day}`).timepicker('setTime', timeStart);
					$(`#timepicker_end1_${day}`).timepicker('setTime', timeEnd);
				});


				$("#id-employee-schedule-add").val(employeeId);
				$("#employee-name-selected").val(employeeName);
				$("#date-schedule").val(null);

				hideShowRowsTime();
				addOrEdit="add";
				employeesSelected=[];
				reloadTableSelectedEmployees();
				$("#btn-add-more-employee").show();
				$("#modal_add_shedule").modal("show");
			}else if(key == 'reset'){
				var employeeId=options.$trigger.attr('data-id-employee');
				$("#id-employee-reset-schedule").val(employeeId);
				$("#modal_reset_schedule").modal("show");
			}else if(key == 'holidays'){
				var employeeId=options.$trigger.attr('data-id-employee');
				employeeSelected=employeeId;
				showOverlay();
				getDataHolidays();
			}

		},
		items: (
			{
				"changeColor": { name: "Cambiar Color", icon: "paste" },
				"add": { name: "Agregar Horario", icon: "add",disabled: function(key, options) {if(parseInt(options.$trigger.attr('data-status-employee'))>0)return true;return false;  } },
				"edit": { name: "Editar", icon: "edit",disabled: function(key, options) {if(parseInt(options.$trigger.attr('data-status-employee'))>0)return false;return true;  } },
				"reset": { name: "Reiniciar Horario", icon: "delete",disabled: function(key, options) {if(parseInt(options.$trigger.attr('data-status-employee'))>0)return false;return true;  } },
				"holidays": { name: "Revisar Vacaciones", icon: "loading",disabled: function(key, options) {if(parseInt(options.$trigger.attr('data-status-employee'))>0)return false;return true;  } },

			}
		)
	});

}




$("#btn-back-time").click(function () {
	if (tabSelected == 'weekly') {
		actualTime = startDate.subtract(7, 'days');
	}
	getRangeDates();
	tableEmployee.ajax.reload(function(){
		$( ".employee-element-shedule" ).dblclick(function() {
			var idEmployee=$(this).attr("data-employee-id");
			employeeSelected=idEmployee;
			showOverlay();
			getDataHolidays();
		});
	},false);

});

$("#btn-next-time").click(function () {
	if(tabSelected == 'weekly') {
		actualTime = startDate.add(7, 'days');
	}
	getRangeDates();
	tableEmployee.ajax.reload(function(){
		$( ".employee-element-shedule" ).dblclick(function() {
			var idEmployee=$(this).attr("data-employee-id");
			employeeSelected=idEmployee;
			showOverlay();
			getDataHolidays();
		});
	},false);
});

$("#btn-now-time").click(function () {
	actualTime = moment();
	//actualTime.locale(local);
	getRangeDates();
	tableEmployee.ajax.reload(function(){
		$( ".employee-element-shedule" ).dblclick(function() {
			var idEmployee=$(this).attr("data-employee-id");
			employeeSelected=idEmployee;
			showOverlay();
			getDataHolidays();
		});
	},false);
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

			textTitleRange = `${monday.format('MMMM DD')}, ${monday.format('YYYY')} - ${saturday.format('MMMM DD')}, ${saturday.format('YYYY')}`;
			textTitleRange = textTitleRange.toUpperCase();
		}

		$("#text-title-range").text(textTitleRange);

		if(!flagTableEmployee){
			$.fn.DataTable.ext.pager.numbers_length = 1;
			KTDatatablesEmployee.init();
		}


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

	th = document.createElement('th');
	th.setAttribute('class', classForTh);
	text = document.createTextNode("Sabado " + moment(actualDaysOfWeek[5]).format('DD/MM'));
	th.appendChild(text);
	tr.appendChild(th);

	if(enableWeekend){
		// th = document.createElement('th');
		// th.setAttribute('class', classForTh);
		// th.setAttribute('class', classForTh);
		// text = document.createTextNode("Sábado " + moment(actualDaysOfWeek[5]).format('DD/MM'));
		// th.appendChild(text);
		// tr.appendChild(th);

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
				// daysTmp.pop();
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
					div.setAttribute('data-original-title', `${schedule.name} ${schedule.last_name}`);
					div.setAttribute('data-mode-schedule', schedule.mode);


					td.appendChild(div);



					div = document.createElement('div');
					div.setAttribute('data-day',tmpDaysEnable[0]);
					div.setAttribute('data-start',schedule.start);
					div.setAttribute('data-end',schedule.end);
					div.setAttribute('class', `event-schedule-for-snap`);
					div.setAttribute('style', `background:#0000; width:100%; height: 15px; min-height: 15px; max-height: 15px;position: absolute;margin: auto;top: -1px;bottom: 0;left:0;right:0;`);
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
						div.setAttribute('data-original-title', `${schedule.name} ${schedule.last_name}`);
						div.setAttribute('data-mode-schedule', schedule.mode);


						td.appendChild(div);

						div = document.createElement('div');
						div.setAttribute('data-day',daySnapTmp);
						div.setAttribute('data-start',schedule.start);
						div.setAttribute('data-end',schedule.end);
						div.setAttribute('class', `event-schedule-for-snap`);
						div.setAttribute('style', `background:#0000; width:100%; height: 15px; min-height: 15px; max-height: 15px;position: absolute;margin: auto;top: -1px;bottom: 0;left:0;right:0;`);
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
			tr.setAttribute('class', classForDroppableTr);
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

			var daysWeekFor=(enableWeekend)?7:6;


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
			<input class="check-holidays" onchange="updateHolidayStatus(${holiday.id})" type="checkbox" ${(holiday.status=="accept")?'checked="checked"':''} ${(holiday.status_entrance=="in")?'disabled':''}>Del ${moment(holiday.start, "YYYY-MM-DD").format("DD MMMM")} al ${moment(holiday.end, "YYYY-MM-DD").format("DD MMMM YYYY")}
			<span></span>
			</label>
			</div>
			</div>
			</div>
			`;
		});
		containerHolidaysDates.html(htmlData);
	}else{
		containerHolidaysDates.html(`<div class="w-100 text-center"><p class="mt-4">El empleado no cuenta vacaciones para validar.</p></div>`);
	}
	$('[data-toggle="kt-tooltip"]').tooltip();
}

function setEmployeeFilter(id=null){
	employeeSelected=id;
	getDataByRange(tabSelected);
	getDataHolidays();
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

			resetElementsDraggable();
			$('[data-toggle="kt-tooltip"]').tooltip();

			$(".event-shedule").hover(function() {
				var classList=$(this).attr("class").split(/\s+/);
				$(`.${classList[1]}`).tooltip('show');
			},function(){
				var classList=$(this).attr("class").split(/\s+/);
				$(`.${classList[1]}`).tooltip('hide');
			});
			$('[data-toggle="kt-tooltip"]').tooltip();


			if(res.length<=0)
			showToast(1,"Nada que mostrar");

		},
		error: function (xhr, status, error) {
			console.log(JSON.stringify(xhr));
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
			hideOverlay();
		},
		error: function (xhr, status, error) {

			hideOverlay();
			sendErrorsShow([error]);
		},
	});
}


function addSheduleEmployee(forceModeTime=false,beforeStart=null,beforeEnd=null){
	var days=[];
	var daysTmp=[];
	var monday= $("#check-monday").is(':checked');
	var tuesday= $("#check-tuesday").is(':checked');
	var wednesday= $("#check-wednesday").is(':checked');
	var thursday= $("#check-thursday").is(':checked');
	var friday= $("#check-friday").is(':checked');
	var saturday= $("#check-saturday").is(':checked');
	var sunday= $("#check-sunday").is(':checked');



	if(monday)daysTmp.push({day:0,start:moment($("#timepicker_start1_monday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_monday").val(), 'H:mm').format('h:mm A')});
	if(tuesday)daysTmp.push({day:1,start:moment($("#timepicker_start1_tuesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_tuesday").val(), 'H:mm').format('h:mm A')});
	if(wednesday)daysTmp.push({day:2,start:moment($("#timepicker_start1_wednesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_wednesday").val(), 'H:mm').format('h:mm A')});
	if(thursday)daysTmp.push({day:3,start:moment($("#timepicker_start1_thursday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_thursday").val(), 'H:mm').format('h:mm A')});
	if(friday)daysTmp.push({day:4,start:moment($("#timepicker_start1_friday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_friday").val(), 'H:mm').format('h:mm A')});
	if(saturday)daysTmp.push({day:5,start:moment($("#timepicker_start1_saturday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_saturday").val(), 'H:mm').format('h:mm A')});
	if(sunday)daysTmp.push({day:6,start:moment($("#timepicker_start1_sunday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_sunday").val(), 'H:mm').format('h:mm A')});

	var flagMoreGroups=true;
	var tmpTime={start:null,end:null};
	daysTmp.forEach(day => {
		if((tmpTime.start!=day.start || tmpTime.end!=day.end) && (tmpTime.start!=null && tmpTime.end!=null))
		flagMoreGroups=false;
		tmpTime={start:day.start,end:day.end};
	});


	if((modeTime=='simple' && flagMoreGroups ) || !forceModeTime){
		var useSimple=true;
		if(beforeStart!=null && beforeEnd!=null){
			if(daysTmp.length>0){
				var flagCheckAll=true;
				daysTmp.forEach(dayTmp => {
					if(beforeStart!=dayTmp.start || beforeEnd!=dayTmp.end)
					flagCheckAll=false;
				});
				if(flagCheckAll){
					useSimple=true;
				}else{
					useSimple=false;
				}
			}else{
				useSimple=false;
			}
		}else{
			useSimple=true;
		}

		if(useSimple){
			if(monday)days.push({day:0,start:moment($("#timepicker_start1_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_simple").val(), 'H:mm').format('h:mm A')});
			if(tuesday)days.push({day:1,start:moment($("#timepicker_start1_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_simple").val(), 'H:mm').format('h:mm A')});
			if(wednesday)days.push({day:2,start:moment($("#timepicker_start1_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_simple").val(), 'H:mm').format('h:mm A')});
			if(thursday)days.push({day:3,start:moment($("#timepicker_start1_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_simple").val(), 'H:mm').format('h:mm A')});
			if(friday)days.push({day:4,start:moment($("#timepicker_start1_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_simple").val(), 'H:mm').format('h:mm A')});
			if(saturday)days.push({day:5,start:moment($("#timepicker_start1_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_simple").val(), 'H:mm').format('h:mm A')});
			if(sunday)days.push({day:6,start:moment($("#timepicker_start1_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_simple").val(), 'H:mm').format('h:mm A')});


			if ($("#one-date-in-row").is(":visible")) {
				if(monday)days.push({day:0,one: 1,start:moment($("#timepicker_start1_one_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_simple").val(), 'H:mm').format('h:mm A')});
				if(tuesday)days.push({day:1,one: 1,start:moment($("#timepicker_start1_one_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_simple").val(), 'H:mm').format('h:mm A')});
				if(wednesday)days.push({day:2,one: 1,start:moment($("#timepicker_start1_one_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_simple").val(), 'H:mm').format('h:mm A')});
				if(thursday)days.push({day:3,one: 1,start:moment($("#timepicker_start1_one_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_simple").val(), 'H:mm').format('h:mm A')});
				if(friday)days.push({day:4,one: 1,start:moment($("#timepicker_start1_one_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_simple").val(), 'H:mm').format('h:mm A')});
				if(saturday)days.push({day:5,one: 1,start:moment($("#timepicker_start1_one_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_simple").val(), 'H:mm').format('h:mm A')});
				if(sunday)days.push({day:6,one: 1,start:moment($("#timepicker_start1_one_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_simple").val(), 'H:mm').format('h:mm A')});
			}

			if ($("#two-date-in-row").is(":visible")) {
				if(monday)days.push({day:0,two: 1,start:moment($("#timepicker_start1_two_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_simple").val(), 'H:mm').format('h:mm A')});
				if(tuesday)days.push({day:1,two: 1,start:moment($("#timepicker_start1_two_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_simple").val(), 'H:mm').format('h:mm A')});
				if(wednesday)days.push({day:2,two: 1,start:moment($("#timepicker_start1_two_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_simple").val(), 'H:mm').format('h:mm A')});
				if(thursday)days.push({day:3,two: 1,start:moment($("#timepicker_start1_two_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_simple").val(), 'H:mm').format('h:mm A')});
				if(friday)days.push({day:4,two: 1,start:moment($("#timepicker_start1_two_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_simple").val(), 'H:mm').format('h:mm A')});
				if(saturday)days.push({day:5,two: 1,start:moment($("#timepicker_start1_two_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_simple").val(), 'H:mm').format('h:mm A')});
				if(sunday)days.push({day:6,two: 1,start:moment($("#timepicker_start1_two_simple").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_simple").val(), 'H:mm').format('h:mm A')});
			}

		}else{
			if(monday)days.push({day:0,start:moment($("#timepicker_start1_monday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_monday").val(), 'H:mm').format('h:mm A')});
			if(tuesday)days.push({day:1,start:moment($("#timepicker_start1_tuesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_tuesday").val(), 'H:mm').format('h:mm A')});
			if(wednesday)days.push({day:2,start:moment($("#timepicker_start1_wednesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_wednesday").val(), 'H:mm').format('h:mm A')});
			if(thursday)days.push({day:3,start:moment($("#timepicker_start1_thursday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_thursday").val(), 'H:mm').format('h:mm A')});
			if(friday)days.push({day:4,start:moment($("#timepicker_start1_friday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_friday").val(), 'H:mm').format('h:mm A')});
			if(saturday)days.push({day:5,start:moment($("#timepicker_start1_saturday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_saturday").val(), 'H:mm').format('h:mm A')});
			if(sunday)days.push({day:6,start:moment($("#timepicker_start1_sunday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_sunday").val(), 'H:mm').format('h:mm A')});

			if ($("#monday-one-date-in-row").is(":visible") || $("#tuesday-one-date-in-row").is(":visible") || $("#wednesday-one-date-in-row").is(":visible") || $("#thursday-one-date-in-row").is(":visible") || $("#friday-one-date-in-row").is(":visible") || $("#sunday-one-date-in-row").is(":visible") || $("#saturday-one-date-in-row").is(":visible")) {
			if(monday)days.push({day:0,one: 1,start:moment($("#timepicker_start1_one_monday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_monday").val(), 'H:mm').format('h:mm A')});
			if(tuesday)days.push({day:1,one: 1,start:moment($("#timepicker_start1_one_tuesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_tuesday").val(), 'H:mm').format('h:mm A')});
			if(wednesday)days.push({day:2,one: 1,start:moment($("#timepicker_start1_one_wednesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_wednesday").val(), 'H:mm').format('h:mm A')});
			if(thursday)days.push({day:3,one: 1,start:moment($("#timepicker_start1_one_thursday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_thursday").val(), 'H:mm').format('h:mm A')});
			if(friday)days.push({day:4,one: 1,start:moment($("#timepicker_start1_one_friday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_friday").val(), 'H:mm').format('h:mm A')});
			if(saturday)days.push({day:5,one: 1,start:moment($("#timepicker_start1_one_saturday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_saturday").val(), 'H:mm').format('h:mm A')});
			if(sunday)days.push({day:6,one: 1,start:moment($("#timepicker_start1_one_sunday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_sunday").val(), 'H:mm').format('h:mm A')});
			}

			if ($("#monday-two-date-in-row").is(":visible") || $("#tuesday-two-date-in-row").is(":visible") || $("#wednesday-two-date-in-row").is(":visible") || $("#thursday-two-date-in-row").is(":visible") || $("#friday-two-date-in-row").is(":visible") || $("#sunday-two-date-in-row").is(":visible") || $("#saturday-two-date-in-row").is(":visible")) {
			if(monday)days.push({day:0,two: 1,start:moment($("#timepicker_start1_two_monday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_monday").val(), 'H:mm').format('h:mm A')});
			if(tuesday)days.push({day:1,two: 1,start:moment($("#timepicker_start1_two_tuesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_tuesday").val(), 'H:mm').format('h:mm A')});
			if(wednesday)days.push({day:2,two: 1,start:moment($("#timepicker_start1_two_wednesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_wednesday").val(), 'H:mm').format('h:mm A')});
			if(thursday)days.push({day:3,two: 1,start:moment($("#timepicker_start1_two_thursday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_thursday").val(), 'H:mm').format('h:mm A')});
			if(friday)days.push({day:4,two: 1,start:moment($("#timepicker_start1_two_friday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_friday").val(), 'H:mm').format('h:mm A')});
			if(saturday)days.push({day:5,two: 1,start:moment($("#timepicker_start1_two_saturday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_saturday").val(), 'H:mm').format('h:mm A')});
			if(sunday)days.push({day:6,two: 1,start:moment($("#timepicker_start1_two_sunday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_sunday").val(), 'H:mm').format('h:mm A')});
			}
		}

	}else{
		if(monday)days.push({day:0,start:moment($("#timepicker_start1_monday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_monday").val(), 'H:mm').format('h:mm A')});
		if(tuesday)days.push({day:1,start:moment($("#timepicker_start1_tuesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_tuesday").val(), 'H:mm').format('h:mm A')});
		if(wednesday)days.push({day:2,start:moment($("#timepicker_start1_wednesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_wednesday").val(), 'H:mm').format('h:mm A')});
		if(thursday)days.push({day:3,start:moment($("#timepicker_start1_thursday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_thursday").val(), 'H:mm').format('h:mm A')});
		if(friday)days.push({day:4,start:moment($("#timepicker_start1_friday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_friday").val(), 'H:mm').format('h:mm A')});
		if(saturday)days.push({day:5,start:moment($("#timepicker_start1_saturday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_saturday").val(), 'H:mm').format('h:mm A')});
		if(sunday)days.push({day:6,start:moment($("#timepicker_start1_sunday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_sunday").val(), 'H:mm').format('h:mm A')});

		if ($("#monday-one-date-in-row").is(":visible") || $("#tuesday-one-date-in-row").is(":visible") || $("#wednesday-one-date-in-row").is(":visible") || $("#thursday-one-date-in-row").is(":visible") || $("#friday-one-date-in-row").is(":visible") || $("#sunday-one-date-in-row").is(":visible") || $("#saturday-one-date-in-row").is(":visible")) {
		if(monday)days.push({day:0,one: 1,start:moment($("#timepicker_start1_one_monday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_monday").val(), 'H:mm').format('h:mm A')});
		if(tuesday)days.push({day:1,one: 1,start:moment($("#timepicker_start1_one_tuesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_tuesday").val(), 'H:mm').format('h:mm A')});
		if(wednesday)days.push({day:2,one: 1,start:moment($("#timepicker_start1_one_wednesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_wednesday").val(), 'H:mm').format('h:mm A')});
		if(thursday)days.push({day:3,one: 1,start:moment($("#timepicker_start1_one_thursday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_thursday").val(), 'H:mm').format('h:mm A')});
		if(friday)days.push({day:4,one: 1,start:moment($("#timepicker_start1_one_friday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_friday").val(), 'H:mm').format('h:mm A')});
		if(saturday)days.push({day:5,one: 1,start:moment($("#timepicker_start1_one_saturday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_saturday").val(), 'H:mm').format('h:mm A')});
		if(sunday)days.push({day:6,one: 1,start:moment($("#timepicker_start1_one_sunday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_one_sunday").val(), 'H:mm').format('h:mm A')});
		}

		if ($("#monday-two-date-in-row").is(":visible") || $("#tuesday-two-date-in-row").is(":visible") || $("#wednesday-two-date-in-row").is(":visible") || $("#thursday-two-date-in-row").is(":visible") || $("#friday-two-date-in-row").is(":visible") || $("#sunday-two-date-in-row").is(":visible") || $("#saturday-two-date-in-row").is(":visible")) {
		if(monday)days.push({day:0,two:1,start:moment($("#timepicker_start1_two_monday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_monday").val(), 'H:mm').format('h:mm A')});
		if(tuesday)days.push({day:1,two:1,start:moment($("#timepicker_start1_two_tuesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_tuesday").val(), 'H:mm').format('h:mm A')});
		if(wednesday)days.push({day:2,two:1,start:moment($("#timepicker_start1_two_wednesday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_wednesday").val(), 'H:mm').format('h:mm A')});
		if(thursday)days.push({day:3,two:1,start:moment($("#timepicker_start1_two_thursday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_thursday").val(), 'H:mm').format('h:mm A')});
		if(friday)days.push({day:4,two:1,start:moment($("#timepicker_start1_two_friday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_friday").val(), 'H:mm').format('h:mm A')});
		if(saturday)days.push({day:5,two:1,start:moment($("#timepicker_start1_two_saturday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_saturday").val(), 'H:mm').format('h:mm A')});
		if(sunday)days.push({day:6,two:1,start:moment($("#timepicker_start1_two_sunday").val(), 'H:mm').format('h:mm A'),end:moment($("#timepicker_end1_two_sunday").val(), 'H:mm').format('h:mm A')});
	}
	}


	var id_employee= $("#id-employee-schedule-add").val();
	var date= $("#date-schedule").val();
	var id= $("#id-schedule-register").val();
	employeesSelected.push({id:id_employee});
	showOverlay();

	var urlForEdit="schedule/edit_schedule_employee";
	var urlForAdd="schedule/add_schedule_employee";

	$.ajax({
		url: (addOrEdit=="edit")?urlForEdit:urlForAdd,
		type: 'POST',
		data: {
			days:days,
			ids_employee:employeesSelected,
			date:date,
			date_start:startDate.format('YYYY-MM-DD'),
			date_end:endDate.format('YYYY-MM-DD'),
			mode_time:modeTime,
			start_simple: moment($("#timepicker_start1_simple").val(), 'H:mm').format('h:mm A'),
			end_simple: moment($("#timepicker_end1_simple").val(), 'H:mm').format('h:mm A'),
			id:id,
			_token: $('#token_ajax').val()
		},
		success: function (res) {

			hideOverlay();
			if (res.status == false) {

				if (res.type === 'simple') {
					var errorsHtml = "";
						errorsHtml += `<li class="mt-2" style="text-transform: initial;">` + res.response + `</li>`;
					errorsHtml =
					`<div class="alert-text">
					<ul class="float-left text-left">
					` + errorsHtml + `
					</ul>
					</div>`;

					$("#modal_errors").modal("show");
					$("#content-errors").html(errorsHtml);

				}else {
					setEmployeeFilter(null);
					sendErrorsShow(res.response);
				}
			} else {
				employeesSelected=[];
				$("#modal_add_shedule").modal("hide");

				getRangeDates();

				tableEmployee.ajax.reload(function(){
					$( ".employee-element-shedule" ).dblclick(function() {
						var idEmployee=$(this).attr("data-employee-id");
						employeeSelected=idEmployee;
						showOverlay();
						getDataHolidays();
					});
				},false);
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

function getDataEditSchedule(id, start, end, s=null, e=null, mo = null, tu = null, wen = null, th = null, fr = null, sa = null, su = null ){

	showOverlay();
	$.ajax({
		url: "schedule/data_schedule_employee",
		type: 'POST',
		data: {
			id:id,
			date_start:startDate.format('YYYY-MM-DD'),
			date_end:endDate.format('YYYY-MM-DD'),
			start:s,
			end:e,
			monday: mo,
			tuesday: tu,
			wednesday: wen,
			thursday: th,
			friday: fr,
			saturday: sa,
			sunday: su,
			_token: $('#token_ajax').val()
		},
		success: function (res) {
			hideOverlay();

			$("#check-monday").prop('checked', false);
			$("#check-tuesday").prop('checked', false);
			$("#check-wednesday").prop('checked', false);
			$("#check-thursday").prop('checked', false);
			$("#check-friday").prop('checked', false);
			$("#check-saturday").prop('checked', false);
			$("#check-sunday").prop('checked', false);
			// console.log(res,'resultado');
			$("#id-schedule-register").val(res[0].id);
			res.forEach(schedule => {
				daysForm.forEach(day => {
					if(schedule[day]=='true'){
						$(`#check-${day}`).prop('checked', true);
						$(`#timepicker_start1_${day}`).timepicker('setTime',  moment(schedule.start, 'HH:mm:ss').format('hh:mm A'));
						$(`#timepicker_end1_${day}`).timepicker('setTime',  moment(schedule.end, 'HH:mm:ss').format('hh:mm A'));
						$(`#timepicker_start1_simple`).timepicker('setTime',  moment(schedule.start, 'HH:mm:ss').format('hh:mm A'));
						$(`#timepicker_end1_simple`).timepicker('setTime',  moment(schedule.end, 'HH:mm:ss').format('hh:mm A'));
						modeTime=schedule.mode;
					}
				});
			});

			if(start==null || end==null){
				start=moment().clone().format('hh:mm A');
				end=moment().clone().format('hh:mm A');
			}
			daysForm.forEach(day => {
				$(`#${day}-btn-one-new-date`).hide();
				if(!$(`#check-${day}`).prop('checked')){
					$(`#timepicker_start1_${day}`).timepicker('setTime',  start);
					$(`#timepicker_end1_${day}`).timepicker('setTime',  end);

				}
			});

			hideShowRowsTime();
			employeesSelected=[];
			reloadTableSelectedEmployees();
			$("#btn-add-more-employee").hide();
			$("#btn-one-new-date").hide();
			daysForm.forEach(day => {
				$(`#${day}-btn-one-new-date`).hide();
				$(`#${day}-one-date-in-row`).hide();
				$(`#${day}-two-date-in-row`).hide();
				$(`#${day}-one-date-out-row`).hide();
				$(`#${day}-two-date-out-row`).hide();
				$(`#${day}-one-date-delete-row`).hide();
				$(`#${day}-two-date-delete-row`).hide();
			});
			$("#modal_add_shedule").modal("show");

		},
		error: function (xhr, status, error) {
			hideOverlay();
			console.log(JSON.stringify(xhr));
			sendErrorsShow([error]);
		},
	});
}


function getDataEditScheduleForDrag(id,start,end,ignore,accepts){

	showOverlay();
	$.ajax({
		url: "schedule/data_schedule_employee",
		type: 'POST',
		data: {
			id:id,
			date_start:startDate.format('YYYY-MM-DD'),
			date_end:endDate.format('YYYY-MM-DD'),
			_token: $('#token_ajax').val()
		},
		success: function (res) {
			//hideOverlay();


			$("#check-monday").prop('checked', false);
			$("#check-tuesday").prop('checked', false);
			$("#check-wednesday").prop('checked', false);
			$("#check-thursday").prop('checked', false);
			$("#check-friday").prop('checked', false);
			$("#check-saturday").prop('checked', false);
			$("#check-sunday").prop('checked', false);

			var startTmp=null;
			var endTmp=null;

			res.forEach(schedule => {
				daysForm.forEach(day => {
					if(schedule[day]=='true'){
						$(`#check-${day}`).prop('checked', true);
						$(`#timepicker_start1_${day}`).timepicker('setTime',  moment(schedule.start, 'HH:mm:ss').format('hh:mm A'));
						$(`#timepicker_end1_${day}`).timepicker('setTime',  moment(schedule.end, 'HH:mm:ss').format('hh:mm A'));
						$(`#timepicker_start1_simple`).timepicker('setTime',  moment(schedule.start, 'HH:mm:ss').format('hh:mm A'));
						$(`#timepicker_end1_simple`).timepicker('setTime',  moment(schedule.end, 'HH:mm:ss').format('hh:mm A'));
						modeTime=schedule.mode;
						startTmp=moment(schedule.start, 'HH:mm:ss').format('hh:mm A');
						endTmp=moment(schedule.end, 'HH:mm:ss').format('hh:mm A');
					}
				});
			});

			ignore.forEach(day => {$(`#check-${day}`).prop('checked', false);});

			accepts.forEach(day => {
				$(`#timepicker_start1_${day}`).timepicker('setTime',  moment(start, 'HH:mm:ss').format('hh:mm A'));
				$(`#timepicker_end1_${day}`).timepicker('setTime',  moment(end, 'HH:mm:ss').format('hh:mm A'));
				$(`#check-${day}`).prop('checked', true);
			});

			hideShowRowsTime();
			addOrEdit="edit";
			employeesSelected=[];
			reloadTableSelectedEmployees();
			addSheduleEmployee(false, startTmp,endTmp);
			// $("#modal_add_shedule").modal("show");

		},
		error: function (xhr, status, error) {
			// hideOverlay();
			console.log(JSON.stringify(xhr));
			sendErrorsShow([error]);
		},
	});
}

function deleteSchedule(daysToDisable,idSchedule,id_employee){

	showOverlay();
	$.ajax({
		url: "schedule/delete_schedule_employee",
		type: 'POST',
		data: {
			days_to_disable:daysToDisable,
			id:idSchedule,
			id_employee:id_employee,
			date_start:startDate.format('YYYY-MM-DD'),
			date_end:endDate.format('YYYY-MM-DD'),
			_token: $('#token_ajax').val()
		},
		success: function (res) {
			hideOverlay();
			if (res.status == false) {
				sendErrorsShow(res.response);
			} else {
				getRangeDates();
				tableEmployee.ajax.reload(function(){
					$( ".employee-element-shedule" ).dblclick(function() {
						var idEmployee=$(this).attr("data-employee-id");
						employeeSelected=idEmployee;
						showOverlay();
						getDataHolidays();
					});
				},false);
				showToast(0,res.response);
			}
		},
		error: function (xhr, status, error) {
			setEmployeeFilter(null);
			hideOverlay();
			if(xhr.responseJSON.errors.hasOwnProperty('id_employee')){
				sendErrorsShow([xhr.responseJSON.errors.id_employee]);
			}else{
				sendErrorsShow([error]);
			}
		},
	});
}

function resetSchedule(){
	showOverlay();
	$.ajax({
		url: "schedule/reset_schedule_employee",
		type: 'POST',
		data: {
			id:$("#id-employee-reset-schedule").val(),
			_token: $('#token_ajax').val()
		},
		success: function (res) {
			$("#modal_reset_schedule").modal("hide");
			hideOverlay();
			if (res.status == false) {
				sendErrorsShow(res.response);
			} else {
				getRangeDates();
				tableEmployee.ajax.reload(function(){
					$( ".employee-element-shedule" ).dblclick(function() {
						var idEmployee=$(this).attr("data-employee-id");
						employeeSelected=idEmployee;
						showOverlay();
						getDataHolidays();
					});
				},false);

				showToast(0,res.response);
			}
		},
		error: function (xhr, status, error) {
			$("#modal_reset_schedule").modal("hide");
			setEmployeeFilter(null);
			hideOverlay();
			console.log(JSON.stringify(xhr));
			if(xhr.responseJSON.errors.hasOwnProperty('id')){
				sendErrorsShow([xhr.responseJSON.errors.id]);
			}else{
				sendErrorsShow([error]);
			}
		},
	});
}

function updateHolidayStatus(id){
	showOverlay();
	$.ajax({
		url: "schedule/update_status_holiday",
		type: 'POST',
		data: {
			id:id,
			_token: $('#token_ajax').val()
		},
		success: function (res) {
			getDataHolidays();
			hideOverlay();
			if (res.status == false) {
				sendErrorsShow(res.response);
			} else {
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

function changeColor(){
	showOverlay();
	$.ajax({
		url: "schedule/change_color_employee",
		type: 'POST',
		data: {
			color:colorSelected,
			id:$('#color-change-employee').val(),
			_token: $('#token_ajax').val()
		},
		success: function (res) {
			hideOverlay();
			if (res.status == false) {
				sendErrorsShow(res.response);
			} else {
				getRangeDates();
				tableEmployee.ajax.reload(function(){
					$( ".employee-element-shedule" ).dblclick(function() {
						var idEmployee=$(this).attr("data-employee-id");
						employeeSelected=idEmployee;
						showOverlay();
						getDataHolidays();
					});
				},false);
				showToast(0,res.response);
			}
			$("#modal_change_color").modal("hide");
		},
		error: function (xhr, status, error) {
			hideOverlay();
			$("#modal_change_color").modal("hide");
			console.log(JSON.stringify(xhr));
			sendErrorsShow([error]);
		},
	});
}


function setModeTime(mode){
	modeTime=mode;
	hideShowRowsTime();
}

function addNewDate(number) {
	if (number == 1) {
		$("#btn-one-new-date").hide();
		$("#btn-two-new-date").show();

		$("#one-date-in-row").show();
		$("#one-date-out-row").show();
		$("#one-date-delete-row").show();
	}
	if (number == 2) {
		$("#btn-two-new-date").hide();

		$("#two-date-in-row").show();
		$("#two-date-out-row").show();
		$("#two-date-delete-row").show();
	}
}

function addNewDateAdvance(number, day) {
	if (number == 1) {
		$(`#${day}-btn-one-new-date`).hide();
		$(`#${day}-btn-two-new-date`).show();

		$(`#${day}-one-date-in-row`).show();
		$(`#${day}-one-date-out-row`).show();
		$(`#${day}-one-date-delete-row`).show();
	}
	if (number == 2) {
		$(`#${day}-btn-two-new-date`).hide();

		$(`#${day}-two-date-in-row`).show();
		$(`#${day}-two-date-out-row`).show();
		$(`#${day}-two-date-delete-row`).show();
	}
}

function deleteNewTime(number) {
	if (number == 1) {
		$("#btn-one-new-date").show();
		$("#one-date-in-row").hide();
		$("#one-date-out-row").hide();
		$("#one-date-delete-row").hide();
		$("#btn-two-new-date").hide();
	}
	if (number == 2) {
		$("#two-date-in-row").hide();
		$("#two-date-out-row").hide();
		$("#two-date-delete-row").hide();
		$("#btn-two-new-date").show();
	}
}

function deleteNewTimeAdvance(number, day) {
	if (number == 1) {
		$(`#${day}-btn-one-new-date`).show();
		$(`#${day}-one-date-in-row`).hide();
		$(`#${day}-one-date-out-row`).hide();
		$(`#${day}-one-date-delete-row`).hide();
		$(`#${day}-btn-two-new-date`).hide();
	}
	if (number == 2) {
		$(`#${day}-two-date-in-row`).hide();
		$(`#${day}-two-date-out-row`).hide();
		$(`#${day}-two-date-delete-row`).hide();
		$(`#${day}-btn-two-new-date`).show();
	}
}
//////////////////////////////////////////////////////messages
function emptyTableSchedule(){
	showToast(1,"Arrastre un empleado de la tabla derecha.",2500);
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
