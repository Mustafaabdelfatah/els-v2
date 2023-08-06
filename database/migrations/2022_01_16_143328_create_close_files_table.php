<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('close_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("request_file_id");
            $table->string("file")->nullable();
            $table->timestamps();
            $table->foreign('request_file_id')->references('id')->on('request_file')
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
        Schema::dropIfExists('close_files');
    }
}
