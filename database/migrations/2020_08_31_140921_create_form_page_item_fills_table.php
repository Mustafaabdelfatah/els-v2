<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormPageItemFillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_page_item_fills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_page_item_id');
            $table->unsignedBigInteger('user_id');

            $table->text('value')->nullable();
            $table->text('file_name')->nullable();
//            $table->integer('importance')->default(0);
//            $table->string('frequency')->nullable();
            $table->boolean('review')->default(false);
            $table->string('comment')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('form_page_item_id')->references('id')->on('form_page_items')
                ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_page_item_fills');
    }
}
