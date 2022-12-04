@extends('layouts.admin')

@section('content')
<style type="text/css">
    .buttonArea{
        margin-bottom: 10px;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-xs-12" >
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <!-- /.box-header -->
                
                <!-- form start -->
                <form role="form" action="@if($mode=='create'){{url('user_create_act')}}@else{{url('user_edit_act')}}@endif" method="POST" id='createform'>
                    @csrf

                    @if($mode=='edit')
                    <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{$now_user->id}}">
                    @endif
                    <div class="box-body ">

                        <div class="form-group @if($errors->has('user_role')) has-error @endif">
                            <label>使用者權限</label>
                            
                            <select class="form-control" name='user_role'>
                                <option value='0'>請選擇權限</option>
                                @foreach ($user_roles as $user_role)                  
                                <option value="{{$user_role->id}}"  @if(old('user_role')) 
                                                                        @if( $user_role->id == old('user_role') ) SELECTED @endif 
                                                                    @else 
                                                                        @if($mode=='edit')
                                                                            @if( $user_role->id == $now_user->rid) SELECTED @endif 
                                                                        @endif
                                                                    @endif>
                                                                    
                                                                    {{$user_role->display_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('user_role'))
                            <span class="help-block">{{$errors->first('user_role')}}</span>
                            @endif
                            
                        </div>

                        <div class="form-group @if($errors->has('name')) has-error @endif">
                            <label for="name">使用者帳號</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="請輸入使用者帳號" value="@if(old('name')){{old('name')}}@elseif(!empty($now_user)){{$now_user->name}}@endif">
                            @if ($errors->has('name'))
                            <span class="help-block">{{$errors->first('name')}}</span>
                            @endif                                 
                        </div>
                        
                        @if($mode=='edit')
                        <div class="form-group">
                            <label for="modify_password">修改密碼</label>
                            <input type="checkbox" class="minimal" id="modify_password" name='modify_password'>
                        </div>
                        @endif

                        <div class="form-group @if($errors->has('password')) has-error @endif">
                            <label for="password">使用者密碼</label>
                            <input type="password" class="form-control" id="password" placeholder="請輸入使用者密碼" name='password'  value="@if(old('password')){{old('password')}}@endif" @if($mode=='edit') disabled @endif >
                            @if ($errors->has('password'))
                            <span class="help-block">{{$errors->first('password')}}</span>
                            @endif                               
                        </div>

                        <div class="form-group @if($errors->has('password_chk')) has-error @endif">
                            <label for="password_chk">使用者密碼確認</label>
                            <input type="password" class="form-control" id="password_chk" placeholder="請再次輸入使用者密碼" name='password_chk'  value="@if(old('password_chk')){{old('password_chk')}}@endif" @if($mode=='edit') disabled @endif>
                            @if ($errors->has('password_chk'))
                            <span class="help-block">{{$errors->first('password_chk')}}</span>
                            @endif                            
                        </div>      

                    </div>
                    <!-- /.box-body -->
                    
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">確認</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
<script>
$(function(){
    $("#modify_password").change(function(){
        if( $(this).is(":checked") ){
            $("#password").removeAttr('disabled');
            $("#password_chk").removeAttr('disabled');
        }else{
            $("#password").attr("disabled","disabled");
            $("#password_chk").attr('disabled',"disabled");
        }
    })
})
</script>
@endsection