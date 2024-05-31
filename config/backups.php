<?php
return [
    'path_for_mysql' => env('PATH_FOR_MYSQL_EXE', "mysql"),
    'path_for_mysqldump' => env('PATH_FOR_MYSQLDUMP_EXE', 'mysqldump'),
    'default_path_gestor' =>  env('DEFAULT_PATH_GESTOR', 'datos/gdocs'),
    'default_path_backups_day' =>  env('DEFAULT_PATH_BACKUPS_DAY', 'backups/diario'),
    'path_backups_week' =>  env('PATH_BACKUPS_WEEK', 'backups/semanal') 
];