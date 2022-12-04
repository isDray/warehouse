<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ShelfEdit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'note' => 'required',
            'wharehouse'=>'required|not_in:0',
            "floor.*"  => "required|integer|gt:0",
        ];
    }

    public function messages(){
        
        return [

            'name.required' => '貨架名稱為必填',
            'name.max' => '貨架名稱最多 :max 個字',
            'code.required' => '貨架編碼為必填',
            'code.max' => '貨架名稱最多 :max 個字',
            'note.required' => '貨架備註為必填',
            'wharehouse.not_in'=>'所屬倉庫不可為空',
            "floor.*.required"  =>":attribute 為必填" ,
            "floor.*.integer"  =>":attribute 只可為數字" ,
            "floor.*.gt"  =>":attribute 數值必須大於0" ,

        ];
    }  

    public function attributes(){
        
        $returnArr = [];
        for ($i=0; $i < 20; $i++) { 
            $tmpnum = $i + 1;
            $returnArr["floor.".$i] = "層數$tmpnum";
        }
        return $returnArr;
        /*
        [
            'floor.0' => '層數 1',
            'floor.1' => '層數 2',
            'floor.2' => '層數 3',

        ];*/
    }      
    
    // 記憶使用者操作用
    public function saveInput( $inputs ){
        
        foreach ($inputs as $inputk => $inputv) {
            
            if( $inputk != '_token'){
                
                var_dump($inputv);
                echo "<br>";
       
                /*$saveArr = [];

                $saveArr[$inputk] = $input;
                session()->put('saveInput',$saveArr);*/
            }
        }

    }
}
