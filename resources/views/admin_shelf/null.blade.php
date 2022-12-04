@extends('layouts.admin')

@section('content')
<style type="text/css">
    .buttonArea{
        margin-bottom: 10px;
    }
</style>

<section class="content">
    
    <div class="box box-primary">
        <table width="100%" >
        <tr>
        @foreach ($datas as $datak=>$data)	
            @if( ($datak+1)%6 == 0)
            <td align='center'>
            <div class='nullbox'>{{$data->code}}-{{$data->layer_num}}-{{$data->block_num}}</div>
            </td></tr><tr>
            @else
            <td align='center'>
            <div class='nullbox'>{{$data->code}}-{{$data->layer_num}}-{{$data->block_num}}</div>
            </td>            
            @endif
        @endforeach
        </tr>
        </table>      
    </div>
  <!-- /.row -->
    <style type="text/css">
        .nullbox{
        	/*border:1px solid black;
        	background-color: white;*/
        	font-weight: 900;
        	font-size: 18px;

        }
    </style>
</section>
<!-- /.content -->

@endsection