<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShelfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shelf', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 30)->unique()->comment = '貨架名稱';
            $table->char('code', 30)->unique()->comment = '貨架編碼';  
            $table->text('note')->comment = '倉庫備註';
            $table->boolean('status')->comment = '狀態';
            $table->unsignedInteger('wharehouse_id')->comment = '所屬倉庫'; 
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
        Schema::dropIfExists('shelf');
    }
}
