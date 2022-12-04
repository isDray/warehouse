<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use DB; 

// Model
use App\Wharehouse;
use App\Shelf;
use App\Block;
use App\Blockgoods;
use App\Goods;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ShelfEdit;
// 使用會員權限
use Illuminate\Support\Facades\Auth;

class shelfController extends Controller
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
        $Shelf      = new Shelf;
        $ShelfDatas = $Shelf::get();
        
        $_pageName  = '倉儲相關操作';
        $_pageName2 = '貨架管理';

        
        return view('admin_shelf.list',['pageName'  => $_pageName,
                                        'pageName2' => $_pageName2,
                                        'shelfs' => $ShelfDatas
                                             ]);
        
    }

    // 新增貨架頁面
    public function create( ){
        
        $_wharehouse = Wharehouse::select('id','name')->get();
        $_pageName  = '貨架相關操作';
        $_pageName2 = '新增';
        return view('admin_shelf.create',['pageName'    => $_pageName,
                                          'pageName2'   => $_pageName2,
                                          'wharehouses' => $_wharehouse
                                         ]);

    }    
    // 新增貨架實作
    // ShelfEdit為驗證規則
    public function createDo( ShelfEdit $request ){

        
        try{
            DB::beginTransaction();
            
            $Shelf = new Shelf;
            $Shelf->name           = $request->name;
            $Shelf->code           = $request->code;
            $Shelf->note           = $request->note;
            $Shelf->status         = 1;
            $Shelf->wharehouse_id  = $request->wharehouse;            
            $Shelf->operator_id    = Auth::id();
            $Shelf->operator_name  = Auth::user()->name; 
            $Shelf->save();             

            // 針對區塊做寫入
            foreach ( $request->floor as $key => $value) {
                $layer = $key + 1;
    
                $block = $value + 1;
                for ( $i=1 ; $i <$block ; $i++) { 
                    $Block = new Block;
                    $Block->shelf_id  = $Shelf->id;
                    $Block->layer_num = $layer;
                    $Block->block_num = $i;
                    $Block->operator_id =  Auth::id();
                    $Block->operator_name  = Auth::user()->name;                 
                    $Block->save();  
                }
            }

            DB::commit();
            //Session::put('successMsg', '貨架新增成功' );
            $request->session()->put('successMsg', '貨架新增成功' );
            return redirect()->action('shelfController@index'); 
        
        }catch(\Exception $e){
            
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            logger("in {$this->currentController} :\n {$e->getMessage()}");
            
            $request->session()->put('errMsg', '貨架新增失誤 , 請稍後再試' );
            return redirect()->back();
            
        }
        
    }

    // 編輯畫面
    // $id 為貨架編號
    public function edit( $id ){

        $_pageName  = '貨架相關操作';
        $_pageName2 = '編輯';        
        // 撈出倉庫
        $_wharehouse = Wharehouse::select('id','name')->get();
        // 撈出貨架資料
        $Shelf = new Shelf;
        $thisShelf = $Shelf->where('id', $id )->first();
        
        // 撈出貨架分割並且整理成array
        $Block = new Block;
        $thisLayers = $Block->select('layer_num')->where('shelf_id',$id)->groupBy('layer_num')->orderBy('layer_num', 'asc')->get();
        
        $blockArr = [];
        foreach ($thisLayers as $key => $value) {
            //echo $value->layer_num;
            //echo '<br>';
            //array_push($blockArr, $value->layer_num);
            $allBlocks = $Block->select('block_num')->where('shelf_id',$id)->where('layer_num',$value->layer_num)->orderBy('block_num', 'asc')->get();
            
            $tmpBlockArr = [];
            foreach ($allBlocks as $allBlockk => $allBlock) {

                array_push($tmpBlockArr , $allBlock->block_num);
        
            }

            $blockArr[$value->layer_num] = $tmpBlockArr;



            //echo '<br>-------<br>';
        }

        return view('admin_shelf.edit',[  'pageName'    => $_pageName,
                                          'pageName2'   => $_pageName2,
                                          'Shelf'       => $thisShelf,
                                          'blockArr'    => $blockArr,
                                          'wharehouses' => $_wharehouse
                                         ]);

    }
    
    // 編輯操作
    public function editDo( ShelfEdit $request ){
        

        try{

            $Shelf = Shelf::find( $request->id );
            $Shelf->name= $request->name;
            $Shelf->code= $request->code;
            $Shelf->note= $request->note;
            $Shelf->wharehouse_id  = $request->wharehouse;             
            $Shelf->operator_id    = Auth::id();
            $Shelf->operator_name  = Auth::user()->name;  
            $Shelf->save(); 

            $request->session()->put('successMsg', '貨架更新成功' );
            return redirect()->action('shelfController@edit', ['id' => $request->id]);
        
        }catch(\Exception $e){

            $request->session()->put('errMsg', '貨架更新失誤 , 請稍後再試' );
            return redirect()->action('shelfController@edit', ['id' => $request->id]);     
        }
              
    }    

    // 商品入貨架畫面
    public function block( $id ){

        $_pageName  = '貨架區塊相關操作';
        $_pageName2 = '上架';

        // 撈出貨架分割並且整理成array
        $Block = new Block;
        $thisLayers = $Block->select('layer_num')->where('shelf_id',$id)->groupBy('layer_num')->orderBy('layer_num', 'asc')->get();
        

        $blockArr = [];
        foreach ($thisLayers as $key => $value) {
            //echo $value->layer_num;
            //echo '<br>';
            //array_push($blockArr, $value->layer_num);
            $allBlocks = $Block->select('id','block_num')->where('shelf_id',$id)->where('layer_num',$value->layer_num)->orderBy('block_num', 'asc')->get();
            
            $tmpBlockArr = [];
            foreach ($allBlocks as $allBlockk => $allBlock) {

                $tmpBlockArr[$allBlock->block_num]['id'] = $allBlock->id;
                $tmpBlockArr[$allBlock->block_num]['block_num'] = $allBlock->block_num;
        
            }
           
            $blockArr[$value->layer_num] = $tmpBlockArr;
            //echo '<br>-------<br>';
        }
        
        return view('admin_shelf.addToBlock',[  'pageName'    => $_pageName,
                                                'pageName2'   => $_pageName2,
                                                'blockArr'    => $blockArr,
                                             ]);
    }

    // 區塊寫入商品
    public function blockDo( Request $request){

        /*****************************************************************
         *  檢查貨架
         *
         ******************************************************************/        

        if( !$this->blockExist($request->block) ){

            return redirect()->back(); 
        }

        /*****************************************************************
         *  檢查貨號
         *
         ******************************************************************/

        // 多組或號字串
        $goods_snStr = $request->goods_sn;
        
        // 以","作為分割符號 , 將各貨號分割
        $goods_snArr = explode(',', $goods_snStr);

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

        // 檢測是否全部的貨號是真實存在
        if( !$this->goods_snExist( $goods_snArr ) ){

            return redirect()->back(); 
        }
        /*****************************************************************
         *  檢查是否已經將貨物放入區塊
         *
         ******************************************************************/
        $duplicate =  $this->alreadyInBlock( $request->block , $goods_snArr);
        
        /*
        $successMsgCond ='';

        foreach ($duplicate as $duplicateGoods) {

            Session::put('successMsg', "存入區塊成功".$successMsgCond );

        }
        */

        $goods_snArr= array_diff( $goods_snArr, $duplicate );
        

        /*****************************************************************
         *  檢查是否需要執行從區塊移除貨號
         *
         ******************************************************************/  
        $removeSwitch = False;
        
        if( isset($request->checkDelet) && $request->checkDelet == 'on'){

            $goods_snRmStr = $request->goods_sn_rm;
        
            // 以","作為分割符號 , 將各貨號分割
            $goods_snRmArr = explode(',', $goods_snRmStr);

        // 過濾空白以及空白字串
        foreach ($goods_snRmArr as $goods_snRmArrk => $goods_snRmArrv) {

            if( empty(trim($goods_snRmArrv)) ){

                unset( $goods_snRmArr[$goods_snRmArrk] );

            }else{

                $goods_snRmArr[$goods_snRmArrk] = trim($goods_snRmArrv);
            }
            
        }

        // 除去重複的貨號
        $goods_snRmArr = array_unique( $goods_snRmArr );
        
        $goods_snRmStr = implode(',', $goods_snRmArr);
        
        $removeSwitch = True;
        
        }        
        
        try{

            DB::beginTransaction();
            
            foreach ($goods_snArr as $goodsSn) {

                $Blockgoods = new Blockgoods;
                $Blockgoods->block_id           = $request->block;
                $Blockgoods->goods_sn           = $goodsSn;         
                $Blockgoods->operator_id    = Auth::id();
                $Blockgoods->operator_name  = Auth::user()->name; 
                $Blockgoods->save(); 

            }

            if( $removeSwitch ){
                
                Blockgoods::where('block_id', $request->block)->whereIn('goods_sn',$goods_snRmArr)->delete();

            }

            DB::commit();

            $request->session()->put('successMsg', "存入區塊成功");
            //return redirect()->action('shelfController@index'); 
            return redirect()->back();
        
        }catch(\Exception $e){
            
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            logger("in {$this->currentController} :\n {$e->getMessage()}");
            
            $request->session()->put('errMsg', '存入區塊失誤 , 請稍後再試' );

            return redirect()->back();
        
        }
        
    }
    public function Del( Request $request ){
        
        //echo json_decode($request->shelfId);
        if( empty( $request->shelfId) ){
            
            exit;
        }
        $Shelf = Shelf::where('id',$request->shelfId)->get();
        
        // 撈出全部區塊
        $Blocks = Block::where('shelf_id',$request->shelfId)->get();
        
        // 將區塊存成陣列
        $blockArr = [];
        
        foreach ($Blocks as $Blockk => $Block) {

            array_push($blockArr, $Block->id);
        }

        $deleteGoods = Blockgoods::whereIn('block_id',$blockArr)->get();

        // 執行刪除動作
        try{

            DB::beginTransaction();
                
                $Shelf->each->delete();
                $Blocks->each->delete();
                $deleteGoods->each->delete();

            DB::commit();
            
            $returnDatas['res']  = True;
            $returnDatas['info'] = '刪除完成';

            echo json_encode($returnDatas);

        }catch(\Exception $e){
            
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            logger("in {$this->currentController} :\n {$e->getMessage()}");

            $returnDatas['res'] = False;
            $returnDatas['info'] = '刪除過程失誤,請稍後再試';

            echo json_encode($returnDatas);

        }        
        // 執行刪除動作END


    }
    // 呈現全貨架區塊
    public function all(){

        $_pageName  = '倉儲相關操作';
        $_pageName2 = '貨架圖示清單';
        
        // 撈出所有倉庫
        $whareHouses =  Wharehouse::select('name','code','id')->get();
        
        // 區塊資料
        $blockArr = [];
        
        // 撈出倉庫所有貨架
        foreach ($whareHouses as $key => $value) {
            

            $whareHouseId = $value->id;
            
            $shelfs = Shelf::select('id','name','code')
                   -> where('wharehouse_id' , $whareHouseId)
                   -> get();

            foreach ($shelfs as $shelfk => $shelf ) {
                
               
                $layers = Block::select('layer_num')
                       -> where('shelf_id',$shelf->id)
                       -> groupBy('layer_num')
                       -> orderBy('layer_num', 'asc')
                       -> get();


                foreach ($layers as $layerk => $layer) {
                    
                    // 當前要撈的層數
                    $tmpLayer = $layer['layer_num'];

                    $blocks   = Block::select('block_num','id')
                             -> where('shelf_id',$shelf->id)
                             -> where('layer_num',$tmpLayer)
                             -> orderBy('block_num', 'asc')
                             -> get();
                    
                    foreach ($blocks as $blockk => $block) {
                        
                        $tmpBlock   = $block->block_num;
                        $tmpBlockId = $block->id;

                        $blockGoods =  Blockgoods::select('goods_sn')->where('block_id',$tmpBlockId)->get();
                        
                        $goodsSnArr = [];
                        foreach ($blockGoods as $blockGoodk => $blockGoods) {
                            

                            array_push($goodsSnArr, $blockGoods->goods_sn);

                        }
                        $goodsSnArr['blockId'] = $tmpBlockId;

                        $blockArr[$key][$shelf->code][$tmpLayer][$tmpBlock] = $goodsSnArr;
                        //$blockArr[$key][$shelf->name][$tmpLayer]['blockId'] = $tmpBlockId;
                    }
                    
                    
                }
                
            }


        }

        
        // 撈出所有貨架
        $shelf = Shelf::select('name','code','id')->get();

        return view('admin_shelf.all',[ 'pageName'    => $_pageName,
                                        'pageName2'   => $_pageName2,
                                        'whareHouses' => $whareHouses,
                                        'blockArrs'   => $blockArr,
                                        'shelfs'      => $shelf
                                      ]);
    }
    // 撈取單一貨架結構
    public function getShelf( Request $request ){
        
        $returnDatas = [];
        
        if( !empty($request->shelfId) ){

            $blockArr = [];


            $layers = Block::select('layer_num')
                   -> where('shelf_id',$request->shelfId)
                   -> groupBy('layer_num')
                   -> orderBy('layer_num', 'asc')
                   -> get();

            foreach ($layers as $layerk => $layer) {
                    
                // 當前要撈的層數
                $tmpLayer = $layer['layer_num'];

                $blocks   = Block::select('block_num','id')
                         -> where('shelf_id',$request->shelfId)
                         -> where('layer_num',$tmpLayer)
                         -> orderBy('block_num', 'asc')
                         -> get();
                    
                foreach ($blocks as $blockk => $block) {
                        
                    $tmpBlock   = $block->block_num;
                    $tmpBlockId = $block->id;

                    $blockGoods =  Blockgoods::select('goods_sn')->where('block_id',$tmpBlockId)->get();
                        
                    $goodsSnArr = [];
                    foreach ($blockGoods as $blockGoodk => $blockGoods) {
                            

                        array_push($goodsSnArr, $blockGoods->goods_sn);

                    }
                    $goodsSnArr['blockId'] = $tmpBlockId;

                    $blockArr[$tmpLayer][$tmpBlock] = $goodsSnArr;
                        //$blockArr[$key][$shelf->name][$tmpLayer]['blockId'] = $tmpBlockId;
                }
                    
            }    

            $returnDatas['res']    = True;
            $returnDatas['Datas']  = $blockArr;
            echo json_encode($returnDatas);
            
        }else{

            $returnDatas['res']  = False;
            $returnDatas['info'] = '沒有接受到要查詢的貨架,請重新整理後再試';

            echo json_encode( $returnDatas );
        }
    }
    // ajax 增加商品到區塊中
    public function ajaxBlockAddGoods( Request $request ){
        
        //echo json_encode($request->input());

        // 回傳用的Data
        $returnDatas = [];
        
        // 確認有收到 blockId & goodsSns
        if( !empty( $request->blockId ) ){
            
            $_blockId  = $request->blockId;
            $_goodsSns = $request->goodsSns;

            // 確認區塊存在
            if( !$this->blockExistNoSession( $_blockId ) ){
                
                $returnDatas['res']  = False;
                $returnDatas['info'] = "區塊不存在,無法進行此操作";
                echo json_encode( $returnDatas );
                exit;
            }
            
            // 分割貨號
            $splitGoodsSns = explode(",",$_goodsSns );
            
            // 附加訊息
            $returnDatas['info'] = '';
            
            $GoodsToAdd = [];
            
            // 取出該區塊本來有的區塊
            $dbAllTmp = Blockgoods::where('block_id',$_blockId)->get();
            $dbAll = [];
            foreach ($dbAllTmp as $dbAllTmpv) {

                array_push($dbAll, $dbAllTmpv->goods_sn);
            }
            
            foreach ($splitGoodsSns as $splitGoodsSnk => $splitGoodsSn) {
                
                $splitGoodsSns[$splitGoodsSnk] = trim($splitGoodsSn);
            }
            
            $deleteSn = array_diff( $dbAll, $splitGoodsSns);

            

            foreach ( $splitGoodsSns as $splitGoodsSnk => $splitGoodsSn) {
                
                $GoodsSnCheck = trim($splitGoodsSn);
                if(  !empty($GoodsSnCheck ) ){
                    if( $this->goods_snExistNoSession($GoodsSnCheck) ){
    
                        array_push($GoodsToAdd , $GoodsSnCheck);
    
                    }else{
    
                        $returnDatas['info'] .= "*".$GoodsSnCheck."不存在,請確認後再嘗試<br>";
                    }
                }
            }
            
            // 確認區塊中是否已經有該貨物
            foreach ($GoodsToAdd as $GoodsToAddk => $GoodsToAddv) {
                
                // 如果已經存在
                if( $this->alreadyInBlockNoSession( $_blockId , $GoodsToAddv ) ){
                    
                    // $returnDatas['info'] .= "*".$GoodsToAddv."已經存在該區塊<br>";
                    unset( $GoodsToAdd[$GoodsToAddk] );
                }

            }


            // 如果全部都是非法的貨號則直接中斷
            if( count( $GoodsToAdd ) == 0 && count($deleteSn) == 0 ){

                $returnDatas['res']  = False;
                $returnDatas['info'] .= "沒有需要操作的請求<br>";
                echo json_encode( $returnDatas );
                exit;
            
            }

            // 寫入資料庫 start
            try{
                
                DB::beginTransaction();
                
                foreach ($deleteSn as $deleteSnk => $deleteSnv) {

                    $delIns = Blockgoods::where('block_id',$_blockId)->where('goods_sn',$deleteSnv)->get();
                    $delIns->each->delete();
                }
                foreach ($GoodsToAdd as $goodsSn) {

                    $Blockgoods = new Blockgoods;
                    $Blockgoods->block_id           = $_blockId;
                    $Blockgoods->goods_sn           = $goodsSn;         
                    $Blockgoods->operator_id    = Auth::id();
                    $Blockgoods->operator_name  = Auth::user()->name; 
                    $Blockgoods->save(); 

                }

                DB::commit();
                
                $allGoods = Blockgoods::where('block_id',$_blockId)->get();
                
                $returnGoods = [];
                foreach ($allGoods as $allGood) {

                    array_push($returnGoods, $allGood->goods_sn);

                }
                $returnDatas['res']   = True;
                $returnDatas['Datas'] = $returnGoods;
                $returnDatas['info']  = "編輯成功<br>".$returnDatas['info'];
                echo json_encode($returnDatas);

            
            }catch(\Exception $e){
            
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
                logger("in {$this->currentController} :\n {$e->getMessage()}");
                $returnDatas['res']  = False;

                echo json_encode($returnDatas);
                exit;
        
            }            
            // 寫入資料庫 end


            
        }else{

            $returnDatas['res']  = False;
            $returnDatas['info'] = "缺少必要參數,請重新整理頁面後再嘗試";
            echo json_encode( $returnDatas );
        
        }
        
        
    }

    // 大量入倉畫面
    public function multipleToBlock(){
        
        $_pageName  = '倉儲相關操作';
        $_pageName2 = '大量入倉';

        return view('admin_shelf.import',['pageName'  => $_pageName,
                                          'pageName2' => $_pageName2,
                                             ]);    
    }

    // 大量入倉執行
    public function multipleToBlockDo( Request $request ){

        $file = $request->file('importShelfGoods');
        
        if( empty($file) ){
            $request->session()->put('errMsg', '尚未選擇檔案' );
            return redirect()->back();            
        }        

        $extension = $request->file('importShelfGoods')->extension();
        
        if( $extension !='xlsx' && $extension !='xls'){
            
            Session::put('errMsg', '匯入檔案只接受:xlsx,xls' );
            return redirect()->back();
        }

        if( $file ){
            
            // 最後要寫入SESSION的資料
            $returnDatas = [];

            $path = $file->getRealPath();
        
            $reader = Excel::load($path);
 
            $reader = $reader->getSheet(0);
 
            $datas  = $reader->toArray();
            
            $toAdd  = [];
            
            $returnDatas['info1'] = '';

            $returnDatas['info2'] = '';

            foreach ($datas as $datak => $data) {

                if( $datak != 0 ){
                    
                    $data[0] = trim($data[0]);

                    $data[1] = trim($data[1]);
                    
                    // 確認區塊是否存在,如果存在回傳區塊ID 如果不存在回傳FALSE
                    $getBlock = $this->blockExistByCode($data[0]);
                    
                    if( $getBlock !== False ){
                        
                        if( $this->goods_snExistNoSession( $data[1] ) ){
                            
                            // 貨架以及貨號都存在,可以寫入
                            $tmpArr = [$getBlock,$data[1]];
                            array_push($toAdd, $tmpArr);

                        }else{
                            
                            $returnDatas['info2'] .= $data[1]."貨號不存在<br>";
                        }

                    }else{
                        
                        $returnDatas['info1'] .= $data[0]."區塊不存在<br>";
                    }
                
                }
            }
        
            if( count( $toAdd ) == 0){
                
                
                $tmpMsg = '檔案中沒有可以新增的資料<br>'.$returnDatas['info1'].$returnDatas['info2'];
                Session::put('errMsg', $tmpMsg );
                return redirect()->back();

                exit;

            }
            
            // 確認區塊中是否已經有該貨物
            foreach ($toAdd as $GoodsToAddk => $GoodsToAddv) {
                
                // 如果已經存在
                if( $this->alreadyInBlockNoSession( $GoodsToAddv[0] , $GoodsToAddv[1] ) ){
                    
                    //$returnDatas['info'] .= "*".$GoodsToAddv."已經存在該區塊<br>";

                    unset( $toAdd[$GoodsToAddk] );
                }

            }            
            
            // 開始寫入資料庫
            try{
                
                DB::beginTransaction();
            
                foreach ($toAdd as $goodsSn) {

                    $Blockgoods = new Blockgoods;
                    $Blockgoods->block_id           = $goodsSn[0];
                    $Blockgoods->goods_sn           = $goodsSn[1];         
                    $Blockgoods->operator_id    = Auth::id();
                    $Blockgoods->operator_name  = Auth::user()->name; 
                    $Blockgoods->save(); 

                }

                DB::commit();
                
                $tmpMsg = '新增成功<br>'.$returnDatas['info1'].$returnDatas['info2'];
                Session::put('successMsg', $tmpMsg ); 
                return redirect()->back();              


            
            }catch(\Exception $e){
            
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
                logger("in {$this->currentController} :\n {$e->getMessage()}");
                
                $tmpMsg = '新增過程出錯<br>'.$returnDatas['info1'].$returnDatas['info2'];
                Session::put('errMsg', $tmpMsg );
                return redirect()->back();

        
            }             
            // 寫入資料庫結束

        }else{

            Session::put('errMsg', '未選擇要匯入之檔案' );
            return redirect()->back();
        }
    }

    public function nullBlock( Request $request ){
        
        $_pageName  = '倉儲相關操作';
        $_pageName2 = '空貨架搜尋';

        $stackeds = Blockgoods::select('block_id')->groupBy('block_id')->get();
        
        $stackArr = [];
        foreach ($stackeds as $stacked) {
            
            array_push($stackArr, $stacked->block_id);
        }
        
        $datas= Block::leftJoin('shelf', function($join) {$join->on('block.shelf_id', '=', 'shelf.id');})
             ->whereNotIn('block.id', $stackArr)
             ->get(['block.*', 'shelf.code']);
        /*
        foreach ( $datas as $key => $value) {
            echo $value->code."-".$value->layer_num."-".$value->block_num;
            echo "<br>";
        }
        */
        
        return view('admin_shelf.null',[ 'pageName'  => $_pageName,
                                         'pageName2' => $_pageName2,
                                         'datas'     => $datas
                                       ]);
        
    }
    // 取的資料庫的相關商品
    public function autoGetData( Request $request){

        if( isset($request->term) ){
           
           $datas =  Goods::where('goods_sn', 'like', "%$request->term%")->get();
           $returnArr = [];
           foreach ($datas as $datak => $data) {

               
               $returnArr[] = ['id'=>$datak,
                               'label'=>$data->goods_sn,
                               'value'=>$data->goods_sn
                              ];

           }

           echo json_encode($returnArr);

        }
    }
    
    // 取得或架中有的商品
    public function autoGetBlockData( Request $request ){

        if( isset($request->term) && isset($request->block) ){
           
           $datas =  Blockgoods::where('goods_sn', 'like', "%$request->term%")->where('block_id',"$request->block")->get();
           $returnArr = [];
           foreach ($datas as $datak => $data) {

               
               $returnArr[] = ['id'=>$datak,
                               'label'=>$data->goods_sn,
                               'value'=>$data->goods_sn
                              ];

           }

           echo json_encode($returnArr);

        }
    }

    // 移除區塊
    public function rmBlock( Request $request ){
        
        // 確認是否所有必需的參數都有收到
        if( !empty($request->shelfId) && !empty($request->layerNum) && !empty($request->blockNum) ){
            
            //確認該層數是否只剩最後一個區塊
            $allBlock = Block::where('shelf_id',$request->shelfId)->where('layer_num',$request->layerNum)->get();
            if( count( $allBlock ) == 1 ){
                
                $blockArr['res']  = False;
                $blockArr['info'] = "此層數只剩最後一個區塊 , 不可移除";
                echo json_encode( $blockArr );
                exit;

            }
            // 撈出同層裡面其他的區塊
            $gtBlocks = Block::where('shelf_id',$request->shelfId)->where('layer_num',$request->layerNum)->where('block_num','>',$request->blockNum)->get();
            
           
        
            try{

                DB::beginTransaction();

                $block = Block::where('shelf_id',$request->shelfId)->where('layer_num',$request->layerNum)->where('block_num',$request->blockNum)->get();
                $block->each->delete();

                if( count( $gtBlocks ) > 0 ){
                    
                    foreach ($gtBlocks as $gtBlockk => $gtBlock) {
                        
                        $block = Block::where('shelf_id',$request->shelfId)->where('layer_num',$request->layerNum)->where('block_num',$gtBlock->block_num)->get();

                        $block->each->decrement('block_num' , 1);

                    }

                }

                DB::commit();
                
                /*****************/
                
                // 撈出貨架分割並且整理成array
                $Block = new Block;
                $thisLayers = $Block->select('layer_num')->where('shelf_id',$request->shelfId)->groupBy('layer_num')->orderBy('layer_num', 'asc')->get();
        
                $blockArr = [];
                foreach ($thisLayers as $key => $value) {

                    $allBlocks = $Block->select('block_num')->where('shelf_id',$request->shelfId)->where('layer_num',$value->layer_num)->orderBy('block_num', 'asc')->get();
            
                    $tmpBlockArr = [];
                    
                    foreach ($allBlocks as $allBlockk => $allBlock) {

                        array_push($tmpBlockArr , $allBlock->block_num);
        
                    }

                    
                    $blockArr[$value->layer_num] = $tmpBlockArr;
                    $blockArr['res'] = True;
                }   
                echo json_encode($blockArr);   

                /*****************/
                /*Session::put('successMsg', "存入區塊成功");
                //return redirect()->action('shelfController@index'); 

                return redirect()->back();*/
        
            }catch(\Exception $e){
            
                //$e->getMessage();

                // 寫入錯誤代碼後轉跳
                logger("in {$this->currentController} :\n {$e->getMessage()}");
                echo json_encode( $blockArr['res'] = False );
                //Session::put('errMsg', '存入區塊失誤 , 請稍後再試' );
                //return redirect()->back();
        
            }            

        }
    }

    // 新增區塊
    public function addBlock( Request $request ){

        // 確認是否有接收到所有必須參數
        if( !empty($request->shelfId) && !empty($request->layerNum) ){
            
            // 撈出該層數最大的區塊號碼 , 存入$maxBlockNum
            $maxBlockNum = Block::where('shelf_id',$request->shelfId)
                                ->where('layer_num',$request->layerNum)
                                ->max('block_num');

            // 新增一個
            try {
                
                $Block = new Block;

                $Block->shelf_id  = intval(trim($request->shelfId));
                
                $Block->layer_num = intval(trim($request->layerNum));

                $Block->block_num = intval(trim( $maxBlockNum+1 ));
                
                $Block->operator_id = Auth::id();
                
                $Block->operator_name = Auth::user()->name; 

                $Block->save();

                // 撈出貨架分割並且整理成array
                $Block = new Block;
                $thisLayers = $Block->select('layer_num')->where('shelf_id',$request->shelfId)->groupBy('layer_num')->orderBy('layer_num', 'asc')->get();
        
                $blockArr = [];

                foreach ($thisLayers as $key => $value) {

                    $allBlocks = $Block->select('block_num')->where('shelf_id',$request->shelfId)->where('layer_num',$value->layer_num)->orderBy('block_num', 'asc')->get();
            
                    $tmpBlockArr = [];
                    
                    foreach ($allBlocks as $allBlockk => $allBlock) {

                        array_push($tmpBlockArr , $allBlock->block_num);
        
                    }

                    
                    $blockArr[$value->layer_num] = $tmpBlockArr;

                    $blockArr['res'] = True;
                }   
                echo json_encode($blockArr); 


            } catch (\Exception $e) {
                
                logger("in {$this->currentController} :\n {$e->getMessage()}");
                $blockArr['res']  = False ;
                $blockArr['info'] = "新增失敗請稍後再試";
                echo json_encode( $blockArr );                
            }

            

        }else{
            
            $blockArr['res']  = False;
            $blockArr['info'] = "缺少必續參數 , 請重新整理頁面後再嘗試";
            echo json_encode( $blockArr );
        }

    }// 新增區塊結束
    
    /*
    |----------------------------------------------------------------
    | 匯入貨架資料 - 介面
    |----------------------------------------------------------------
    |
    */
    public function shelf_import(){
        
        $_pageName  = '倉儲相關操作';
        $_pageName2 = '匯入貨架資料';

        return view('admin_shelf.shelf_import',['pageName'  => $_pageName,
                                                'pageName2' => $_pageName2,
                                             ]);    
    }    




    /*
    |----------------------------------------------------------------
    | 匯入貨架資料 - 功能
    |----------------------------------------------------------------
    |
    */    
    public function shelf_import_act( Request $request ){
        
        $file = $request->file('importShelfData');
        
        if( empty($file) ){
            $request->session()->put('errMsg', '尚未選擇檔案' );
            return redirect()->back();            
        }        

        $extension = $request->file('importShelfData')->extension();
        
        if( $extension !='xlsx' && $extension !='xls'){
            
            Session::put('errMsg', '匯入檔案只接受:xlsx,xls' );
            return redirect()->back();
        }

        if( $file ){
            
            // 最後要寫入SESSION的資料
            $returnDatas = [];

            $path = $file->getRealPath();
            
            $reader = Excel::load($path);
            
            // 第幾張表
            $reader->setActiveSheetIndex(3);
           
            // 每層幾格
            $blocks_perlayers = 6;

            $array_cells = $reader->getActiveSheet()->toArray();
            
            $max_column   = COUNT($array_cells[0]);
            
            $max_row = COUNT($array_cells);
        
            // 貨架表示法
            $shelf_layer_patten = "/^[a-zA-Z]{1}[0-9]{1,2}[-]{0,1}[\x{4e00}-\x{9fa5}]{2}/u";

            // 貨架編碼表示法
            $shelf_code_pattern = "/^[a-zA-Z]{1}[0-9]{1,2}(-[0-9]{1,2})+$/";

            // 貨號表示法
            $goods_pattern = "/^([0-9]{4,6}|)$/";
            
            $shelf_codes = [];

            $arrage_shelf_codes = [];

            for( $i=1; $i<=$max_row; $i++){
                
                for( $j=0; $j<=$max_column; $j++ ){
                    
                    $now_value = $reader->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();

                    if( preg_match($shelf_code_pattern,$now_value) ){

                        array_push( $shelf_codes , $now_value );
                    }
                }

            }
            
            // 迴圈重新整理
            foreach( $shelf_codes as $shelf_code ){
                
                $class_code = explode('-',$shelf_code)[0];

                if( !array_key_exists($class_code,$arrage_shelf_codes) ){
                    $arrage_shelf_codes[$class_code] = [];
                }

                $arrage_shelf_codes[$class_code][$shelf_code] = [];
            }


            // 取出貨號
            for( $i=1; $i<=$max_row; $i++){
                
                for( $j=0; $j<=$max_column; $j++ ){
                    
                    $now_value = $reader->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();

                    if( preg_match($goods_pattern,$now_value) ){

                        for( $k=$i; $k >= 1 ; $k --){

                            $find_code = $reader->getActiveSheet()->getCellByColumnAndRow($j, $k)->getValue();
                            
                            if( preg_match($shelf_code_pattern,$find_code) ){
                                
                                $class_code = explode('-',$find_code)[0];
                                
                                array_push( $arrage_shelf_codes[$class_code][$find_code] ,$now_value  );
                                break;
                            }
                        }
                    }
                }

            }          
            
            echo '<pre>';
            var_dump( $arrage_shelf_codes );
            echo '</pre>';

        }
    }

    // 確認貨號存在資料庫
    // $goods = 要驗證的貨號陣列
    protected function goods_snExist( array $goods ){
        
        foreach ( $goods as $key => $value ) {

            if(Goods::where('goods_sn',$value)->get()->isEmpty()){

                session()->put('errMsg', "貨號:".$value."不存在");
                return false;
            }

        }

        return true;
        
         
       
    }
    protected function goods_snExistNoSession( $_goods ){
        
       

        if(Goods::where('goods_sn',$_goods)->get()->isEmpty()){

                return false;
        }

        return true;         
       
    }

    // 確認區塊代碼是否存在
    protected function blockExist( $id ){
        
        $res = Block::where('id',$id)->get()->isNotEmpty();
        if( !$res ){

            session()->put('errMsg', "區塊不存在");
        }
        return $res;
    }

    // 確認區塊代碼是否存在(不存session)
    protected function blockExistNoSession( $id ){
        
        $res = Block::where('id',$id)->get()->isNotEmpty();

        return $res;
    }

    // 檢查商品是否已經存在區塊中
    // $block = 區塊號碼
    // $goods = 貨號陣列
    protected function alreadyInBlock( $block , array $goods ){
        
        // 該貨架已存在的貨號
        $alreadyGoods = [];

        foreach ($goods as $good) {
            
            $res = Blockgoods::where('block_id',$block)
                             ->where('goods_sn',$good)
                             ->get()
                             ->isNotEmpty();
            
            // 如果已經存在了
            if( $res ){
                array_push($alreadyGoods, $good);
            }
        }

        return $alreadyGoods;
    }

    protected function alreadyInBlockNoSession( $block , $good){
        
        $res = Blockgoods::where('block_id',$block)
                         ->where('goods_sn',$good)
                         ->get()
                         ->isNotEmpty(); 
        if( $res ){

            return true;
        }

        return false;       
    }
    
    // 
    protected function blockExistByCode( $code ){
        
        // 分割
        $tmpExplode = explode("-", $code);

        $shelfCode = $tmpExplode[0];

        $layerNum  = $tmpExplode[1];

        $blockNum  = $tmpExplode[2];
        
        $datas     = DB::table('block as b')
                  ->select('b.id')
                  ->leftJoin('shelf as s', 'b.shelf_id', '=', 's.id')
                  ->where('b.layer_num',$layerNum)
                  ->where('b.block_num',$blockNum)
                  ->where('s.code',$shelfCode)
                  ->first();

        if( count($datas) == 0){
            
            return False;

        }else{

            return $datas->id;
        }
    }
}
