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
        <form role="form" action="{{url('/shelf_import_act')}}" method="POST" id='createform' enctype="multipart/form-data" >
          @csrf
          <div class="box-body">
            <div class='col-md-4 col-md-offset-4'>
                
                <div class="form-group">
                
                    <label for="importGoods">上傳檔案</label>
                    <input type="file" id="importShelfData" name="importShelfData">
                    <p class="help-block">選擇匯入貨架Excel檔案</p>

                </div>

            </div>
          </div>
          <div class="box-footer text-center" >
            <button type="submit" class="btn btn-primary" id='searchbtn'>匯入</button>
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