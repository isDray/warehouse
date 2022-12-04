<?php
Auth::routes(['register' => false]);
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    if( Auth::user() ){

        return redirect()->route('findPage');


    }else{

        //return redirect()->route('login');
        return view('/auth/login');

    }
    //

});

//Auth::routes();
// 關閉註冊

// 

//Route::get('/home', 'HomeController@index')->name('home');


// 通用相關
Route::any('/commonSuccessMsg', 'commonController@getSuccessMsg');
Route::any('/commonErrMsg', 'commonController@getErrMsg');

// 路由群組
Route::middleware(['auth'])->group(function () {

    // 資訊面板相關
    Route::any('/admin_dashboard', function () {
        
    });
    
    // 使用者管理系統
    $this->middleware(['role:admin'])->group(function () {
        Route::get('/admin_user_all', 'userController@index');
        Route::post('/user_query', 'userController@query');
        Route::get('/user_create', 'userController@create');
        Route::post('/user_create_act', 'userController@create_act');
        Route::get('/user_edit/{id}', 'userController@edit');
        Route::post('/user_edit_act', 'userController@edit_act');
        Route::post('/user_del', 'userController@user_delete');
    });

    // 倉儲系統相關
    Route::get('/admin_whare_house', 'whareHouseController@index')->name('admin_whare_house');
    Route::get('/admin_whare_house_create', 'whareHouseController@create');
    Route::post('/admin_whare_house_create_do', 'whareHouseController@create_do');
    Route::get('/admin_whare_house_edit/{id}',  ['uses' =>'whareHouseController@edit']);
    Route::post('/admin_whare_house_editDo', 'whareHouseController@editDo');
    
    // 貨架系統相關
    Route::get('/admin_shelf', 'shelfController@index')->name('shelf');
    Route::get('/admin_shelf_create', 'shelfController@create');
    Route::post('/admin_shelf_createDo', 'shelfController@createDo');    
    Route::get('/admin_shelf_edit/{id}', 'shelfController@edit');
    Route::post('/admin_shelf_editDo', 'shelfController@editDo');
    Route::post('/admin_shelf_rmBlock', 'shelfController@rmBlock');
    Route::post('/admin_shelf_addBlock', 'shelfController@addBlock');
    Route::get('/admin_self_all', 'shelfController@all');
    Route::post('/admin_self_getShelf', 'shelfController@getShelf');

   /*----------------------------------------------------------------
    | 貨架區塊系統相關
    |
    */

    Route::get('/admin_shelf_block/{id}', 'shelfController@block');
    Route::post('/admin_shelf_blockDo', 'shelfController@blockDo');
    Route::post('/admin_shelf_blockDel', 'shelfController@Del');
    Route::get('/autoGetData', 'shelfController@autoGetData');
    Route::get('/autoGetBlockData', 'shelfController@autoGetBlockData');
    //Route::get('/autoGetBlockData', 'shelfController@autoGetBlockData');
    // 多貨架直接點取更新
    Route::post('/admin_shelf_ajaxBlockAddGoods', 'shelfController@ajaxBlockAddGoods');
    // 大量入倉
    Route::get('/admin_shelf_multipleToBlock', 'shelfController@multipleToBlock');
    Route::post('/admin_shelf_multipleToBlockDo', 'shelfController@multipleToBlockDo');
    // 空區塊查詢
    Route::get('/admin_shelf_nullBlock', 'shelfController@nullBlock');
    
    // 匯入貨架資料表
    Route::get('/admin_shelf_import', 'shelfController@shelf_import');
    Route::post('/shelf_import_act', 'shelfController@shelf_import_act');



   /*----------------------------------------------------------------
    |商品相關 
    |
    */

    Route::get('/admin_goods_find', 'goodsController@find')->name('findPage');
    Route::any('/admin_goods_findDo/{goodsSn}', 'goodsController@findDo');
    Route::get('/admin_goods_import', 'goodsController@goodsImport');
    //Route::get('/admin_goods_import', 'goodsController@goodsImport');
    Route::post('/admin_goods_importDo', 'goodsController@goodsImportDo');
    
});

// 顯示第 0 - 24 列 (總計 1207 筆, 查詢花費 0.0020 秒。) [id: 1210... - 1186...]
//1210