@extends('layouts.admin')

@section('content')
<style type="text/css">
    .buttonArea{
        margin-bottom: 10px;
    }
</style>
<!-- 新增按鈕區塊 -->
<div class='col-md-1 col-md-offset-11 col-sm-12 col-xs-12 text-center buttonArea'>
    <a href="{{url('user_create')}}">
    <span  class='btn btn-block btn-primary'>
        新增使用者
    </span>
    </a>
</div>


    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">使用者清單</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="user_list" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>使用者ID</th>
                  <th>使用者名稱</th>
                  <th>使用者權限</th>
                  <th>最後修改時間</th>
                  <th>操作</th>
                </tr>
                </thead>                                                

                <tfoot>
                <tr>
                  <th>使用者ID</th>
                  <th>使用者名稱</th>
                  <th>使用者權限</th>
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

<script type="text/javascript">
$(document).ready(function() {
    var user_list = $('#user_list').DataTable({
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
        },
        processing: true,
        serverSide: true,
        search: {
            return: true,
        },
        ajax: {
                  url:"{{url('/user_query')}}",
                  type: 'POST',
                  data: function ( d ) {
                      d._token = "{{ csrf_token() }}";
                  }                  
        },
        "order": [0,"asc"],
        "columnDefs": [ {
            "targets": 4,
            "orderable": false
        }]
       
    });
    
    user_list.ajax.reload();
});
</script>




<script>
$('body').on('click', '.userDel', function() {

// 刪除確認
Swal.fire({

    title: '確定要刪除使用者?',
    text: "執行刪除後將移除該會員相關資訊,且不可復原",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: '確認刪除',
    cancelButtonText: '取消'

}).then((result) => {
    
    if (result.value) {
        
        userId = $(this).attr('userId');
        // AJAX刪除開始
        var request = $.ajax({
            url: "{{url('/user_del')}}",
            method: "POST",
            data: { userId : userId,
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

           $('#user_list').DataTable().ajax.reload();
        
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