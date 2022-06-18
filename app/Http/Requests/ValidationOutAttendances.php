<?php

namespace App\Http\Requests;

use App\Rules\RuleExistInForOutDate;
use App\Rules\RuleExistOutDate;
use App\Rules\RuleStatusDayWorkEmployee;
use Illuminate\Foundation\Http\FormRequest;

class ValidationOutAttendances extends FormRequest
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
            'id' =>['required', new RuleExistOutDate(), new RuleExistInForOutDate]
        ];
    }
}
