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
                <div class="form-group">
                  <label for="name">貨架名稱</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="請輸入倉庫名稱" value="{{$Shelf->name}}">
                </div>

                <div class="form-group">
                  <label for="code">貨架編碼</label>
                  <input type="text" class="form-control" id="code" placeholder="請輸入倉庫編碼" name='code' value="{{$Shelf->code}}">
                </div>

                <div class="form-group">
                  <label>貨架備註</label>
                  <textarea class="form-control" rows="3" placeholder="請輸入倉庫相關備註" name='note'>{{$Shelf->note}}</textarea>
                </div>

                <div class="form-group">
                  <label>所屬倉庫</label>
                  <select class="form-control" name='wharehouse'>
                  <option value='0'>請選擇倉庫</option>
                  @foreach ($wharehouses as $wharehouse)                  
                  <option value="{{$wharehouse->id}}" @if( $Shelf->wharehouse_id == $wharehouse->id) SELECTED @endif>{{$wharehouse->name}}</option>
                  @endforeach
                  </select>
                </div>                
                <!-- 貨架結構START  -->
                <div class="box box-default">
                    <div class="box-header with-border">
                    <h3 class="box-title">貨架結構</h3>
                    </div>
                    <div class="box-body " id='shelfStruct'>
                        
                        @foreach ($blockArr as $blockArrk => $blockArrv)
                        <div class='row blockRow col-md-11' style='display:flex;'>
                            @foreach ($blockArrv as $blockArrv2)
                            <div class='blockBox'>
                              <span class='btn btn-danger fa-remove fa circlebtn blockBoxDel' layerNum="{{$blockArrk}}" blockNum="{{$blockArrv2}}">
                                
                              </span>
                              {{$blockArrk}}-{{$blockArrv2}}
                            </div>                            
                            @endforeach
                        </div>
                        <div class='col-md-1' style='margin-top:10px;'>
                            
                            <span class='btn btn-block btn-primary addBlock' style='padding:0px;height:40px;line-height:40px;' layerNum="{{$blockArrk}}">第{{$blockArrk}}層,新增區塊</span>
                            
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
      background-color: #e4d4bb;
      border: 2px solid #b3a794;
      float:left;
      border-radius: 5px;
      position: relative;
      text-align: center;
      font-size: 16px;
      font-weight: 900;
      line-height: 40px;
      color:#75716b;
    }
    .blockRow{
      margin-top: 10px;
    }
    .circlebtn{
      border-radius: 50%;
      padding: 0px;
      width: 18px;
      height: 18px;
      position: absolute;
      top:2px;
      right: 2px;
    }
</style>

<script type="text/javascript">

// 當頁面載入完成時

$(function(){
    
    /*----------------------------------------------------------------------------------------------------
     | 貨架區塊刪除
     |----------------------------------------------------------------------------------------------------
     | 點擊 x 按鈕時 , 經由ajax發起刪除的請求 , 並且根據不同的回應結果呈現提示訊息 , 以及動態改變
     | 貨架結構呈現圖
     |
     |
     */

    $('body').on('click', '.blockBoxDel', function() {
        
        // 取得層數以及區塊號
        var layerNum = $(this).attr('layerNum');
        var blockNum = $(this).attr('blockNum');
        
        // 確認視窗 
        Swal.fire({

            title: '移除確認',
            html: "即將刪除"+$(this).attr('layerNum')+"-"+$(this).attr('blockNum')+"區塊一經移除後將無法恢復,確定要移除?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '確認',
            cancelButtonText: '取消'

        }).then((result) => {
            
            // 如果操作人員選擇確定刪除則開始發出請求
            if (result.value) {

                // Ajax start
                var request = $.ajax({
                  
                    url: "{{url('/admin_shelf_rmBlock')}}",
                    method: "POST",
                    data: { shelfId : "{{$Shelf->id}}",
                            layerNum : layerNum,
                            blockNum : blockNum,
                            _token: "{{ csrf_token() }}",
                    },
                    
                    dataType: "JSON"
            
                });
 
                request.done(function( msg ) {

                    if( msg['res'] != false ){

                        Swal.fire({
                            type: 'success',
                            title:'操作成功',
                            text: '區塊已成功移除',
                        });
                      
                        $("#shelfStruct").empty();
                      
                        //重組或架結構呈現圖
                        $.each( msg, function( key, value ) {
                          
                            if(key=='res'){

                                return;
                            }

                            tmpdata = '';

                            $.each( value, function( key2, value2 ) {
                              
                                tmpdata += "<div class='blockBox'>"+
                                "<span class='btn btn-danger fa-remove fa circlebtn blockBoxDel' layerNum='"+key+"' blockNum='"+value2+"'></span>"+
                                +key+"-"+value2+
                                "</div>"; 

                            });
                            
                            tmpBtnData = '';
                            
                            tmpBtnData += "<div class='col-md-1' style='margin-top:10px;'>"+
                                          "<span class='btn btn-block btn-primary addBlock' style='padding:0px;height:40px;line-height:40px;' layerNum="+key+">第"+key+"層,新增區塊</span>"+
                                          "</div>";

                            $("#shelfStruct").append("<div class='row blockRow col-md-11' style='display:flex;'>"+tmpdata+"</div>"+tmpBtnData); 
                          
 
                        });

                    }else{
                        
                        Swal.fire({
                          type: 'error',
                          title:'操作失敗',
                          text: msg['info'],
                        });
                    }

                });
 
                request.fail(function( jqXHR, textStatus ) {
                  //alert( "Request failed: " + textStatus );
                });
                // Ajax End              
            
            }
        });

    });

    /*-----------------
    */
    
    $('body').on('click', '.addBlock', function() {
        
        // 選擇的層數
        var layerNum = $(this).attr('layerNum');
        
        // Ajax 新增指定層數區塊 Start
        var request = $.ajax({
            url: "{{url('/admin_shelf_addBlock')}}",
            method: "POST",
            data: { layerNum : layerNum ,
                    _token: "{{ csrf_token() }}",
                    shelfId : "{{$Shelf->id}}"
            },
            dataType: "JSON"
        });
 
        request.done(function( msg ) {
            
            if( msg['res'] == true ){

                Swal.fire({
                
                    type: 'success',
                    title:'操作成功',
                    text: '已新增一區塊',

                });
                      
                $("#shelfStruct").empty();
                      
                //重組或架結構呈現圖
                $.each( msg, function( key, value ) {
                          
                    if(key=='res'){

                        return;
                    }

                    tmpdata = '';

                    $.each( value, function( key2, value2 ) {
                              
                        tmpdata += "<div class='blockBox'>"+
                        "<span class='btn btn-danger fa-remove fa circlebtn blockBoxDel' layerNum='"+key+"' blockNum='"+value2+"'></span>"+
                        +key+"-"+value2+
                        "</div>"; 

                    });
                    tmpBtnData = '';
                    tmpBtnData += "<div class='col-md-1' style='margin-top:10px;'>"+
                                  "<span class='btn btn-block btn-primary addBlock' style='padding:0px;height:40px;line-height:40px;' layerNum="+key+">第"+key+"層,新增區塊</span>"+
                                  "</div>";
                    $("#shelfStruct").append("<div class='row blockRow col-md-11' style='display:flex;'>"+tmpdata+"</div>"+tmpBtnData); 
                          
 
                });

            }else{
                
                Swal.fire({
                    type: 'error',
                    title:'操作失敗',
                    text: msg['info'],
                });
            }

        });
 
        request.fail(function( jqXHR, textStatus ) {
            //alert( "Request failed: " + textStatus );
        });

        // Ajax 新增指定層數區塊 End

    });
});

</script>
@endsection