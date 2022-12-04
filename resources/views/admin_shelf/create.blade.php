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
            <form role="form" action="{{url('admin_shelf_createDo')}}" method="POST" id='createform'>
              @csrf
              <div class="box-body">
                <div class="form-group">
                  <label for="name">貨架名稱</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="請輸入貨架名稱">
                </div>

                <div class="form-group">
                  <label for="code">貨架編碼</label>
                  <input type="text" class="form-control" id="code" placeholder="請輸入貨架編碼" name='code'>
                </div>

                <div class="form-group">
                  <label>貨架備註</label>
                  <textarea class="form-control" rows="3" placeholder="請輸入貨架相關備註" name='note'></textarea>
                </div>  
                <div class="form-group">
                  <label>所屬倉庫</label>
                  <select class="form-control" name='wharehouse'>
                  <option value='0'>請選擇倉庫</option>
                  @foreach ($wharehouses as $wharehouse)                  
                  <option value="{{$wharehouse->id}}" >{{$wharehouse->name}}</option>
                  @endforeach
                  </select>
                </div>        
                <div class="form-group">
                
                <label>貨架規格</label>

                <span class="btn btn-default btn-flat" onclick="newFloor();">新增</span>

                </div>              

                <div class="input-group floor">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-danger">第1層</button>
                    </div>
                    <!-- /btn-group -->
                    <input type="text" class="form-control" name='floor[]'>

                </div>

                <!--
                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status" value="1" checked>
                      啟用
                    </label>
                    <label>
                      <input type="radio" name="status" id="status" value="0">
                      停用
                    </label>                    
                  </div>
                </div>
                -->
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">新增</button>
              </div>
            </form>
          </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<script type="text/javascript">

function newFloor(){

    var floor = $(".floor").length + 1;
    var lastfloor = $(".floor").last();
    lastfloor.after('<div class="input-group floor"><div class="input-group-btn"><button type="button" class="btn btn-danger">第'+floor+'層</button></div><input type="text" class="form-control" name="floor[]"></div>');
}


</script>

@endsection