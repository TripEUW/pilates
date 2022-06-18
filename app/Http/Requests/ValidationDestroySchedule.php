<?php

namespace App\Http\Requests;

use App\Rules\RuleCheckInWorkingEmployee;
use Illuminate\Foundation\Http\FormRequest;

class ValidationDestroySchedule extends FormRequest
{
     /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_employee' => [new RuleCheckInWorkingEmployee($this->input('id_employee'))]
        ];
    }
}
