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
            <form role="form" action="{{url('admin_whare_house_create_do')}}" method="POST" id='createform'>
              @csrf
              <div class="box-body">
                <div class="form-group">
                  <label for="name">倉庫名稱</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="請輸入倉庫名稱">
                </div>

                <div class="form-group">
                  <label for="code">倉庫編碼</label>
                  <input type="text" class="form-control" id="code" placeholder="請輸入倉庫編碼" name='code'>
                </div>

                <div class="form-group">
                  <label>倉庫備註</label>
                  <textarea class="form-control" rows="3" placeholder="請輸入倉庫相關備註" name='note'></textarea>
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


@endsection