<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
// Model
use App\Wharehouse;
// form request
use App\Http\Requests\WharehouseEdit;

use Validator;
use Illuminate\Support\MessageBag;

use Illuminate\Support\Facades\Auth;
class whareHouseController extends Controller
{    
    public $currentController = '';
    
    // 進入不同controller 自動存取controller名稱
    function __construct(){
        
        $tmpClass =  explode( "\\" , __CLASS__ );
        $this->currentController = end( $tmpClass);
        Session::put('currentClass', $this->currentController );


    }
    // 清單頁面
    public function index()
    {   

    	// 取出倉庫資料
        $Wharehouse      = new Wharehouse;
        $wharehouseDatas = $Wharehouse::get();
        
        $_pageName  = '倉儲相關操作';
        $_pageName2 = '倉庫管理';

        return view('admin_wharehouse.list',['pageName'  => $_pageName,
                                             'pageName2' => $_pageName2,
                                             'wharehouses' => $wharehouseDatas
        	                                 ]);
    }
    
    // 新增倉庫頁面
    public function create(){
        $_pageName  = '倉儲相關操作';
        $_pageName2 = '新增';
        return view('admin_wharehouse.create',['pageName'  => $_pageName,
                                               'pageName2' => $_pageName2
        	                                  ]);

    }

    // 新增進資料庫
    public function create_do( WharehouseEdit $request ){
         
        if( $this->nameExist($request->name) ){

            $request->session()->put('errMsg', '倉庫名稱已存在' );
            return redirect()->back();     
        }

        if( $this->codeExist($request->code) ){

            $request->session()->put('errMsg', '倉庫編碼已存在' );
            return redirect()->back(); 

        }

        try{

            $Wharehouse = new Wharehouse;
            $Wharehouse->name        = $request->name;
            $Wharehouse->code        = $request->code;
            $Wharehouse->description = $request->note;
            $Wharehouse->status      = 1;
            $Wharehouse->operator_id    = Auth::id();
            $Wharehouse->operator_name  = Auth::user()->name; 
            $Wharehouse->save();  

            $request->session()->put('successMsg', '倉庫新增成功' );
            return redirect()->action('whareHouseController@index'); 
        
        }catch(\Exception $e){
            //$e->getMessage()
            logger("in {$this->currentController} :\n {$e->getMessage()}");
            $request->session()->put('errMsg', '倉庫新增失誤 , 請稍後再試' );
            return redirect()->back();     
        }       

    }
    // 編輯
    // id = 倉庫的流水編號      
    public function edit( Request $request , $id ){

        $_pageName  = '倉儲相關操作';
        $_pageName2 = '編輯';
        
        // 如果倉庫編號存在開啟編輯
        $idExist = $this->chkExist($id);
        
        if( !$idExist ){
            //echo "ENTER";
            $request->session()->put('errMsg', '倉庫編號不存在' );
            return redirect()->action('whareHouseController@index');
            exit;
        } 

        // 取出倉庫資料
        $datas = Wharehouse::where('id',$id)->get();
        return view('admin_wharehouse.edit',['pageName'   => $_pageName,
                                             'pageName2'  => $_pageName2,
                                             'wharehouse' => $datas
                                            ]);
    }
    // 編輯實作
    // WharehouseEdit 為驗證條件
    public function editDo( WharehouseEdit $request ){

        /*
        if( $this->nameExist($request->name) ){

            Session::put('errMsg', '倉庫名稱已存在' );
            return redirect()->back();     
        }

        if( $this->codeExist($request->code) ){

            Session::put('errMsg', '倉庫編碼已存在' );
            return redirect()->back(); 

        } 
        */       

       
        try{

            $wharehouse = Wharehouse::find( $request->id );
            $wharehouse->name= $request->name;
            $wharehouse->code= $request->code;
            $wharehouse->description= $request->note;
            $wharehouse->operator_id    = Auth::id();
            $wharehouse->operator_name  = Auth::user()->name;  
            $wharehouse->save(); 

            $request->session()->put('successMsg', '倉庫更新成功' );

            return redirect()->action('whareHouseController@edit', ['id' => $request->id]);
        
        }catch(\Exception $e){

            logger("in {$this->currentController} :\n {$e->getMessage()}");
            $request->session()->put('errMsg', '倉庫更新失誤 , 請稍後再試' );
            return redirect()->action('whareHouseController@edit', ['id' => $request->id]);     
        } 
    }
      
    // 判斷此筆倉庫id是否存在
    protected function chkExist( $id ){
    
        $res = Wharehouse::where('id',$id)->get()->isNotEmpty();
        return $res;
    }

    // 檢測貨架名稱是否已經存在
    protected function nameExist( $name ){

        $res = Wharehouse::where('name',$name)->get()->isNotEmpty();
        return $res;
    }
    // 檢測貨架代碼是否已經存在
    protected function codeExist( $code ){

        $res = Wharehouse::where('code',$code)->get()->isNotEmpty();
        return $res;  
    }    
}


