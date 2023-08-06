<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLitigationColumnsFromRequestReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_return', function (Blueprint $table) {
            $table->dropColumn('workers')->default(0);
            $table->dropColumn('commercial')->default(0);
            $table->dropColumn('general')->default(0);
            $table->dropColumn('administrative')->default(0);
            $table->dropColumn('executive')->default(0);
            $table->dropColumn('expected_amount')->nullable();
            $table->dropColumn('procedureType')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_return', function (Blueprint $table) {
            $table->tinyInteger('workers')->default(0);
            $table->tinyInteger('commercial')->default(0);
            $table->tinyInteger('general')->default(0);
            $table->tinyInteger('administrative')->default(0);
            $table->tinyInteger('executive')->default(0);
            $table->integer('expected_amount')->nullable();
            $table->string('procedureType')->nullable();
        });
    }
}
