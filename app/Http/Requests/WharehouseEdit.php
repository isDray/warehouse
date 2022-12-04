<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class WharehouseEdit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:30',
            'code' => 'required|max:30',
            'note' => 'required'
        ];
    }

    public function messages(){
        
        return [
            'name.required' => '倉庫名稱為必填',
            'name.max' => '倉庫名稱最多 :max 個字',
            'code.required' => '倉庫編碼為必填',
            'code.max' => '倉庫名稱最多 :max 個字',
            'note.required' => '倉庫備註為必填',
        ];
    }
}
