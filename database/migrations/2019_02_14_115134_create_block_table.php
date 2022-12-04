<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('shelf_id')->comment = '所屬貨架'; 
            $table->unsignedTinyInteger('layer_num')->comment = '層數';
            $table->unsignedTinyInteger('block_num')->comment = '第幾區塊';
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
        Schema::dropIfExists('block');
    }
}
