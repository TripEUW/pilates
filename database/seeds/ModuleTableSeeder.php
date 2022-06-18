<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            //dashboard
            [
                'title' => 'panel de control',
                'url' => 'dashboard',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar grupos de sesiones',
                'url' => 'dashboard_add_group_session',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar sesiones',
                'url' => 'dashboard_add_new_session',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar sesiones',
                'url' => 'dashboard_delete_session',
                'rols' => [1,2]
            ],
            [
                'title' => 'editar grupos de sesiones',
                'url' => 'dashboard_edit_group_sessions',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar grupos de sesiones',
                'url' => 'dashboard_delete_group_sessions',
                'rols' => [1,2]
            ],
            [
                'title' => 'mover grupos de sesiones arrastrando',
                'url' => 'dashboard_edit_group_sessions_drag',
                'rols' => [1,2]
            ],
            [
                'title' => 'crear nuevo grupo desde planificador',
                'url' => 'dashboard_create_group',
                'rols' => [1,2]
            ],
            [
                'title' => 'crear nuevo empleado desde planificador',
                'url' => 'dashboard_create_employee',
                'rols' => [1,2]
            ],
            [
                'title' => 'mover sesiones clic izquierdo',
                'url' => 'dashboard_move_sessions',
                'rols' => [1,2]
            ],
            [
                'title' => 'mover sesiones editar',
                'url' => 'dashboard_move_sessions2',
                'rols' => [1,2]
            ],
            [
                'title' => 'bloqueo para agregar sesiones',
                'url' => 'dashboard_lock_group_session_add',
                'rols' => [1,2]
            ],
            [
                'title' => 'cargar plantillas sobre planificador',
                'url' => 'dashboard_load_template',
                'rols' => [1,2]
            ],
            //template
            [
                'title' => 'plantillas',
                'url' => 'template',
                'rols' => [1]
            ],
            [
                'title' => 'crear nuevas plantillas',
                'url' => 'template_create_template',
                'rols' => [1]
            ],
            [
                'title' => 'activar/desactivar plantillas',
                'url' => 'template_enable_disable',
                'rols' => [1]
            ],
            [
                'title' => 'renombrar plantillas',
                'url' => 'template_rename',
                'rols' => [1]
            ],
            [
                'title' => 'eliminar plantillas',
                'url' => 'template_delete_template',
                'rols' => [1]
            ],
            //roles y permisos
            [
                'title' => 'ver roles y permisos',
                'url' => 'rol_and_permission',
                'rols' => [1]
            ],
            [
                'title' => 'crear roles',
                'url' => 'rol_and_permission_insert',
                'rols' => [1]
            ],
            [
                'title' => 'eliminar roles',
                'url' => 'rol_and_permission_delete',
                'rols' => [1]
            ],
            [
                'title' => 'editar roles',
                'url' => 'rol_and_permission_update',
                'rols' => [1]
            ],
            [
                'title' => 'asignar y quitar permisos',
                'url' => 'rol_module_insert',
                'rols' => [1]
            ],
            //gestion de empleados
            [
                'title' => 'gestión de empleados',
                'url' => 'management_employee',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar empleados',
                'url' => 'management_employee_insert',
                'rols' => [1,2]
            ],
            [
                'title' => 'actualizar empleados',
                'url' => 'management_employee_update',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar empleados',
                'url' => 'management_employee_delete',
                'rols' => [1,2]
            ],
            //salas y grupos
            [
                'title' => 'gestión de salas y grupos',
                'url' => 'management_room_group',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar salas',
                'url' => 'management_room_group_room_insert',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar salas',
                'url' => 'management_room_group_room_delete',
                'rols' => [1,2]
            ],
            [
                'title' => 'actualizar salas',
                'url' => 'management_room_group_room_update',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar grupos',
                'url' => 'management_room_group_group_insert',
                'rols' => [1,2]
            ],
            [
                'title' => 'actualizar grupos',
                'url' => 'management_room_group_group_update',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar grupos',
                'url' => 'management_room_group_group_delete',
                'rols' => [1,2]
            ],
            //clientes
            [
                'title' => 'gestión de clientes',
                'url' => 'management_client',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar clientes',
                'url' => 'management_client_insert',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar clientes',
                'url' => 'management_client_delete',
                'rols' => [1,2]
            ],
            [
                'title' => 'actualizar clientes',
                'url' => 'management_client_update',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar documento (gestión de clientes)',
                'url' => 'management_client_add_document',
                'rols' => [1,2]
            ],
            //productos
            [
                'title' => 'gestión de productos',
                'url' => 'management_product',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar productos',
                'url' => 'management_product_insert',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar productos',
                'url' => 'management_product_delete',
                'rols' => [1,2]
            ],
            [
                'title' => 'actualizar productos',
                'url' => 'management_product_update',
                'rols' => [1,2]
            ],
            //ventas
            [
                'title' => 'crear ventas',
                'url' => 'administration_sale',
                'rols' => [1,2]
            ],
            //facturación
            [
                'title' => 'facturación',
                'url' => 'administration_billing',
                'rols' => [1,2]
            ],
            [
                'title' => 'descargar/generar facturas',
                'url' => 'administration_billing_invoice_download',
                'rols' => [1,2]
            ],
            [
                'title' => 'imprimir/generar facturas',
                'url' => 'administration_billing_invoice_print',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar facturas',
                'url' => 'administration_billing_delete',
                'rols' => [1]
            ],
            //tickets
            [
                'title' => 'descargar tickets',
                'url' => 'administration_billing_ticket_download',
                'rols' => [1,2]
            ],
            [
                'title' => 'imprimir tickets',
                'url' => 'administration_billing_ticket_print',
                'rols' => [1,2]
            ],
            //configuracion
            [
                'title' => 'configuración',
                'url' => 'administration_config',
                'rols' => [1]
            ],
            [
                'title' => 'actualizar datos fiscales',
                'url' => 'administration_config_update_fiscal_data',
                'rols' => [1]
            ],
            [
                'title' => 'actualizar ruta de gestor documental',
                'url' => 'administration_config_documentary_manager_data',
                'rols' => [1]
            ],
            [
                'title' => 'actualizar rutas de backups DB',
                'url' => 'administration_config_update_paths_backups_data',
                'rols' => [1]
            ],
            [
                'title' => 'agregar días festivos o no laborales',
                'url' => 'administration_config_add_no_work_day',
                'rols' => [1]
            ],
            [
                'title' => 'eliminar días festivos o no laborales',
                'url' => 'administration_config_destroy_no_work_day',
                'rols' => [1]
            ],
            [
                'title' => 'editar días festivos o no laborales',
                'url' => 'administration_config_edit_no_work_day',
                'rols' => [1]
            ],
            [
                'title' => 'ver o editar campos protegidos',
                'url' => 'administration_config_check_status_hide_attr',
                'rols' => [1]
            ],
            //gestion de ventas
            [
                'title' => 'gestión de ventas',
                'url' => 'management_sale',
                'rols' => [1]
            ],
            [
                'title' => 'generar/descargar/imprimir facturas',
                'url' => 'management_sale_generate_invoice_function',
                'rols' => [1]
            ],
            [
                'title' => 'eliminar ventas',
                'url' => 'management_sale_delete',
                'rols' => [1]
            ],
            //historial clinico
            [
                'title' => 'historial clínico',
                'url' => 'medical_history',
                'rols' => [1,2]
            ],
            [
                'title' => 'agregar documentos',
                'url' => 'medical_history_add_document',
                'rols' => [1,2]
            ],
            [
                'title' => 'eliminar documentos',
                'url' => 'medical_history_delete_document',
                'rols' => [1,2]
            ],
            [
                'title' => 'actualizar documentos',
                'url' => 'medical_history_update_document',
                'rols' => [1,2]
            ],
            [
                'title' => 'descargar zip de todo',
                'url' => 'medical_history_compress_all',
                'rols' => [1,2]
            ],
            [
                'title' => 'descargar zip por clientes',
                'url' => 'medical_history_compress_by_clients',
                'rols' => [1,2]
            ],
            [
                'title' => 'descargar zip por cliente',
                'url' => 'medical_history_compress_by_client',
                'rols' => [1,2]
            ],
            //copias de seguridad
            [
                'title' => 'Copias de seguridad',
                'url' => 'administration_backup',
                'rols' => [1]
            ],
            [
                'title' => 'Eliminar copias de seguridad',
                'url' => 'administration_backup_delete',
                'rols' => [1]
            ],
            [
                'title' => 'Descargar copias de seguridad',
                'url' => 'administration_backup_download_backup',
                'rols' => [1]
            ],
            [
                'title' => 'Restaurar copias de seguridad actuales',
                'url' => 'administration_backup_restore_by_id',
                'rols' => [1]
            ],
            [
                'title' => 'Renombrar copias de seguridad actuales',
                'url' => 'administration_backup_backup_rename',
                'rols' => [1]
            ],
            [
                'title' => 'Crear copia de seguridad full',
                'url' => 'administration_backup_create_backup_full',
                'rols' => [1]
            ],
            [
                'title' => 'Restaurar copia de seguridad desde archivo',
                'url' => 'administration_backup_restore_by_file',
                'rols' => [1]
            ],
            //horarios
            [
                'title' => 'horarios',
                'url' => 'schedule',
                'rols' => [1]
            ],
            [
                'title' => 'agregar o establecer horarios',
                'url' => 'schedule_add_schedule_employee',
                'rols' => [1]
            ],
            [
                'title' => 'editar horarios',
                'url' => 'schedule_edit_schedule_employee',
                'rols' => [1]
            ],
            [
                'title' => 'eliminar horarios',
                'url' => 'schedule_delete_schedule_employee',
                'rols' => [1]
            ],
            [
                'title' => 'cambiar color horarios',
                'url' => 'schedule_change_color_employee',
                'rols' => [1]
            ],
            [
                'title' => 'reiniciar horarios',
                'url' => 'schedule_reset_schedule_employee',
                'rols' => [1]
            ],
            //arqueo
            [
                'title' => 'arqueo diario',
                'url' => 'report_report_cash_register',
                'rols' => [1,2]
            ],
            [
                'title' => 'imprimir arqueo diario',
                'url' => 'report_print_cash_register',
                'rols' => [1,2]
            ],
            //informes
            [
                'title' => 'listado de informes',
                'url' => 'report_report_listings',
                'rols' => [1]
            ],
            [
                'title' => 'informes de ventas',
                'url' => 'report_get_sales_data_table',
                'rols' => [1]
            ],
            [
                'title' => 'informes de productos',
                'url' => 'report_get_products_data_table',
                'rols' => [1]
            ],
            [
                'title' => 'informes de empleados',
                'url' => 'report_get_employee_data_table',
                'rols' => [1]
            ],
            [
                'title' => 'informes de clientes',
                'url' => 'report_get_client_data_table',
                'rols' => [1]
            ],
            [
                'title' => 'informes de vacaciones',
                'url' => 'report_get_holidays_data_table',
                'rols' => [1]
            ],
            [
                'title' => 'informes de asistencias',
                'url' => 'report_get_attendances_data_table',
                'rols' => [1]
            ],
            //auditorias
            [
                'title' => 'auditorias',
                'url' => 'audit',
                'rols' => [1]
            ]
        ];

        // $this->call(UsersTableSeeder::class);
        foreach ($modules as $key => $module) {

            DB::table('module')->insert([
                'id' => $key+1,
                'title' => $module['title'],
                'url' => $module['url'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s') //change to spain
            ]);

            foreach ($module['rols'] as $rol) {
                DB::table('rol_module')->insert([
                    'id_rol' =>  $rol,
                    'id_module' => $key+1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s') //change to spain
                ]);
            }

        }
    }
}
