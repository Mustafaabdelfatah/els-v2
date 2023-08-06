<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApproveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approve_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('workers')->default(0);
            $table->tinyInteger('commercial')->default(0);
            $table->tinyInteger('general')->default(0);
            $table->tinyInteger('administrative')->default(0);
            $table->tinyInteger('executive')->default(0);
            $table->integer('expected_amount')->nullable();
            $table->string('procedureType')->nullable();
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
        Schema::dropIfExists('approve_requests');
    }
}
