<?php

namespace App\Console\Commands;

use App\Helpers\Pilates;
use App\Models\Backup;
use App\Models\Configuration;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupDataBaseDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:backup_daily {force_backup?}';
    protected $description = 'Create backup Database Daily';
    protected $process;

    protected $forceBackupAttr;

    public function __construct($force = false)
    {
        parent::__construct();
        $this->forceBackupAttr = $force;
    }


    public function handle()
    {
        $admins = Employee::where('id_rol', 1)->get();
        $forceBackup = $this->forceBackupAttr ?? false;
        $excludeDays = ['6']; // 1 = Monday, 2 = Tuesday, 3 = Wednesday, 4 = Thursday, 5 = Friday, 6 = Saturday, 0 = Sunday.
        $dayCutting = '0'; // 1 = Monday, 2 = Tuesday, 3 = Wednesday, 4 = Thursday, 5 = Friday, 6 = Saturday, 0 = Sunday.
        $dayToPreserve = '6'; // 0 = Monday, 1 = Tuesday, 2 = Wednesday, 3 = Thursday, 4 = Friday, 5 = Saturday, 6 = Sunday.

        $backupDB = null;

        if (!in_array(date('w'), $excludeDays) || $forceBackup != false) {

            try {
                if (date('w') == $dayCutting && $forceBackup == false) {
                    $backups = DB::select("SELECT * FROM backup WHERE WEEKDAY(backup.date_create)!='$dayToPreserve'");


                    foreach ($backups as $key => $backup) {
                        if (Storage::disk('dropbox')->has("$backup->path_dropbox/$backup->file_name")) {
                            Storage::disk('dropbox')->delete("$backup->path_dropbox/$backup->file_name");
                        }
                        if (Storage::disk('public')->has("$backup->path_public/$backup->file_name")) {
                            Storage::disk('public')->delete("$backup->path_public/$backup->file_name");
                        }
                        Backup::where('id', $backup->id)->delete();
                    }
                    Log::info('Copies of the week have been deleted');
                }


                $today = date('m-d-Y_his_a');

                $config = Configuration::first();
                $pathForSaveBackup = (isset($config->path_backups_day)) ? $config->path_backups_day : config('backups.default_path_backups_day');
                $pathForSaveBackupFiles = (isset($config->path_gestor)) ? $config->path_gestor : config('backups.default_path_gestor');
                $pathForSaveBackup = "{$pathForSaveBackup}";
                $tmpPath = "{$pathForSaveBackup}";
                $nameFileBackup = "{$today}.sql";

                if (!Storage::disk('public')->has($pathForSaveBackup))
                    Storage::disk('public')->makeDirectory($pathForSaveBackup);

                $pathForSaveBackup = Storage::disk('public')->path($pathForSaveBackup);
                $username = config('database.connections.mysql.username');
                $password = config('database.connections.mysql.password');
                $host = config('database.connections.mysql.host');
                $port = config('database.connections.mysql.port');
                $database = config('database.connections.mysql.database');
                $pathForMysqldump = config('backups.path_for_mysqldump');

                $passwordPart = ((empty(config('database.connections.mysql.password')) ? "" : "-p{$password}"));
                $this->process = new Process([
                    "bash",
                    "-c",
                    "mysqldump -h {$host} -u {$username} {$passwordPart} --port={$port} --ignore-table={$database}.backup {$database} > {$pathForSaveBackup}/{$nameFileBackup}"
                ]);
                $backupDB = Backup::create([
                    'date_create' => now(),
                    'file_name' => $nameFileBackup,
                    'description' => 'Backup: ' . Pilates::getNowDayName(),
                    'status' => '3',
                    'path_public' => $tmpPath,
                    'path_dropbox' => $tmpPath,
                ]); //creando
                $this->process->mustRun();

                if (Storage::disk('public')->has("$tmpPath/$nameFileBackup")) {

                    $fileBackup = Storage::disk('public')->get("$tmpPath/$nameFileBackup");
                    $fileSize = Storage::disk('public')->size("$tmpPath/$nameFileBackup");
                    // Storage::disk('dropbox')->put("$tmpPath/$nameFileBackup", $fileBackup);

                    // if (!Storage::disk('dropbox')->has("$tmpPath/$nameFileBackup")) {
                    //     $backupDB->update(['status' => '2']); //error create
                    //     Log::error('Daily DB Backup - The base copy was created successfully but had a problem uploading the backup to Dropbox.');
                    //     return ['status' => '2', 'response' => "La copia local fue creada con éxito pero ocurrió un problema al subir la copia de seguridad a Dropbox."];
                    // }


                    $files = Storage::disk('public')->files($pathForSaveBackupFiles);
                    foreach ($files as $key => $file) {
                        $nameFile = basename($file);
                        if (!Storage::disk('dropbox')->has("$file")) {
                            $fileDoc = Storage::disk('public')->get($file);
                            // Storage::disk('dropbox')->put("$pathForSaveBackupFiles/$nameFile", $fileDoc);
                            //file no exist in drop box, but upload now
                        } else if (Storage::disk('dropbox')->has("$file") && Storage::disk('public')->has("$file")) {
                            // $sizeDropbox = Storage::disk('dropbox')->size("$file");
                            $sizePublic = Storage::disk('public')->size("$file");
                            // if ($sizeDropbox != $sizePublic) {
                                // $fileDoc = Storage::disk('public')->get($file);
                                // Storage::disk('dropbox')->put("$pathForSaveBackupFiles/$nameFile", $fileDoc);
                            // }
                        }
                    }




                    //creado con éxito
                    Log::info('Daily DB Backup - Success');
                    return ['status' => '3', 'response' => "Copia de seguridad creada con éxito."];
                } else {
                    $backupDB->update(['status' => '4']); //error create

                    foreach ($admins as $admin) {
                        //start notification
                        $title_n = 'Copia de seguridad incompleta.';
                        $msg_n = 'Ocurrió un problema al crear la copia de seguridad principal.';
                        $path_n = 'administration_backup';
                        $cod_sender = '';
                        $cod_receiver = $admin->id;
                        $type_sender = 0;
                        $type_receiver = $admin->id_rol;
                        $type_notification = 'modal_redirect'; //redirect,message,modal_redirect
                        $use_lang_title = 'false';
                        $use_lang_msg = 'false';
                        $paramsTitleNotifi = [];
                        $paramsMsgNotifi = [];
                        $sendMail = false;
                        $icon = 'flaticon-upload-1 icon-font-yellow'; //name-image.jpg or html code
                        $type_icon = 'html-class'; //html-class, image-public
                        Pilates::sendNotification($title_n, $msg_n, $path_n, $cod_sender, $cod_receiver, $type_sender, $type_receiver, $type_notification, $use_lang_title, $use_lang_msg, $paramsTitleNotifi, $paramsMsgNotifi, $sendMail, $icon, $type_icon);
                        //end notification
                    }

                    Log::error('Daily DB Backup - Failed');
                    return ['status' => '4', 'response' => "Ocurrió un problema al crear la copia de seguridad principal."];
                }
            } catch (ProcessFailedException $exc) {
                $backupDB->update(['status' => '4']); //error

                foreach ($admins as $admin) {
                    //start notification
                    $title_n = 'La copia de seguridad no se completo.';
                    $msg_n = 'Ocurrió un error al crear la copia, la configuración del servidor no es correcta o el servicio no esta disponible.';
                    $path_n = 'administration_backup';
                    $cod_sender = '';
                    $cod_receiver = $admin->id;
                    $type_sender = 0;
                    $type_receiver = $admin->id_rol;
                    $type_notification = 'modal_redirect'; //redirect,message,modal_redirect
                    $use_lang_title = 'false';
                    $use_lang_msg = 'false';
                    $paramsTitleNotifi = [];
                    $paramsMsgNotifi = [];
                    $sendMail = false;
                    $icon = 'flaticon-upload-1 icon-font-red'; //name-image.jpg or html code
                    $type_icon = 'html-class'; //html-class, image-public
                    Pilates::sendNotification($title_n, $msg_n, $path_n, $cod_sender, $cod_receiver, $type_sender, $type_receiver, $type_notification, $use_lang_title, $use_lang_msg, $paramsTitleNotifi, $paramsMsgNotifi, $sendMail, $icon, $type_icon);
                    //end notification
                }
                Log::error('Daily DB Backup - Failed: ' . $exc);
                return ['status' => '4', 'response' => "Ocurrió un error al crear la copia, la configuración del servidor no es correcta o el servicio no esta disponible."];
            }
        } else {
            Log::info('No backup copies are made on this day');
            return ['status' => '4', 'response' => "No se realizan copias de seguridad en este día."];
        }
    }
}
