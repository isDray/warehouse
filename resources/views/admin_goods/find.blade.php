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
        <!-- 頁籤區塊 -->
        <div class="nav-tabs-custom">
            
            <!-- 頁簽選單 -->
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">貨號搜尋</a></li>
              <li><a href="#tab_2" data-toggle="tab">貨架搜尋</a></li>
            </ul>
            <!-- /頁籤選單 -->
            
            <!-- 頁籤內容區塊 -->
            <div class="tab-content">
                
                <!-- 搜尋貨號 -->
                <div class="tab-pane active" id="tab_1">
                    
                    <div class="box box-primary">
                        <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                        </div>

                        <form role="form" action="" method="POST" id='createform' onsubmit="return false">
                            <div class="box-body">
                                <div class='col-md-4 col-md-offset-4'>
                
                                    <div class="form-group">
                                    <label for="name"></label>
                                    <input type="text" class="form-control" id="goods_sn" name="goods_sn" placeholder="請輸入要查詢之貨號" value="">
                                    </div>
                                
                                </div>
                            </div>
                            
                            <div class="box-footer text-center" >
                                <button type="submit" class="btn btn-primary" id='searchbtn'>搜尋</button>
                            </div>
                        </form>
                    </div>

                    <div class="box box-primary" id='resultbox'>
                        
                        <div class="box-header with-border" >
                        <h3 class="box-title" id='resulttitle'>查詢結果</h3>
                        </div>

                        <div class="box-body" id='resultmain'>

                        </div>
                        
                        <div class="box-footer text-center" ></div>

                    </div>

                </div>
                <!-- /搜尋貨號 -->

                <!-- 搜尋貨架 -->
                <div class="tab-pane " id="tab_2">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                        <h3 class="box-title">請選擇貨架</h3>
                        </div>
                     

                        <div class="box-body">
                            
                            @foreach($shelfs as $shelfk=>$shelf)
                            <div class='col-md-1 col-sm-2 col-xs-2'>
                                <span class='btn btn-primary btn-block shelfBtn' shelfId="{{$shelf->id}}" shelfCode="{{$shelf->code}}">{{$shelf->code}}</span>
                            </div>
                            @endforeach
                        </div>
                            
                        <div class="box-footer text-center" >
                        </div>
                
                    </div>

                    <div class="box box-primary" id='shelfBox'>
                        
                        <div class="box-header with-border" >
                        <h3 class="box-title" id='resulttitle'>查詢結果</h3>
                        </div>

                        <div class="box-body" id='shelfBoxMain'>

                        </div>
                        
                        <div class="box-footer text-center" ></div>

                    </div>

                </div>
                <!-- /搜尋貨架 -->

            </div>
            <!-- /頁籤內容區塊 -->
        </div>      
      <!-- 頁籤區塊 END-->







    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<script type="text/javascript">

$(function(){
    
    $("#searchbtn").click(function(){
        //"_token": "{{ csrf_token() }}",
        var goodsSnVal = $("#goods_sn").val();
        var request = $.ajax({
            url: "{{url('/admin_goods_findDo')}}"+'/'+goodsSnVal,
            method: "POST",
            data: { _token:"{{ csrf_token() }}" },
            dataType: "json"
        });
 
request.done(function( msg ) {
    console.log(  msg['noexist']);
    $("#resultmain").empty();
    
    tmpli = '';
    $.each( msg['exist'],function(index,element){
        //console.log(index);
        //tmpli = '';
        $.each(element,function(index2,element2){
       
            //tmpli += '<p>貨架'+element2['shelf']+'第'+element2['layer_num']+'層,第'+element2['block_num']+'區塊'++'</p>';
            if( index2 == 0){

                tmpli += '<tr>'+
                         '<td class="bg-blue">'+element2['shelf']+'-'+element2['layer_num']+'-'+element2['block_num']+'</td><td class="bg-blue" >'+index+'</td>'+
                         '<td class="bg-blue">'+element2['updated_at']+'</td></tr>';

            }else{

                tmpli += '<tr>'+
                         '<td>'+element2['shelf']+'-'+element2['layer_num']+'-'+element2['block_num']+'</td><td>'+index+'</td>'+
                         '<td>'+element2['updated_at']+'</td></tr>';              
            }

                
        });

    });
    $("#resultmain").append('<div class="col-md-4 col-md-offset-4 col-xs-12 col-md-12"><table class="table table-bordered">'+
    '<tbody><tr><th>位置</th><th>貨號</th><th>入倉時間</th></tr>'+
    tmpli+
    '</tbody>'+
    '</table></div>');

    if( typeof msg['noexist'] !== 'undefined'){
        tmpli = '';
        $.each( msg['noexist'],function(index,element){
          tmpli += '<tr><td class="bg-red">'+element+'</td></tr>';
        });      
        /*$("#resultmain").append(
        '<div class="panel panel panel-danger">'+
        '<div class="panel-heading">不存在之貨號</div>'+
        '<div class="panel-body">'+
        '<table class="table table-hover">'+
        '<tbody>'+
        tmpli+ 
        '</tbody></table></div>'+
        '</div>'); */
        $("#resultmain").append('<div class="col-md-4 col-md-offset-4 col-xs-12 col-md-12"><table class="table table-bordered">'+
            '<tbody><tr><th>不存在之貨號貨號</th></tr>'+
            tmpli+
            '</tbody>'+
         '</table></div>');
    }

});
 
request.fail(function( jqXHR, textStatus ) {
  console.log( /*"Request failed: " + textStatus*/'r' );
});        

    });
});

</script>

<!-- 自動完成 -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
  $( function() {
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#goods_sn" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {

        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
          $.getJSON( "{{url('/autoGetData')}}", {
            term: extractLast( request.term )
          }, response );
        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 4 ) {
            return false;
          }
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  } );
  </script>

<style type="text/css">
.findResBox{
  height: 240px;
  overflow-y:auto;
}
</style>

<!-- 貨架js區塊 -->
<script type="text/javascript">
    
    $(function(){
        
        // 貨架按鈕點擊時啟動
        $(".shelfBtn").click(function(){
            
            $("#shelfBoxMain").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            
            var shelfId   = $(this).attr('shelfId');
            var shelfCode = $(this).attr('shelfCode');
            
            // ajax 撈取貨架
            var request = $.ajax({
                url: "{{url('/admin_self_getShelf')}}",
                method: "POST",
                data: { shelfId:shelfId,
                        _token:"{{ csrf_token() }}"
                },
                dataType: "JSON"
            });
 
            request.done(function( msg ) {
                $(".overlay").remove();
                
                if( msg['res'] === true){
                    
                    // 清空區塊 
                    $("#shelfBoxMain").empty();
                    //console.log(msg['Datas']);
                    
                    mergeCode = '';
                    $.each(msg['Datas'], function( index, layer ) {
                        
                        mergeCode +='<li style="display:flex">';

                        $.each(layer, function( blockNum, block ) {
 
                            if( Object.entries(block).length == 1){

                                mergeCode +='<div class="blockBox blockBox2" shelf="'+shelfCode+'" layerNum="'+index+'" blockNum="'+blockNum+'" blockId="'+block['blockId']+'" id="'+block['blockId']+'">';
                                mergeCode +='<h4 style="margin-bottom:0px;">'+shelfCode+'-'+index+'-'+blockNum+'</h4>';
                                $.each(block, function( goodsk, goosSn ) {
                                    if( goodsk != 'blockId'){

                                        mergeCode += '<p style="margin-bottom:0px;color:#75716b;" goodsSn="'+goosSn+'">'+goosSn+'</p>';
                                    }
                                });
                                mergeCode += '</div>';

                            }else{

                                mergeCode +='<div class="blockBox blockBox1" shelf="'+shelfCode+'" layerNum="'+index+'" blockNum="'+blockNum+'" blockId="'+block['blockId']+'" id="'+block['blockId']+'">';
                                mergeCode +='<h4 style="margin-bottom:0px;">'+shelfCode+'-'+index+'-'+blockNum+'</h4>';
                                $.each(block, function( goodsk, goosSn ) {
                                    if( goodsk != 'blockId'){

                                        mergeCode += '<p style="margin-bottom:0px;color:#75716b;" goodsSn="'+goosSn+'">'+goosSn+'</p>';
                                    }
                                });
                                mergeCode += '</div>';
                            }
                
                        });

                        mergeCode +='</li>';

                    });
                    
                    $("#shelfBoxMain").append('<div class="box box-widget widget-user-2 style-1">'+
                        
                    '<div class="widget-user-header bg-orange">'+
                    '<p align="center">'+shelfCode+'</p>'+
                    '</div>'+'<div class="box-footer no-padding"><ul class="nav nav-stacked">'+
                    mergeCode+
                    '</ul></div>'+
                    '</div>');

                }else{

                }

            });
 
            request.fail(function( jqXHR, textStatus ) {
                $(".overlay").remove();
               
            });            
            // ajax 撈取貨架 END
        });

    });

</script>

<script type="text/javascript">
    $(function(){
    $('body').on('click', '.blockBox', function() {

        var shelf    = $(this).attr('shelf');
        var layerNum = $(this).attr('layerNum');        
        var blockNum = $(this).attr('blockNum');
        var blockId  = $(this).attr('blockId');
        var nowBlock = $( this );

        // 取出區塊內所有貨號
        var allGoodsSn = $(this).find( "p" );
        defaultVal = '';
        
        // 需要補足的數量
        var needAdd = 5 - allGoodsSn.length ;
        
        // 要產生的輸入框
        var inputHtml = '';

        $.each(allGoodsSn, function( allGoodsSnk, allGoodsSnv ) {
            
            /*if(allGoodsSnk !=0 ){

               defaultVal += ',';
            }
            defaultVal += $(this).attr('goodsSn');*/
            //<input class="form-control input-lg" type="text" placeholder=".input-lg">
            inputHtml += '<input class="form-control input-lg" type="text" placeholder=""  style="font-weight:900;" value="'+$(this).attr('goodsSn')+'" name="editSn[]"><br>';
        });
        
        // 如果尚未達到五個,則將輸入框補足至五個
        if( needAdd > 0 ){

            for( i= 0 ;i<needAdd; i++){

               inputHtml += '<input class="form-control input-lg" type="text" placeholder="" style="font-weight:900;" value="" name="editSn[]"><br>';

            }

        }
        // 輸入框跳出

            Swal.fire({
                title: '編輯貨號到'+shelf+'-'+layerNum+'-'+blockNum,
//              input: 'text',
                html: inputHtml,
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: '編輯',
                cancelButtonText: '取消',
            }).then((result) => {
                
                var sendData = '';

                $("input[name='editSn[]']").each(function() {
                    
                    if( !$(this).val() ){

                        return;

                    }else{
                        
                        if( sendData !== '' ){
                            
                            sendData += ','+$(this).val();
                        
                        }else{
                            
                            sendData += $(this).val();
                        }

                    }
                    
                });
                
                if ( typeof(result.value) !== 'undefined' ) {
                    
            
                    // ajax Start
                    var request = $.ajax({
                        url: "{{url('/admin_shelf_ajaxBlockAddGoods')}}",
                        method: "POST",
                        data: { _token: "{{ csrf_token() }}",
                                blockId:blockId,
                                goodsSns:sendData,//result.value,
                        },
                        dataType: "JSON" 
                    });
 
                    request.done(function( msg ) {

 
                        if( msg['res'] === true){

                            Swal.fire({
                            
                                type: 'success',
                                title: '執行成功',
                                html: msg['info'],
                            })

                            // 執行成功後重新生成畫面
                            nowBlock.children('p').remove();
                            
                            // 迴圈新增開始
                            nowBlock.css('background-color','#dedddb');
                            $.each(msg['Datas'], function( index, value ) {
                                
                                
                                nowBlock.append('<p style="margin-bottom:0px;color:#75716b;" goodsSn="'+value+'">'+value+'</p>');
                                nowBlock.css('background-color','#fbb64a');
                            });
                            // 迴圈新增結束
                        }

                        if( msg['res'] === false ){

                            Swal.fire({
                            
                                type: 'error',
                                title: '執行錯誤',
                                html: msg['info'],

                            })
                            
                        }

                    });
 
                    request.fail(function( jqXHR, textStatus ) {
                        
                    //alert( "Request failed: " + textStatus );
                    });
                    // ajax End 
                    
                }
            })
        // 輸入框跳出結束
    });
    });
</script>
<!-- /貨架js區塊 -->

<!-- 貨架 css區塊 -->
<style type="text/css">
    .blockBox{
      flex:1;
      /*#e4d4bb*/
      border: 2px solid #b3a794;
      float:left;
      border-radius: 5px;
      position: relative;
      text-align: center;
      font-size: 16px;
      font-weight: 900;
      color:#463a29;
      min-height: 60px;
      height: auto;
      cursor: pointer;
      overflow-x: hidden;
    }
    .blockBox::-webkit-scrollbar-track{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    background-color: #F5F5F5;
    }

    .blockBox::-webkit-scrollbar{
    width: 6px;
    background-color: #F5F5F5;
    }

    .blockBox::-webkit-scrollbar-thumb{
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
    }    

    .blockBox1{
      background-color: #fbb64a;
    }
    .blockBox2{
      background-color: #dedddb;
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
    .style-1{
        height: auto;
        overflow-y: auto;
    }
    .style-1::-webkit-scrollbar-track{
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }
    .style-1::-webkit-scrollbar{
        width: 6px;
        background-color: #F5F5F5;
    }

    .style-1::-webkit-scrollbar-thumb{
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #555;
    }
    .widget-user-header{
        padding: 0px!important;
    }
    .widget-user-header>p{
        margin-bottom: 0px!important;
        font-size: 20px;
        font-weight: 900;
    }

    .swal2-input{
        font-size: 24px!important;
        font-weight: 900;
    }
</style>
<!-- /貨價 js區塊-->
@endsection