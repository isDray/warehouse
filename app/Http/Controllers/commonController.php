<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class commonController extends Controller
{
    
    public function getSuccessMsg(Request $request){
     
    	if( $request->session()->has('successMsg') ){

          $tmp = $request->session()->get('successMsg');
           
          $request->session()->forget('successMsg');
          $request->session()->save();

          echo json_encode($tmp);

    	}else{

          echo json_encode(False);
        }
    }

    public function getErrMsg( Request $request ){
    	
    	if( $request->session()->has('errMsg') ){
          
          $tmp = $request->session()->get('errMsg');
          
          $request->session()->forget('errMsg');
          $request->session()->save();


          echo json_encode($tmp);

    	}else{

            echo json_encode(False);
        }    	

    }

    public function deleteSuccess(){

           session()->forget('successMsg');
           session()->save();

           Session::forget('successMsg');
           Session::save();


    }

    public function deleteErr(){
           session()->forget('errMsg');
           session()->save();

           Session::forget('errMsg');
           Session::save();
    }

}
