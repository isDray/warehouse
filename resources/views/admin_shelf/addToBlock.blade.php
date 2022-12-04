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
            <form role="form" action="{{url('admin_shelf_blockDo')}}" method="POST" id='createform'>
              @csrf
              <div class="box-body">
                
                <!-- 選擇START -->
                <div class="form-group">
                  <label>選擇區塊</label>
                  <select class="form-control" name='block' id='block'>
                    @foreach ($blockArr as $layer=>$blockArrvs)
                        @foreach ($blockArrvs as $blockArrv)
                        <option value="{{$blockArrv['id']}}">第{{$layer}}層 , 第{{$blockArrv['block_num']}}區塊</option>
                        @endforeach
                    @endforeach
                  </select>
                </div>
                <!-- 選擇區塊END -->
                <div class="form-group">
                  <label for="name">存入貨號</label>
                  <input type="text" class="form-control" id="goods_sn" name="goods_sn" placeholder="請輸入要存入區塊之貨號 , 如果有多個貨號請用逗號(,)隔開">
                </div>                
                
                <!-- 移除商品 -->
                <div class="form-group">
                  <label for="name">刪減貨號</label>
                  <div class="input-group">
                        <span class="input-group-addon">
                          <input type="checkbox" name='checkDelet'>
                        </span>
                    <input type="text" class="form-control" id='goods_sn_rm' name="goods_sn_rm" placeholder="請輸入要刪減之貨號 , 如果有多個貨號請用逗號(,)隔開">
                  </div>
                  <!-- /input-group -->
                </div>

              </div>
              <!-- /.box-body -->
              <input type="hidden" class="form-control" id="id" name="id"  value="">
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
          if ( term.length < 3 ) {
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

      // 刪減區塊

    $( "#goods_sn_rm" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {

        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
          $.getJSON( "{{url('/autoGetBlockData')}}", {
            term: extractLast( request.term ),
            block:$("#block").val()
          }, response );
        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 3 ) {
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
@endsection