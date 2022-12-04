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
        <!-- /.box-header -->
        <!-- form start -->

          <div class="box-body" id='resultmain'>

          </div>
          <div class="box-footer text-center" >

          </div>

      </div>

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
    $.each( msg['exist'],function(index,element){
        //console.log(index);
        tmpli = '';
        $.each(element,function(index2,element2){

            //tmpli += '<p>貨架'+element2['shelf']+'第'+element2['layer_num']+'層,第'+element2['block_num']+'區塊'++'</p>';
           
            tmpli += '<tr>'+
                     '<td>'+element2['shelf']+'-'+element2['layer_num']+'-'+element2['block_num']+'</td>'+
                     '<td>'+element2['updated_at']+'</td></tr>';
                
        });
        $("#resultmain").append(
        '<div class="col-md-3 col-md-offset-2 col-sm-12 col-xs-12 "><div class="panel panel panel-info findResBox">'+
        '<div class="panel-heading">貨號'+index+'</div>'+
        '<div class="panel-body">'+
        '<table class="table table-hover">'+
        '<tbody><tr>'+
        '<th>貨架區塊</th>'+
        '<th>入倉時間</th></tr>'+
        tmpli+
        '</tbody></table></div>'+
        '</div></div>');
    });
    
    if( typeof msg['noexist'] !== 'undefined'){
        tmpli = '';
        $.each( msg['noexist'],function(index,element){
          tmpli += '<tr><td>'+element+'<td><tr>';
        });      
        $("#resultmain").append(
        '<div class="panel panel panel-danger">'+
        '<div class="panel-heading">不存在之貨號</div>'+
        '<div class="panel-body">'+
        '<table class="table table-hover">'+
        '<tbody>'+
        tmpli+ 
        '</tbody></table></div>'+
        '</div>');        
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
@endsection