@extends('layouts.admin')

@section('content')
<style type="text/css">
    .buttonArea{
        margin-bottom: 10px;
    }
</style>
<!-- 新增按鈕區塊 -->
<div class='col-md-1 col-md-offset-11 col-sm-12 col-xs-12 text-center buttonArea'>
    <a href="{{url('admin_shelf_create')}}">
    <span  class='btn btn-block btn-primary'>
        建立貨架
    </span>
    </a>
</div>


    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">倉庫清單</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="shelf_list" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>貨架ID</th>
                  <th>貨架名稱</th>
                  <th>貨架編碼</th>
                  <th>最後修改時間</th>
                  <th>操作</th>
                </tr>
                </thead>                                                
                <tbody>
                @foreach ($shelfs as $shelf)
                <tr>
                  <td>{{$shelf->id}}</td>
                  <td>{{$shelf->name}}</td>
                  <td>{{$shelf->code}}</td>
                  <td>{{$shelf->updated_at}}</td>
                  <td>
                    <a class="btn btn-social-icon btn-twitter" href="{{url('admin_shelf_edit/'.$shelf->id)}}" title='編輯貨架'>
                        <i class="fa fa-edit"></i>
                    </a>      

                    <a class="btn btn-success" href="{{url('admin_shelf_block/'.$shelf->id)}}" title='貨物上架'>
                        <i class="fa fa-archive"></i>
                    </a> 
                    
                    <span class="btn btn-danger shelfDel" shelfId="{{$shelf->id}}" code="{{$shelf->code}}" title='刪除貨架'>
                        <i class="fa fa-remove"></i>
                    </span>

                  </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>貨架ID</th>
                  <th>貨架名稱</th>
                  <th>貨架編碼</th>
                  <th>最後修改時間</th>
                  <th>操作</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

<!-- 貨架區塊 -->

<script type="text/javascript">
    $('#shelf_list').DataTable({
        language: {
        "processing":   "處理中...",
        "loadingRecords": "載入中...",
        "lengthMenu":   "顯示 _MENU_ 項結果",
        "zeroRecords":  "沒有符合的結果",
        "info":         "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
        "infoEmpty":    "顯示第 0 至 0 項結果，共 0 項",
        "infoFiltered": "(從 _MAX_ 項結果中過濾)",
        "infoPostFix":  "",
        "search":       "搜尋:",
        "paginate": {
            "first":    "第一頁",
            "previous": "上一頁",
            "next":     "下一頁",
            "last":     "最後一頁"
        },
        "aria": {
            "sortAscending":  ": 升冪排列",
            "sortDescending": ": 降冪排列"
        }
        }

    });

</script>

<script type="text/javascript">

    $('body').on('click', '.shelfDel', function() {

        // 刪除確認
        Swal.fire({

            title: '確定要刪除'+$(this).attr('code')+'貨架?',
            text: "執行刪除後指定貨架以及層數、區塊、貨號資訊...等都將一併刪除,且不可復原",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '確認刪除',
            cancelButtonText: '取消'

        }).then((result) => {
            
            if (result.value) {
                
                shelfId = $(this).attr('shelfId');
                // AJAX刪除開始
                var request = $.ajax({
                    url: "{{url('/admin_shelf_blockDel')}}",
                    method: "POST",
                    data: { shelfId : shelfId,
                            _token: "{{ csrf_token() }}", 
                    },
                    dataType: "JSON"
                });
 
                request.done(function( msg ) {
                    
                   if( msg['res'] === true){

                        Swal.fire({
                            
                            type: 'success',
                            title: '執行成功',
                            html: msg['info'],
                        });
                   }else{
                        Swal.fire({
                            
                            type: 'error',
                            title: '執行失敗',
                            html: msg['info'],
                        });
                   }
                
                });
 
                request.fail(function( jqXHR, textStatus ) {
                    
                    console.log( "Request failed: " + textStatus );
                });

            }
        })       
        // 刪除確認結束
    });
        //alert('sQQQQ');


    
</script>
@endsection