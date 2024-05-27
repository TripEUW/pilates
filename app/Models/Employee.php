<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\EmployeeResetPasswordNotification;
use Carbon\Carbon;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $table = 'employee';
    protected $guarded = ['id'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $dates = [
        'two_factor_expires_at'
    ];
    protected $fillable = [
        'two_factor_code',
        'two_factor_expires_at'
    ];
    protected $hidden = [
        'remember_token'
    ];
    public function generateTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }
    public function resetTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new EmployeeResetPasswordNotification($token));
    }


    public function roles()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id');
    }



    public function getEmployeeDataTable(Request $request)
    {
        $columns = array(
            0 => 'employee.id',
            1 => 'rol',
            2 => 'name',
            3 => 'email',
            4 => 'sex',
            5 => 'date_of_birth',
            6 => 'tel',
            7 => 'address',
            8 => 'observation',
            9 => 'status',
            10 => 'actions'
        );

        $totalData = Employee::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $employees = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'rol.name as rol',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.email',
                        'employee.sex',
                        'employee.date_of_birth',
                        'employee.tel',
                        'employee.address',
                        'employee.observation',
                        'employee.status',
                        'employee.id as actions'
                    ])->map(function ($employee) {
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->sex = $employee->sex == 'male' ? 'Masculino' : 'Femenino';
                        return $employee;
                    });
            } else {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'rol.name as rol',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.email',
                        'employee.sex',
                        'employee.date_of_birth',
                        'employee.tel',
                        'employee.address',
                        'employee.observation',
                        'employee.status',
                        'employee.id as actions'
                    ])->map(function ($employee) {
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->sex = $employee->sex == 'male' ? 'Masculino' : 'Femenino';

                        return $employee;
                    });
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->where('employee.id', 'LIKE', "%{$search}%")
                    ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                    ->orWhere('employee.name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.email', 'LIKE', "%{$search}%")
                    ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                    ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                    ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                    ->orWhere('employee.address', 'LIKE', "%{$search}%")
                    ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                    ->orWhere('employee.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'rol.name as rol',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.email',
                        'employee.sex',
                        'employee.date_of_birth',
                        'employee.tel',
                        'employee.address',
                        'employee.observation',
                        'employee.status',
                        'employee.id as actions'
                    ])->map(function ($employee) {
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->sex = $employee->sex == 'male' ? 'Masculino' : 'Femenino';
                        return $employee;
                    });
            } else {

                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->where('employee.id', 'LIKE', "%{$search}%")
                    ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                    ->orWhere('employee.name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.email', 'LIKE', "%{$search}%")
                    ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                    ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                    ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                    ->orWhere('employee.address', 'LIKE', "%{$search}%")
                    ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                    ->orWhere('employee.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'rol.name as rol',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.email',
                        'employee.sex',
                        'employee.date_of_birth',
                        'employee.tel',
                        'employee.address',
                        'employee.observation',
                        'employee.status',
                        'employee.id as actions'
                    ])->map(function ($employee) {
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->sex = $employee->sex == 'male' ? 'Masculino' : 'Femenino';
                        return $employee;
                    });
            }

            $totalFiltered = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                ->where('employee.id', 'LIKE', "%{$search}%")
                ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                ->orWhere('employee.name', 'LIKE', "%{$search}%")
                ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                ->orWhere('employee.email', 'LIKE', "%{$search}%")
                ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                ->orWhere('employee.address', 'LIKE', "%{$search}%")
                ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                ->orWhere('employee.status', 'LIKE', "%{$search}%")
                ->orWhere('rol.name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();


        $result = [
            'iTotalRecords' => $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData' => $employees
        ];

        return $result;
    }

    // public function setPasswordAttribute($pass){//Accessors and mutators
    //     $this->attributes['password'] = Hash::make($pass);
    // }
    public function setNameAttribute($name)
    { //Accessors and mutators
        $this->attributes['name'] = mb_strtolower($name);
    }
    public function setLastNameAttribute($last_name)
    { //Accessors and mutators
        $this->attributes['last_name'] = mb_strtolower($last_name);
    }
    public function setEmailAttribute($email)
    { //Accessors and mutators
        $this->attributes['email'] = mb_strtolower($email);
    }
    public function getRolAttribute($rol)
    { //Accessors and mutators
        return ucwords($rol);
    }
    public function getNameAttribute($name)
    { //Accessors and mutators
        return ucwords($name);
    }
    public function getLastNameAttribute($last_name)
    { //Accessors and mutators
        return ucwords($last_name);
    }
    public function getDateOfBirthAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }

    public function getEmployeeSelectedDataTable(Request $request)
    {


        $columns = array(
            0 => 'employee.id',
            1 => 'employee.name',
            2 => 'rol.name',
            3 => 'employee.status',
            4 => 'status_assign',
            5 => 'n_groups'
        );

        $totalData = Employee::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $employees = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'employee.name',
                        'rol.name as rol_name',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.status',
                        'employee.id as status_assign',
                        DB::raw('(SELECT COUNT(id_employee) FROM `group` WHERE `group`.`id_employee`=employee.id) as n_groups'),
                        'employee.id as actions'
                    ])->filter(function ($employee) {
                        $employee->status_assign = $employee->n_groups > 0 ? 'Asignado' : 'Sin grupo';
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->actions = ['id' => $employee->id, 'name' => $employee->name];
                        return $employee;
                    });
            } else {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'employee.name',
                        'rol.name as rol_name',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.status',
                        'employee.id as status_assign',
                        DB::raw('(SELECT COUNT(id_employee) FROM `group` WHERE `group`.`id_employee`=employee.id) as n_groups'),
                        'employee.id as actions'
                    ])->filter(function ($employee) {
                        $employee->status_assign = $employee->n_groups > 0 ? 'Asignado' : 'Sin grupo';
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->actions = ['id' => $employee->id, 'name' => $employee->name];
                        return $employee;
                    });
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->where('employee.id', 'LIKE', "%{$search}%")
                    ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                    ->orWhere('employee.name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.email', 'LIKE', "%{$search}%")
                    ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                    ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                    ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                    ->orWhere('employee.address', 'LIKE', "%{$search}%")
                    ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                    ->orWhere('employee.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'employee.name',
                        'rol.name as rol_name',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.status',
                        'employee.id as status_assign',
                        DB::raw('(SELECT COUNT(id_employee) FROM `group` WHERE `group`.`id_employee`=employee.id) as n_groups'),
                        'employee.id as actions'
                    ])->filter(function ($employee) {
                        $employee->status_assign = $employee->n_groups > 0 ? 'Asignado' : 'Sin grupo';
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->actions = ['id' => $employee->id, 'name' => $employee->name];
                        return $employee;
                    });
            } else {

                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->where('employee.id', 'LIKE', "%{$search}%")
                    ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                    ->orWhere('employee.name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.email', 'LIKE', "%{$search}%")
                    ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                    ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                    ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                    ->orWhere('employee.address', 'LIKE', "%{$search}%")
                    ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                    ->orWhere('employee.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'employee.name',
                        'rol.name as rol_name',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.status',
                        'employee.id as status_assign',
                        DB::raw('(SELECT COUNT(id_employee) FROM `group` WHERE `group`.`id_employee`=employee.id) as n_groups'),
                        'employee.id as actions'
                    ])->filter(function ($employee) {
                        $employee->status_assign = $employee->n_groups > 0 ? 'Asignado' : 'Sin grupo';
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->actions = ['id' => $employee->id, 'name' => $employee->name];
                        return $employee;
                    });
            }

            $totalFiltered = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                ->where('employee.id', 'LIKE', "%{$search}%")
                ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                ->orWhere('employee.name', 'LIKE', "%{$search}%")
                ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                ->orWhere('employee.email', 'LIKE', "%{$search}%")
                ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                ->orWhere('employee.address', 'LIKE', "%{$search}%")
                ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                ->orWhere('employee.status', 'LIKE', "%{$search}%")
                ->orWhere('rol.name', 'LIKE', "%{$search}%")
                ->count();
        }




        $result = [
            'iTotalRecords' => $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData' => $employees
        ];

        return $result;
    }

    public function getEmployeeSelectedDataTableDashboard(Request $request)
    {

        $columns = array(
            0 => 'employee.id',
            1 => 'employee.name',
            2 => 'rol.name',
            3 => 'employee.status',
            4 => 'status_assign',
            5 => 'n_groups'
        );

        $totalData = Employee::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $employees = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'employee.name',
                        'rol.name as rol_name',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.status',
                        'employee.id as status_assign',
                        DB::raw('(SELECT COUNT(id_employee) FROM `group` WHERE `group`.`id_employee`=employee.id) as n_groups'),
                        'employee.id as actions'
                    ])->filter(function ($employee) {
                        $employee->status_assign = $employee->n_groups > 0 ? 'Asignado' : 'Sin grupo';
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->actions = ['id' => $employee->id, 'name' => $employee->name];
                        return $employee;
                    });
            } else {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'employee.name',
                        'rol.name as rol_name',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.status',
                        'employee.id as status_assign',
                        DB::raw('(SELECT COUNT(id_employee) FROM `group` WHERE `group`.`id_employee`=employee.id) as n_groups'),
                        'employee.id as actions'
                    ])->filter(function ($employee) {
                        $employee->status_assign = $employee->n_groups > 0 ? 'Asignado' : 'Sin grupo';
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->actions = ['id' => $employee->id, 'name' => $employee->name];
                        return $employee;
                    });
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->where('employee.id', 'LIKE', "%{$search}%")
                    ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                    ->orWhere('employee.name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.email', 'LIKE', "%{$search}%")
                    ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                    ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                    ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                    ->orWhere('employee.address', 'LIKE', "%{$search}%")
                    ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                    ->orWhere('employee.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'employee.name',
                        'rol.name as rol_name',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.status',
                        'employee.id as status_assign',
                        DB::raw('(SELECT COUNT(id_employee) FROM `group` WHERE `group`.`id_employee`=employee.id) as n_groups'),
                        'employee.id as actions'
                    ])->filter(function ($employee) {
                        $employee->status_assign = $employee->n_groups > 0 ? 'Asignado' : 'Sin grupo';
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->actions = ['id' => $employee->id, 'name' => $employee->name];
                        return $employee;
                    });
            } else {

                $employees = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                    ->where('employee.id', 'LIKE', "%{$search}%")
                    ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                    ->orWhere('employee.name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('employee.email', 'LIKE', "%{$search}%")
                    ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                    ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                    ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                    ->orWhere('employee.address', 'LIKE', "%{$search}%")
                    ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                    ->orWhere('employee.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'employee.id',
                        'employee.name',
                        'rol.name as rol_name',
                        DB::raw('CONCAT(employee.name," ",employee.last_name) as name'),
                        'employee.status',
                        'employee.id as status_assign',
                        DB::raw('(SELECT COUNT(id_employee) FROM `group` WHERE `group`.`id_employee`=employee.id) as n_groups'),
                        'employee.id as actions'
                    ])->filter(function ($employee) {
                        $employee->status_assign = $employee->n_groups > 0 ? 'Asignado' : 'Sin grupo';
                        $employee->status = $employee->status == 'enable' ? 'Activo' : 'Inactivo';
                        $employee->actions = ['id' => $employee->id, 'name' => $employee->name];
                        return $employee;
                    });
            }

            $totalFiltered = Employee::join('rol', 'employee.id_rol', '=', 'rol.id')
                ->where('employee.id', 'LIKE', "%{$search}%")
                ->orWhere('employee.id_rol', 'LIKE', "%{$search}%")
                ->orWhere('employee.name', 'LIKE', "%{$search}%")
                ->orWhere('employee.last_name', 'LIKE', "%{$search}%")
                ->orWhere('employee.email', 'LIKE', "%{$search}%")
                ->orWhere('employee.sex', 'LIKE', "%{$search}%")
                ->orWhere('employee.date_of_birth', 'LIKE', "%{$search}%")
                ->orWhere('employee.tel', 'LIKE', "%{$search}%")
                ->orWhere('employee.address', 'LIKE', "%{$search}%")
                ->orWhere('employee.observation', 'LIKE', "%{$search}%")
                ->orWhere('employee.status', 'LIKE', "%{$search}%")
                ->orWhere('rol.name', 'LIKE', "%{$search}%")
                ->count();
        }




        $result = [
            'iTotalRecords' => $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData' => $employees
        ];

        return $result;
    }


    public function getEmployeeScheduleDataTable(Request $request)
    {
        $columns = array(
            0 => 'name',
        );

        $totalData = Employee::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $employees = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $employees = Employee::get([
                    '*'
                ])->map(function ($client) use ($request) {
                    return $this->analizeMapEmployeeSheduleDataTable($client, $request);
                })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $employees = Employee::get([
                    '*'
                ])->map(function ($client) use ($request) {
                    return $this->analizeMapEmployeeSheduleDataTable($client, $request);
                })

                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $employees = Employee::get([
                    '*'
                ])->map(function ($client) use ($request) {
                    return $this->analizeMapEmployeeSheduleDataTable($client, $request);
                })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->filterSearchEmployeeDataTable($client, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $employees = Employee::get([
                    '*'
                ])->map(function ($client) use ($request) {
                    return $this->analizeMapEmployeeSheduleDataTable($client, $request);
                })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->filterSearchEmployeeDataTable($client, $search, $columns, $request);
                    })

                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered =
                Employee::get([
                    '*'
                ])->map(function ($client) use ($request) {
                    return $this->analizeMapEmployeeSheduleDataTable($client, $request);
                })
                    ->filter(function ($client) use ($search, $columns, $request) {
                        return $this->filterSearchEmployeeDataTable($client, $search, $columns, $request);
                    })
                    ->count();
        }



        $result = [
            'iTotalRecords' => $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData' => $employees
        ];

        return $result;
    }

    function getEmployeeScheduleSelectedDataTable(Request $request)
    {
        $columns = array(
            0 => 'name',
        );

        $totalData = 0;
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $employees = [];
        if (
            !empty($request->input('employee_selected'))
            &&
            !empty($request->input('date_start'))
            &&
            !empty($request->input('date_end'))

        ) {

            $totalData = count(Employee::where('id', "!=", $request->employee_selected)->get([
                '*'
            ])->filter(function ($client) use ($request) {
                return $this->analizeMapEmployeeSheduleDataTableSelected($client, $request);
            })->values()->all());
            $totalFiltered = $totalData;

            if (empty($request->input('search.value'))) {

                if ($limit == -1) {
                    $employees = Employee::where('id', "!=", $request->employee_selected)->get([
                        '*'
                    ])->filter(function ($client) use ($request) {
                        return $this->analizeMapEmployeeSheduleDataTableSelected($client, $request);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {
                    $employees = Employee::where('id', "!=", $request->employee_selected)->get([
                        '*'
                    ])->filter(function ($client) use ($request) {
                        return $this->analizeMapEmployeeSheduleDataTableSelected($client, $request);
                    })

                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                        ->skip($start)->take($limit)
                        ->values()->all();
                }
            } else {
                $search = $request->input('search.value');
                if ($limit == -1) {
                    $employees = Employee::where('id', "!=", $request->employee_selected)->get([
                        '*'
                    ])->filter(function ($client) use ($request) {
                        return $this->analizeMapEmployeeSheduleDataTableSelected($client, $request);
                    })
                        ->filter(function ($client) use ($search, $columns, $request) {
                            return $this->filterSearchEmployeeDataTable($client, $search, $columns, $request);
                        })
                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
                } else {

                    $employees = Employee::where('id', "!=", $request->employee_selected)->get([
                        '*'
                    ])->filter(function ($client) use ($request) {
                        return $this->analizeMapEmployeeSheduleDataTableSelected($client, $request);
                    })
                        ->filter(function ($client) use ($search, $columns, $request) {
                            return $this->filterSearchEmployeeDataTable($client, $search, $columns, $request);
                        })

                        ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                        ->skip($start)->take($limit)
                        ->values()->all();
                }

                $totalFiltered =
                    Employee::where('id', "!=", $request->employee_selected)->get([
                        '*'
                    ])->filter(function ($client) use ($request) {
                        return $this->analizeMapEmployeeSheduleDataTableSelected($client, $request);
                    })
                        ->filter(function ($client) use ($search, $columns, $request) {
                            return $this->filterSearchEmployeeDataTable($client, $search, $columns, $request);
                        })
                        ->count();
            }

        }



        $result = [
            'iTotalRecords' => $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData' => $employees
        ];

        return $result;
    }


    function analizeMapEmployeeSheduleDataTable($employee, $request)
    {
        $employee->name = "$employee->name $employee->last_name";
        $employee['count_schedule'] = count(Schedule::where('id_employee', $employee->id)->where('date_start', '>=', $request->date_start)->where('date_end', '<=', $request->date_end)->get());
        $employee["employee"] = json_decode($employee);

        return $employee;
    }

    function analizeMapEmployeeSheduleDataTableSelected($employee, $request)
    {
        $count = count(Schedule::where('id_employee', $employee->id)->where('date_start', '>=', $request->date_start)->where('date_end', '<=', $request->date_end)->get());
        if ($count <= 0) {
            $employee->name = "$employee->name $employee->last_name";
            $employee['count_schedule'] = $count;
            $employee["employee"] = json_decode($employee);
            $employee["actions"] = json_decode($employee);
            return $employee;
        } else {
            return false;
        }

    }

    function filterSearchEmployeeDataTable($employee, $search, $columns, $request)
    {
        $item = false;
        //general
        foreach ($columns as $colum)
            if (stristr($employee[$colum], $search))
                $item = $employee;
        return $item;
    }

}
