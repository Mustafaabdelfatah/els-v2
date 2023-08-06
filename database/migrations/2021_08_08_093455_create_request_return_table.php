<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_return', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('user_id');
            $table->string('file')->nullable();
            $table->string('Priority')->nullable();
            $table->string('end_date')->nullable();
            $table->string('is_secret')->nullable();
            $table->string('approve_secret')->nullable();
            $table->string('is_need_info')->nullable();
            $table->string('informationText')->nullable();
            $table->integer('expected_amount')->nullable();
            $table->tinyInteger('workers')->default(0);
            $table->tinyInteger('commercial')->default(0);
            $table->tinyInteger('general')->default(0);
            $table->tinyInteger('administrative')->default(0);
            $table->tinyInteger('executive')->default(0);
            $table->string('procedureType')->nullable();
            $table->text('value')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms')
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
        Schema::dropIfExists('request_return');
    }
}
