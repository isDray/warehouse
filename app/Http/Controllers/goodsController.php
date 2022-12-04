<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use DB; 

use App\Wharehouse;
use App\Shelf;
use App\Block;
use App\Blockgoods;
use App\Goods;
use App\Models\Role;
use App\User;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\Facades\DB;

// 使用會員權限
use Illuminate\Support\Facades\Auth;

class goodsController extends Controller
{

    public $currentController = '';
    
    // 進入不同controller 自動存取controller名稱
    function __construct(){
        
        $tmpClass =  explode( "\\" , __CLASS__ );
        $this->currentController = end( $tmpClass);
        Session::put('currentClass', $this->currentController );


    }

    // 找尋商品
    public function find(){
        
        $_pageName  = '倉儲相關操作';
        $_pageName2 = '商品位置搜尋';
        
        // 撈出所有貨架
        $shelf = Shelf::select('name','code','id')->get();

        return view('admin_goods.find',['pageName'  => $_pageName,
                                        'pageName2' => $_pageName2,
                                        'shelfs' =>  $shelf
                                             ]);        
    }
    
    // 搜尋商品
    public function findDo( $goodsSn ){

        // 以","作為分割符號 , 將各貨號分割
        $goods_snArr = explode(',', $goodsSn);

        // 過濾空白以及空白字串
        foreach ($goods_snArr as $goods_snArrk => $goods_snArrv) {

            if( empty(trim($goods_snArrv)) ){

                unset( $goods_snArr[$goods_snArrk] );

            }else{

                $goods_snArr[$goods_snArrk] = trim($goods_snArrv);
            }
            
        }

        // 除去重複的貨號
        $goods_snArr = array_unique( $goods_snArr );
        
        $returnData = [];


        $shelfList = $this->getShelfList();

        // 迴圈找資料
        foreach ($goods_snArr as $goods_sn) {
            
            // 如果是真實存在的貨號才繼續搜尋
            if( $this->goods_snExist($goods_sn) ){
      
                $datas = $this->findGoond( $goods_sn );
                
                

                if( count($datas)> 0 ){

                    foreach ($datas as $datak => $data) {

                       $returnData['exist'][$goods_sn][] =[
                                                          'shelf'=> $shelfList[$data->shelf_id],
                                                          'layer_num' => $data->layer_num,
                                                          'block_num' => $data->block_num,
                                                          'updated_at' =>$data->updated_at
                                                        ];
                    }
                
                }else{

                    $returnData['nodata'][] = $goods_sn;
                    
                }

            }else{

                $returnData['noexist'][] = $goods_sn;

            }

        }

        echo json_encode($returnData);


    }

    public function goodsImport(){

        $_pageName  = '商品相關操作';
        $_pageName2 = '匯入貨號';

        return view('admin_goods.import',['pageName'  => $_pageName,
                                          'pageName2' => $_pageName2,
                                             ]);               
    }

    public function goodsImportDo( Request $request ){

        $file = $request->file('importGoods');

        if( empty($file) ){
            $request->session()->put('errMsg', '尚未選擇檔案114' );
            return redirect()->back();//->with('test','尚未選擇檔案');            
        }
        $extension = $request->file('importGoods')->extension();
       
        if( $extension !='xlsx' && $extension !='xls'){
            
            $request->session()->put('errMsg', '匯入檔案只接受:xlsx,xls' );
            return redirect()->back();

        }

        if( $file ){
            
            $path = $file->getRealPath();
        
            $reader = Excel::load($path);
 
            $reader = $reader->getSheet(0);
 
            $datas  = $reader->toArray();
        
            $GoodsSns = [];
            
            foreach ($datas  as $datak => $data) {
            
                if( $datak != 0 ){
                    
                    // 如果資料庫中還沒有此筆商品貨號
                    if( !$this->goods_snExist($data[0]) ){
                    
                        array_push($GoodsSns,"{$data[0]}");
                    }
                }
            }
            

            // 貨號寫入資料庫 Start
            try{
                
                DB::beginTransaction();
                
                // 迴圈寫入
                
                foreach ($GoodsSns as $GoodsSnk => $GoodsSn) {
                    $Goods = new Goods;

                    $Goods->goods_sn = $GoodsSn;
                    $Goods->operator_id    = Auth::id();
                    $Goods->operator_name  = Auth::user()->name; 

                    $Goods->save();
                }

                DB::commit();
                

                session()->put('successMsg', '貨號匯入成功' );
                return redirect()->back();
        
            }catch(\Exception $e){
            
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
                logger("in {$this->currentController} :\n {$e->getMessage()}");
            
                Session::put('errMsg', '貨號匯入失誤 , 請稍後再試' );
                return redirect()->back();
            
            }            

            // 貨號寫入資料庫 End
            

        }else{
            
            Session::put('errMsg', '未選擇要匯入之檔案' );
            return redirect()->back();
        }
        
        

    }

    // 驗證貨號是否真實存在
    protected function goods_snExist( $_goodsSn ){
        

        if( Goods::where('goods_sn',$_goodsSn)->get()->isEmpty() ){

            return false;

        }else{

            return true;
        }

        
    }

    // 查詢貨號所在區塊
    protected function findGoond( $_goodsSn ){
        
        //$datas = Blockgoods::select("block_goods * , block.shelf_id ,block.layer_num,block.block_num")->leftJoin('posts', 'block_goods.block_id', '=', 'block.id')->where('goods_sn',$_goodsSn)->get();
        $datas = DB::table('block_goods as bg')
                 ->select('bg.*','b.layer_num','b.block_num','b.shelf_id')
                 ->leftJoin('block as b', 'bg.block_id', '=', 'b.id')
                 ->where('bg.goods_sn',$_goodsSn)
                 ->orderBy('updated_at', 'asc')
                 ->get();

        return $datas;
    }
    
    // 查詢所有貨架代號
    protected function getShelfList(){
        
        $list = [];

        $datas = Shelf::get();
        
        foreach ( $datas as $data ) {
            
            $list[$data->id] = $data->code;

        }

        return $list;
    }

}

