@extends('layouts.admin')

@section('content')
<style type="text/css">
    .buttonArea{
        margin-bottom: 10px;
    }
</style>
<!-- 新增按鈕區塊 -->
<div class='col-md-1 col-md-offset-11 col-sm-12 col-xs-12 text-center buttonArea'>
    <a href="{{url('admin_whare_house_create')}}">
    <span  class='btn btn-block btn-primary'>
        建立倉庫
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
              <table id="wharehouse_list" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>倉庫ID</th>
                  <th>倉庫名稱</th>
                  <th>倉庫編碼</th>
                  <th>最後修改時間</th>
                  <th>操作</th>
                </tr>
                </thead>                                                
                <tbody>
                @foreach ($wharehouses as $wharehouse)
                <tr>
                  <td>{{$wharehouse->id}}</td>
                  <td>{{$wharehouse->name}}</td>
                  <td>{{$wharehouse->code}}</td>
                  <td>{{$wharehouse->updated_at}}</td>
                  <td>
                    <a class="btn btn-social-icon btn-twitter" href="{{url('admin_whare_house_edit/'.$wharehouse->id)}}">
                        <i class="fa fa-edit"></i>
                    </a>                   
                  </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>倉庫ID</th>
                  <th>倉庫名稱</th>
                  <th>倉庫編碼</th>
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
    $('#wharehouse_list').DataTable({
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
});
</script>
@endsection