<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuaItemCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dua_item_carts', function (Blueprint $table) {
            $table->id();
            $table->text('product_code')->nullable();
            $table->string('user_email')->nullable();
            $table->integer('product_price')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('total')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('dua_item_carts');
    }
}
