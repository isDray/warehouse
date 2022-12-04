@extends('layouts.admin')

@section('content')
<style type="text/css">
    .buttonArea{
        margin-bottom: 10px;
    }
    .fixed-header{
        position: fixed;
        top:0px;
        z-index: 999;
        right: 0px;
    }
    #searchBox{
        padding-top: 5px!important;
        padding-bottom: 5px!important;
    }
</style>

<section class="content">
    
    <div class="row">
        <div class="col-xs-12" >
                    <div class="box box-primary" id='searchBox' data-widget="collapse">
                        <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Collapse">
                        <h3 class="box-title">請選擇貨架</h3>
                        
                        <div class="pull-right box-tools">
                            <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Collapse">
                        <i class="fa fa-minus"></i></button>
                        </div>

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
            <div class="box box-default">
                <!--
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                -->

                <!-- box-body Start -->
                <div class='box-body'>
                    <div class="col-md-12">
                    
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                    
                    <ul class="nav nav-tabs">
                        @foreach ($whareHouses as $key=>$whareHouse)
                            <li class="@if($key==0) active @endif"><a href="#tab_{{$key}}" data-toggle="tab" aria-expanded="@if($key==0) true @else false @endif">{{$whareHouse->name}}</a></li>
                        @endforeach
                        <!--
                        <li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">Tab 1</a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Tab 2</a></li>
                        <li class="active"><a href="#tab_3" data-toggle="tab" aria-expanded="true">Tab 3</a></li>
                        -->
                    
                    </ul>

                    <!-- tab-content Start -->
                    <div class="tab-content col-md-12">

                        @foreach ($blockArrs as $key=>$blockArr)
                        <div class="tab-pane @if($key == 0) active @endif" id="tab_{{$key}}">
                            
                            @foreach ($blockArr as $shelfk=>$shelf)
                            <div class="tab-body col-md-12">
                                <!-- Widget: user widget style 1 -->
                                <div class="box box-widget widget-user-2  style-1" id="{{$shelfk}}">
                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="widget-user-header bg-orange">
                                        <p align='center'>{{$shelfk}}</p>
                                    </div>
                                
                                    <div class="box-footer no-padding">
                                    
                                        <ul class="nav nav-stacked">
                                            @foreach($shelf as $layerk => $layer)
                                            <li style='display:flex'>
                                                @foreach($layer as $blockk => $block)
                                                
                                                
                                                <div  @if(count($block)==1)class='blockBox blockBox2'@else class='blockBox blockBox1' @endif shelf="{{$shelfk}}" layerNum="{{$layerk}}" blockNum="{{$blockk}}" blockId="{{$block['blockId']}}" id="{{$block['blockId']}}">
                                                    
                                                    <h4 style="margin-bottom:0px;">{{$shelfk}}-{{$layerk}}-{{$blockk}}</h4>
                                                    
                                                    @foreach($block as $goodsk=>$goodsSn)
                                                    
                                                    @if( $goodsk !== 'blockId')

                                                       <p style="margin-bottom:0px;color:#75716b;" goodsSn="{{$goodsSn}}">{{$goodsSn}}</p> 

                                                    @endif
                                                        
                                                    @endforeach
                                                
                                                </div>


                                                @endforeach
                                            </li>
                                            @endforeach
                                            <!--
                                            <li style='display:flex'><div class='fx'>a</div><div class='fx'>a</div><div class='fx'>a</div><div class='fx'>a</div></li>
                                            <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>
                                            <li><a href="#">Completed Projects <span class="pull-right badge bg-green">12</span></a></li>
                                            <li><a href="#">Followers <span class="pull-right badge bg-red">842</span></a></li>
                                            -->

                                        </ul>
                                    </div>
                                </div>

                            </div>
                            @endforeach
                        </div>
                        @endforeach

                    </div>
                    <!-- tab-content End -->
                    
                    </div>
                    <!-- Custom Tabs  End-->

                    </div>
                    <!-- 12格 -->

                    </div>
                    <!-- box-body Start -->
            </div>

        </div>

    </div>
</section>
<!-- /.content -->
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
</style>
<script type="text/javascript">
/*------------------------------------------------------------
 | 修改
 |
 |
 */

//頁面載入
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
            /*
            if(allGoodsSnk !=0 ){

               defaultVal += ',';
            }
            defaultVal += $(this).attr('goodsSn');*/
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
                html: inputHtml,
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: '編輯',
                cancelButtonText: '取消',
                //inputValue:defaultVal
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

                            nowBlock.css('background-color','#dedddb');
                            // 迴圈新增開始
                            $.each(msg['Datas'], function( index, value ) {
                                
                                
                                nowBlock.append('<p style="margin-bottom:0px;color:#75716b;" goodsSn="'+value+'" >'+value+'</p>');
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


    // 錨點轉跳
    $(".shelfBtn").click(function(){
        
        //var scrollPosition = window.style.scrollTop;
        //window.location.hash = "#"+$("#wantTo").val(); 
        //window.style.scrollTop = scrollPosition;
        if( $("#searchBox").position()['left'] == 15){

            var addHeight = $("#searchBox").height() 
            addHeight =  addHeight + 200 ;
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#"+$(this).attr('shelfCode')).offset().top - addHeight 
            }, 500);

        }else{

            $([document.documentElement, document.body]).animate({
                scrollTop: $("#"+$(this).attr('shelfCode')).offset().top - 200
            }, 500);

        }
        //console.log($(this).attr('shelfCode'));
        

    
        $("#searchBox").addClass('collapsed-box');
    });


    $(window).scroll(function(){
    if ($(window).scrollTop() >= 300) {

        $("#searchBox").addClass('fixed-header');

    }
    else {
         
        $("#searchBox").removeClass('fixed-header');
    }
    });

});
</script>
@endsection