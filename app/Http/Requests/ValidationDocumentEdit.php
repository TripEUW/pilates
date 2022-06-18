<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationDocumentEdit extends FormRequest
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

    public function rules()
    {
     // store
            return [
                'name_document' => 'required|max:100|required_with:front|required_with:back',
                'front' => 'nullable|max:10240',
                'back' => 'nullable|max:10240',
                'observation' => 'nullable|max:5000'
            ];
          
    }

        
    public function messages()
    {
        return [
            'name_document.required_with' => 'El nombre o título del documento es necesario cuando el anverso o reverso está presente.'
        ];
    }

   public function attributes(){

       return [
           'name_document' => 'nombre o título',
           'observation' => 'observaciones',
           'name_document' => 'nombre o título del documento'
       ];
   
      }
}
