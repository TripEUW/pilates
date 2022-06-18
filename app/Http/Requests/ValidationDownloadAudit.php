<?php

namespace App\Http\Requests;

use App\Rules\RuleExistAudit;
use Illuminate\Foundation\Http\FormRequest;

class ValidationDownloadAudit extends FormRequest
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
            'date' => ['required','date_format:Y-m-d', new RuleExistAudit()],
        ];
    }

    
   public function attributes(){

    return [
        'date' => 'fecha de auditoria',
    ];

   }
}
