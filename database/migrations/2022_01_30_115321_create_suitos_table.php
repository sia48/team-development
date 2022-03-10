<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suitos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->date('suito')->nullable()->comment('日付取得');
            $table->datetime('datetime')->comment('使用日時');
            $table->string('category')->comment('カテゴリー');
            $table->integer('money')->comment('金額');
            $table->tinyinteger('flag')->comment('1:支出,2:収入');
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
        Schema::dropIfExists('suitos');
    }
}
