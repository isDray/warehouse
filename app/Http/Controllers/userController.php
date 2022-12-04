<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Session;
use App\User;
use App\Models\Role;
use DB; 

class userController extends Controller
{
    function __construct(){
        
        $tmpClass =  explode( "\\" , __CLASS__ );
        $this->currentController = end( $tmpClass);
        Session::put('currentClass', $this->currentController );

    }

    public function index()
    {   

        $_pageName  = '使用者管理';
        $_pageName2 = '使用者列表';
        
        $Users = User::SELECT("users.*" ,"roles.display_name")
               ->leftJoin('role_user',function($join) {
                
                     $join->on('users.id', '=', 'role_user.user_id');
                 })
               ->leftJoin('roles', function($join2) {

                     $join2->on('role_user.role_id', '=', 'roles.id');

                 })->get();
          
        
        return view('user.list',['pageName'  => $_pageName,
                                 'pageName2' => $_pageName2,
                                 'Users'     => $Users
                                           ]);

    }



    /*
    |----------------------------------------------------------------
    | 新增使用者介面
    |----------------------------------------------------------------
    |
    |
    */
    public function create(){

        $_pageName  = '使用者管理';
        $_pageName2 = '新增使用者';
        
        // 取出可用權限
        $user_roles = Role::where('name','!=','admin')->get();

        return view('user.userform' , ['pageName'  => $_pageName,
                                       'pageName2' => $_pageName2,
                                       'mode'      => 'create',
                                       'user_roles'=>$user_roles]);
    }
    



    /*
    |----------------------------------------------------------------
    | 列表查詢
    |----------------------------------------------------------------
    |
    |
    */
    public function query( Request $request ){
        

        $table_records = User::get()->count();

        $Users = User::SELECT("users.id", "users.name", "roles.display_name",  "users.updated_at")
               ->leftJoin('role_user',function($join) {
                
                     $join->on('users.id', '=', 'role_user.user_id');
                 })
               ->leftJoin('roles', function($join2) {

                     $join2->on('role_user.role_id', '=', 'roles.id');

                 });

       
        
        if( isset( $request->order) ){

            $tmp_column = '';

            if( isset($request->order[0]['column']) && isset($request->order[0]['dir']) && in_array($request->order[0]['dir'],['asc','desc'])  ){

                switch( $request->order ){
                    case 0 :
                        $tmp_column = "users.id";
                        break;
                    case 1 :
                        $tmp_column = "users.name";
                        break;                    
                    case 2 :
                        $tmp_column = "role_user.role_id";
                        break;                    
                    case 3 :
                        $tmp_column = "role_user.updated_at";
                        break;                    
                    default:
                        $tmp_column = "users.id";
                        break;
                }
            }

            $Users = $Users->orderBy($tmp_column, $request->order[0]['dir']);

            unset( $tmp_column );
        }

        if( isset($request->search['value']) && !empty($request->search['value']) ){

            $Users = $Users->where(function ($sub_query) use ($request) {
                $sub_query->where('users.name', 'like', "%{$request->search['value']}%")
                          ->orWhere('roles.display_name', 'like', "%{$request->search['value']}%");
            });

        }
       
        $Users = $Users->skip( $request->start )->take( $request->length );
        

        $Users = $Users->get();
        
        //var_dump( $Users );
        $data = [];
        
        foreach( $Users as $User){
            array_push($data, [
            "{$User['id']}",
            "{$User['name']}",
            "{$User['display_name']}",
            "{$User['updated_at']}",
            "<a class='btn btn-social-icon btn-twitter' href=".url("/user_edit/".$User['id']).">
            <i class='fa fa-edit'></i></a>
            <span class='btn btn-danger userDel' userId={$User['id']} title='刪除使用者'>
            <i class='fa fa-remove'></i>
            </span>"
            ]);
        }
        
        $returnData = [];

        $returnData["draw"]            = $request->draw ++;
        $returnData["recordsTotal"]    = $table_records; // 計算筆數
        $returnData["recordsFiltered"] = COUNT($data);       
        $returnData["data"]            = $data;
        
        return  json_encode($returnData);

    }



    /*
    |----------------------------------------------------------------
    | 新增使用者
    |----------------------------------------------------------------
    |
    */
    public function create_act( Request $request){
        
        $this->validate($request, [
            'name'         => 'required',
            'password'     => 'required',
            'password_chk' => 'required|same:password',
            'user_role'    => 'required|gt:0'
        ],[
            'name.required'         => '使用者帳號為必填',
            'password.required'     => '使用者密碼為必填',
            'password_chk.required' => '使用者密碼確認為必填',
            'password_chk.same'     => '使用者密碼確認與使用者密碼不相同',
            'user_role.required'    => '使用者權限為必填',
            'user_role.gt'          => '尚未選擇使用者權限'
        ]);      
        
           

        try {

            DB::beginTransaction();
    
            $user = new User();
            $user->name  = $request->name;
            $user->email = '';
            $user->password = Hash::make($request->password);
            $user->save();  

            $user->attachRole($request->user_role);
        
            DB::commit();

            $request->session()->put('successMsg', '會員新增成功' );
            return redirect()->action('userController@index');             
        
        } catch (Throwable $e) {

            DB::rollback();

        }        
    }



    /*
    |----------------------------------------------------------------
    | 編輯使用者介面
    |----------------------------------------------------------------
    |
    |
    |
    */
    public function edit( $id ){
        
        // 取出會員資料
        $_pageName  = '使用者管理';
        $_pageName2 = '編輯使用者';
        
        $now_User = User::SELECT("users.*" ,"roles.display_name","role_user.role_id as rid")
               ->leftJoin('role_user',function($join) {
                
                     $join->on('users.id', '=', 'role_user.user_id');
                 })
               ->leftJoin('roles', function($join2) {

                     $join2->on('role_user.role_id', '=', 'roles.id');

                 })
               ->where('users.id', $id)
               ->first();        
        
        // 取出可用權限
        $user_roles = Role::where('name','!=','admin')->get();                 

        return view('user.userform' , ['pageName'   => $_pageName,
                                       'pageName2'  => $_pageName2,
                                       'mode'       => 'edit',
                                       'user_roles' => $user_roles,
                                       'now_user'   => $now_User ]);                 
    }




    /*
    |----------------------------------------------------------------
    |編輯使用者
    |----------------------------------------------------------------
    |
    |
    */
    public function edit_act( Request $request ){
        

        $rule      = [
            'user_id'      => 'required|exists:users,id',
            'name'         => 'required',
            'password'     => 'required',
            'password_chk' => 'required|same:password',
            'user_role'    => 'required|gt:0'
        ];

        $error_msg = [
            'user_id.required'      => '缺少參數，無法編輯',
            'user_id.exists'        => '缺少參數，無法編輯',
            'name.required'         => '使用者帳號為必填',
            'password.required'     => '使用者密碼為必填',
            'password_chk.required' => '使用者密碼確認為必填',
            'password_chk.same'     => '使用者密碼確認與使用者密碼不相同',
            'user_role.required'    => '使用者權限為必填',
            'user_role.gt'          => '尚未選擇使用者權限'
        ];

        if( !isset( $request->modify_password ) ){
            unset( $rule['password'] );
            unset( $rule['password_chk'] );
            unset( $error_msg['password.required'] );
            unset( $error_msg['password_chk.required'] );
            unset( $error_msg['password_chk.same'] );
        }        

        $this->validate( $request, $rule, $error_msg ); 



        try {

            DB::beginTransaction();
    
            $user = User::find( $request->user_id );

            if( isset( $request->modify_password ) ){
                 
                $user->password = Hash::make($request->password);

            }

            $user->name  = $request->name;
             
            $user->save();  

            $user->detachAllRoles();

            $user->attachRole($request->user_role);
        
            DB::commit();

            $request->session()->put('successMsg', '會員編輯成功' );
            
            return redirect()->action('userController@index');             
        
        } catch (Throwable $e) {

            DB::rollback();

        }          

    }




    /*
    |----------------------------------------------------------------
    | 刪除使用者
    |----------------------------------------------------------------
    |
    */
    public function user_delete( Request $request ){
        
        // 執行結果預設為失敗
        $returnDatas['res'] = False;
        //$returnDatas['info'] = '刪除過程失誤,請稍後再試';
        // 檢查必要資訊
        if( !isset( $request->userId ) ){

            $returnDatas['info'] = '缺少必要參數，無法進行刪除';
            echo json_encode( $returnDatas );
            exit;
        }

        $now_User = User::SELECT("users.*" ,"roles.display_name","roles.id as rid")
        ->leftJoin('role_user',function($join) {
         
              $join->on('users.id', '=', 'role_user.user_id');
          })
        ->leftJoin('roles', function($join2) {

              $join2->on('role_user.role_id', '=', 'roles.id');

          })
        ->where('users.id', $request->userId)
        ->first();
        

        if( !$now_User ){

            $returnDatas['info'] = '會員不存在，無法進行刪除';
            echo json_encode( $returnDatas );
            exit;            

        }
       
        if( $now_User->rid <= 3 ){
            $returnDatas['info'] = '此會員權限無法進行刪除';
            echo json_encode( $returnDatas );
            exit;             
        }

        try{

            DB::beginTransaction();

                $now_User->detachAllRoles();

                $now_User->delete();

            DB::commit();
            
            $returnDatas['res']  = True;
            $returnDatas['info'] = '刪除完成';

            echo json_encode($returnDatas);

        }catch(\Exception $e){
            
            //$e->getMessage();

            // 寫入錯誤代碼後轉跳
            //logger("in {$this->currentController} :\n {$e->getMessage()}");

            $returnDatas['res'] = False;
            $returnDatas['info'] = '刪除過程失誤,請稍後再試';

            echo json_encode($returnDatas);

        }          

    }
}
