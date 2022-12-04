<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockgoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_goods', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('block_id')->comment = '區塊id';
            $table->char('goods_sn', 30)->comment = '貨號'; 
            $table->unsignedSmallInteger('number')->default(0)->comment = '存放數量'; 
            $table->unsignedSmallInteger('operator_id')->comment = '操作人員id';
            $table->char('operator_name', 30)->comment = '操作人員名稱';            
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_goods');
    }
}
