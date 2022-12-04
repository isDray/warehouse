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
            <form role="form" action="{{url('admin_shelf_editDo')}}" method="POST" id='createform'>
              @csrf
              <div class="box-body">
               
                <!-- 貨架結構START  -->
                <div class="box box-default">
                    <div class="box-header with-border">
                    <h3 class="box-title">貨架結構</h3>
                    </div>
                    <div class="box-body" >
                        
                        @foreach ($blockArr as $blockArrv)
                        <div class='row blockRow' style='display:flex;'>
                            @foreach ($blockArrv as $blockArrv2)
                            <div class='blockBox'>
                              <input type='radio'>{{$blockArrv2}}
                            </div>                            
                            @endforeach
                        </div>
                        @endforeach                      
                                                                               
                    </div>
                </div>    
                <!-- 貨架結構END -->

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
              <input type="hidden" class="form-control" id="id" name="id"  value="{{$Shelf->id}}">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">修改</button>
              </div>
            </form>
          </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<style type="text/css">
    .blockBox{
      flex:1;
      height: 40px;
      background-color: #f39c12;
      border: 1px solid black;
      float:left;
      border-radius: 5px;
    }
    .blockRow{
      margin-top: 10px;
    }
</style>
@endsection