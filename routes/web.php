<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

Route::get('login', 'Security\LoginController@index')->name('login');
Route::post('login', 'Security\LoginController@login')->name('login_in');
Route::get('logout', 'Security\LoginController@logout')->name('logout');

//Auth::routes();
//password reset routes
Route::post('password/email', 'Auth\EmployeeForgotPasswordController@sendResetLinkEmail')->name('employee.password.email');
Route::get('password/reset', 'Auth\EmployeeForgotPasswordController@showLinkRequestForm')->name('employee.password.request');
Route::post('password/reset', 'Auth\EmployeeResetPasswordController@reset')->name('employee.password.update');
Route::get('password/reset/{token}', 'Auth\EmployeeResetPasswordController@showResetForm')->name('employee.password.reset');


Route::get('pdf/{id}/{id2}', 'PdfController@invoice');
Route::get('print_php_info', 'PdfController@printPhp');

Route::group(['prefix' => '/', 'middleware' => ['auth', 'enable_employee', 'rol.permission']], function () {



    Route::post('refresh_csrf', 'DashboardController@refreshCsrf');

    Route::get('/', 'DashboardController@index');
    Route::get('restricted_permission', 'RestrictedPermission@index');

    //dashboard
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('dashboard/dataTable_group_calendar', 'DashboardController@dataTableGroupCalendar')->name('dashboard_data_table_group_calendar'); //table calendar
    Route::post('dashboard/add_group_session', 'DashboardController@storeGroupSession')->name('dashboard_add_group_session');
    Route::post('dashboard/check_balance', 'DashboardController@checkBalance')->name('dashboard_check_balance');
    Route::post('dashboard/get_data_by_range', 'DashboardController@getDataByRange')->name('dashboard_get_data_by_range');
    Route::post('dashboard/add_new_session', 'DashboardController@storeNewSession')->name('dashboard_add_new_session');
    Route::post('dashboard/data_table_sessions_group', 'DashboardController@dataTableSessionsGroup')->name('dashboard_data_table_sessions_group');
    Route::post('dashboard/delete_session', 'DashboardController@deleteSession')->name('dashboard_delete_session');
    Route::post('dashboard/edit_group_sessions', 'DashboardController@editGroupSessions')->name('dashboard_edit_group_sessions');
    Route::post('dashboard/edit_group_sessions_drag', 'DashboardController@editGroupSessionsDrag')->name('dashboard_edit_group_sessions_drag');
    Route::post('dashboard/delete_group_sessions', 'DashboardController@deleteGroupSessions')->name('dashboard_delete_group_sessions');
    Route::post('dashboard/dataTable_employee_select', 'DashboardController@dataTableEmployeeSelected')->name('dashboard_data_table_employee_select');//
    Route::post('dashboard/create_group', 'DashboardController@storeGroup')->name('dashboard_create_group');
    Route::post('dashboard/create_employee', 'DashboardController@storeEmployee')->name('dashboard_create_employee');
    Route::post('dashboard/move_sessions', 'DashboardController@moveSessions')->name('dashboard_move_sessions');
    Route::post('dashboard/lock_group_session_add', 'DashboardController@lockGroupSessionAdd')->name('dashboard_lock_group_session_add');
    Route::post('dashboard/load_template', 'DashboardController@loadTemplate')->name('dashboard_load_template');
    Route::post('dashboard/load_template_check', 'DashboardController@loadTemplateCheck')->name('dashboard_load_template_check');
    Route::post('dashboard/dataTable_groups_sessions', 'DashboardController@dataTableGroupSessions')->name('dashboard_dataTable_groups_sessions');
    Route::post('dashboard/set_emmployee_group', 'DashboardController@setEmployeeGroup')->name('dashboard_set_emmployee_group');
    Route::post('dashboard/dataTable_groups_sessions2', 'DashboardController@dataTableGroupSessions2')->name('dashboard_dataTable_groups_sessions2');
    Route::post('dashboard/move_sessions2', 'DashboardController@moveSessions2')->name('dashboard_move_sessions2');
    Route::post('dashboard/print_itinerary', 'DashboardController@printItinerary')->name('dashboard_print_itinerary');//




    //template
    Route::get('template', 'TemplateController@index')->name('template');
    Route::post('template/get_data', 'TemplateController@getData')->name('template_get_data');
    Route::post('template/create_template', 'TemplateController@storeTemplate')->name('template_create_template');
    Route::post('template/get_template_list', 'TemplateController@getTemplateList')->name('template_get_template_list');
    Route::post('template/add_group_session', 'TemplateController@storeGroupSession')->name('template_add_group_session');
    Route::post('template/edit_hour_session', 'TemplateController@editHourGroupSession')->name('template_edit_hour_group_session');
    Route::post('template/dataTable_group_calendar', 'TemplateController@dataTableGroupCalendar')->name('template_data_table_group_calendar');
    Route::post('template/edit_group_sessions_drag', 'TemplateController@editGroupSessionsDrag')->name('template_edit_group_sessions_drag');
    Route::post('template/add_new_session', 'TemplateController@storeNewSession')->name('template_add_new_session');
    Route::post('template/data_table_sessions_group', 'TemplateController@dataTableSessionsGroup')->name('template_data_table_sessions_group');
    Route::post('template/edit_group_sessions', 'TemplateController@editGroupSessions')->name('template_edit_group_sessions');
    Route::post('template/move_sessions', 'TemplateController@moveSessions')->name('template_move_sessions');
    Route::post('template/delete_session', 'TemplateController@deleteSession')->name('template_delete_session');
    Route::post('template/delete_group_sessions', 'TemplateController@deleteGroupSessions')->name('template_delete_group_sessions');
    Route::post('template/delete_template', 'TemplateController@deleteTemplate')->name('template_delete_template');
    Route::post('template/enable_disable', 'TemplateController@enableDisableTemplate')->name('template_enable_disable');
    Route::post('template/rename', 'TemplateController@renameTemplate')->name('template_rename');






     //roles y permisos
    Route::get('rol_and_permission', 'RolController@index')->name('rol_and_permission');
    Route::post('rol_and_permission/insert', 'RolController@store')->name('rol_and_permission_insert');
    Route::delete('rol_and_permission/delete/{id}', 'RolController@destroy')->name('rol_and_permission_delete');
    Route::put('rol_and_permission/update', 'RolController@update')->name('rol_and_permission_update');
    Route::post('rol_module/insert', 'RolModuleController@store')->name('rol_module_insert');

    //gestion de empleados
    Route::get('management_employee', 'EmployeeController@index')->name('management_employee');
    Route::post('management_employee/dataTable', 'EmployeeController@dataTable')->name('management_employee_data_table');
    Route::post('management_employee/insert', 'EmployeeController@store')->name('management_employee_insert');
    Route::get('management_employee/edit/{id}', 'EmployeeController@edit')->name('management_employee_edit');
    Route::put('management_employee/update/{id}', 'EmployeeController@update')->name('management_employee_update');
    Route::delete('management_employee/delete/', 'EmployeeController@destroy')->name('management_employee_delete');

    Route::get('employee_profile', 'EmployeeProfileController@index')->name('employee_profile');
    Route::put('employee_profile/update', 'EmployeeProfileController@update')->name('employee_profile_update');
    Route::put('employee_profile/change_password', 'EmployeeProfileController@changePassword')->name('employee_profile_change_password');

    //salas y grupos
    Route::get('management_room_group', 'RoomAndGroupController@index')->name('management_room_group');
    Route::post('management_room_group/room_insert', 'RoomAndGroupController@storeRoom')->name('management_room_group_room_insert');
    Route::post('management_room_group/dataTable_room', 'RoomAndGroupController@dataTableRoom')->name('management_room_group_data_table_room');
    Route::delete('management_room_group/room_delete', 'RoomAndGroupController@destroyRoom')->name('management_room_group_room_delete');
    Route::put('management_room_group/room_update', 'RoomAndGroupController@updateRoom')->name('management_room_group_room_update');
    Route::post('management_room_group/dataTable_employee_select', 'RoomAndGroupController@dataTableEmployeeSelected')->name('management_room_group_data_table_employee_selected');
    Route::post('management_room_group/dataTable_room_select', 'RoomAndGroupController@dataTableRoomSelected')->name('management_room_group_data_table_room_selected');
    Route::post('management_room_group/group_insert', 'RoomAndGroupController@storeGroup')->name('management_room_group_group_insert');
    Route::post('management_room_group/dataTable_group', 'RoomAndGroupController@dataTableGroup')->name('management_room_group_data_table_group');
    Route::put('management_room_group/group_update', 'RoomAndGroupController@updateGroup')->name('management_room_group_group_update');
    Route::delete('management_room_group/group_delete', 'RoomAndGroupController@destroyGroup')->name('management_room_group_group_delete');

    //clientes
    Route::get('management_client', 'ClientController@index')->name('management_client');
    Route::post('management_client/insert', 'ClientController@store')->name('management_client_insert');
    Route::post('management_client/dataTable', 'ClientController@dataTable')->name('management_client_data_table');
    Route::delete('management_client/delete', 'ClientController@destroy')->name('management_client_delete');
    Route::put('management_client/update', 'ClientController@update')->name('management_client_update');
    Route::post('management_client/add_document', 'ClientController@addDocument')->name('management_client_add_document');

    //productos
    Route::get('management_product', 'ProductController@index')->name('management_product');
    Route::post('management_product/insert', 'ProductController@store')->name('management_product_insert');
    Route::post('management_product/dataTable', 'ProductController@dataTable')->name('management_product_data_table');
    Route::delete('management_product/delete', 'ProductController@destroy')->name('management_product_delete');
    Route::put('management_product/update', 'ProductController@update')->name('management_product_update');

    //ventas
    Route::get('administration_sale', 'SaleController@index')->name('administration_sale');
    Route::post('administration_sale/insert', 'SaleController@store')->name('administration_sale_insert');

    //facturación
    Route::get('administration_billing', 'BillingController@index')->name('administration_billing');
    Route::post('administration_billing/dataTable', 'BillingController@dataTable')->name('administration_billing_data_table');
    Route::get('administration_billing/invoice_download/{id}', 'BillingController@downloadInvoice')->name('administration_billing_invoice_download');
    Route::get('administration_billing/invoice_print/{id}', 'BillingController@printInvoice')->name('administration_billing_invoice_print');
    Route::delete('administration_billing/delete', 'BillingController@destroy')->name('administration_billing_delete');
  
    //tickets
    Route::get('administration_billing/ticket_download/{id}', 'BillingController@downloadTicket')->name('administration_billing_ticket_download');
    Route::get('administration_billing/ticket_print/{id}', 'BillingController@printTicket')->name('administration_billing_ticket_print');

    //configuracion
    Route::get('administration_config', 'ConfigController@index')->name('administration_config');
    Route::put('administration_config/update_fiscal_data', 'ConfigController@updateFiscalData')->name('administration_config_update_fiscal_data');
    Route::put('administration_config/documentary_manager_data', 'ConfigController@updateDocumentaryManagerData')->name('administration_config_documentary_manager_data');
    Route::put('administration_config/update_paths_backups_data', 'ConfigController@updateBackupsPathData')->name('administration_config_update_paths_backups_data');
    Route::post('administration_config/dataTableNoWorkDays', 'ConfigController@dataTableNoWorkDays')->name('administration_config_data_table_no_work_days');
    Route::post('administration_config/add_no_work_day', 'ConfigController@storeNoWorkDay')->name('administration_config_add_no_work_day');
    Route::delete('administration_config/destroy_no_work_day', 'ConfigController@destroyNoWorkDay')->name('administration_config_destroy_no_work_day');
    Route::put('administration_config/edit_no_work_day', 'ConfigController@editNoWorkDay')->name('administration_config_edit_no_work_day');
    Route::post('administration_config/update_status_module_assitances', 'ConfigController@updateStatusModuleAssitances')->name('administration_config_update_status_module_assitances');
    Route::post('administration_config/check_status_hide_attr', 'ConfigController@checkStatusHideAttr')->name('administration_config_check_status_hide_attr');

    //copias de seguridad
    Route::get('administration_backup', 'BackupController@index')->name('administration_backup');
    Route::post('administration_backup/dataTable', 'BackupController@dataTable')->name('administration_backup_dataTable');
    Route::delete('administration_backup/delete', 'BackupController@destroy')->name('administration_backup_delete');
    Route::get('administration_backup/download_backup/{id}', 'BackupController@downloadBackup')->name('administration_backup_download_backup');
    Route::post('administration_backup/backup_restore_by_id', 'BackupController@restoreBackupById')->name('administration_backup_restore_by_id');
    Route::put('administration_backup/backup_rename', 'BackupController@renameBackup')->name('administration_backup_backup_rename');
    Route::post('administration_backup/create_backup_full', 'BackupController@createBackupFull')->name('administration_backup_create_backup_full');
    Route::post('administration_backup/backup_restore_by_file', 'BackupController@restoreBackupByFile')->name('administration_backup_restore_by_file');

     //gestion de ventas
    Route::get('management_sale', 'SaleController@indexManagment')->name('management_sale');
    Route::post('management_sale/dataTable', 'SaleController@dataTable')->name('management_sale_data_table');
    Route::get('management_sale/generate_invoice/{id}', 'SaleController@generateInvoiceIndex')->name('management_sale_generate_invoice');
    Route::post('management_sale/generate_invoice', 'SaleController@generateInvoice')->name('management_sale_generate_invoice_function');
    Route::delete('management_sale/delete', 'SaleController@destroy')->name('management_sale_delete');

    //historial clinico
    Route::get('medical_history', 'MedicalHistoryController@index')->name('medical_history');
    Route::post('medical_history/load_documents', 'MedicalHistoryController@getDocuments')->name('medical_history_load_documents');
    Route::post('medical_history/dataTable_documents', 'MedicalHistoryController@dataTableDocuments')->name('medical_history_data_table_documents');
    Route::delete('medical_history/delete_document', 'MedicalHistoryController@destroyDocument')->name('medical_history_delete_document');
    Route::post('medical_history/add_document', 'MedicalHistoryController@addDocument')->name('medical_history_add_document');
    Route::put('medical_history/update_document', 'MedicalHistoryController@updateDocument')->name('medical_history_update_document');
    Route::post('medical_history/compress_all', 'MedicalHistoryController@compressAll')->name('medical_history_compress_all');
    Route::post('medical_history/compress_by_clients', 'MedicalHistoryController@compressByClients')->name('medical_history_compress_by_clients');
    Route::post('medical_history/compress_by_client', 'MedicalHistoryController@compressByClient')->name('medical_history_compress_by_client');

    //horarios
    Route::get('schedule', 'ScheduleController@index')->name('schedule');
    Route::get('schedule_employee', 'ScheduleController@indexEmployee')->name('schedule_employee');
    Route::post('schedule/dataTableEmployee', 'ScheduleController@dataTableEmployee')->name('schedule_data_table_employee');
    Route::post('schedule/add_schedule_employee', 'ScheduleController@storeSchedule')->name('schedule_add_schedule_employee');
    Route::post('schedule/edit_schedule_employee', 'ScheduleController@editSchedule')->name('schedule_edit_schedule_employee');
    Route::post('schedule/get_data_schedule', 'ScheduleController@getDataSchedule')->name('schedule_get_data_schedule');
    Route::post('schedule/delete_schedule_employee', 'ScheduleController@destroySchedule')->name('schedule_delete_schedule_employee');
    Route::post('schedule/data_schedule_employee', 'ScheduleController@getScheduleEmployee')->name('schedule_data_schedule_employee');//
    Route::post('schedule/change_color_employee', 'ScheduleController@editColorEmployee')->name('schedule_change_color_employee');//
    Route::post('schedule/reset_schedule_employee', 'ScheduleController@resetSchedule')->name('schedule_reset_schedule_employee');//
    Route::post('schedule/add_holidays', 'ScheduleController@addHolidays')->name('schedule_add_holidays');//
    Route::post('schedule/get_data_holidays', 'ScheduleController@getDataHolidays')->name('schedule_get_data_holidays');//
    Route::post('schedule/delete_holidays', 'ScheduleController@destroyHolidays')->name('schedule_delete_holidays');//
    Route::post('schedule/update_status_holiday', 'ScheduleController@updateStatusHolidays')->name('schedule_update_status_holiday');//
    Route::post('schedule/dataTable_employee_select', 'ScheduleController@dataTableEmployeeSelected')->name('data_table_employee_select');//


    Route::get('report/report_cash_register', 'ReportCashRegisterController@index')->name('report_report_cash_register');
    Route::post('report/report_cash_register_get_data', 'ReportCashRegisterController@getData')->name('report_report_cash_register_get_data');//
    Route::post('report/print_cash_register', 'ReportCashRegisterController@printCashRegister')->name('report_print_cash_register');

    Route::get('notification', 'NotificationController@indexNotification')->name('notification');//
    Route::post('notification/change_status_notification', 'NotificationController@changeStatusNotification')->name('notification_change_status_notification');//
    Route::post('notification/dataTable', 'NotificationController@dataTable')->name('notification_dataTable');//
    Route::delete('notification/delete_notification', 'NotificationController@destroy')->name('notification_delete_notification');//


    Route::post('attendances/set_time_in', 'AttendancesController@setTimeIn')->name('attendances_set_time_in');//
    Route::post('attendances/set_time_out', 'AttendancesController@setTimeOut')->name('attendances_set_time_out');//

    //reportes
    Route::get('report/report_listings', 'ReportListingsController@index')->name('report_report_listings');
    Route::post('report/get_sales_data_table', 'ReportListingsController@getSalesDataTable')->name('report_get_sales_data_table');
    Route::post('report/get_products_data_table', 'ReportListingsController@getProductDataTable')->name('report_get_products_data_table');
    Route::post('report/get_employee_data_table', 'ReportListingsController@getEmployeeDataTable')->name('report_get_employee_data_table');
    Route::post('report/get_client_data_table', 'ReportListingsController@getClientDataTable')->name('report_get_client_data_table');
    Route::post('report/get_holidays_data_table', 'ReportListingsController@getHolidaysDataTable')->name('report_get_holidays_data_table');
    Route::post('report/get_attendances_data_table', 'ReportListingsController@getAttendancesDataTable')->name('report_get_attendances_data_table');
    Route::post('report/get_free_hours_data_table', 'ReportListingsController@getFreeHoursDataTable')->name('report_get_free_hours_data_table');



    //auditorias
    Route::get('audit', 'AuditController@index')->name('audit');
    Route::post('audit/downlaod', 'AuditController@download')->name('audit_download');//






});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
