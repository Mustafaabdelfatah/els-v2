<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->boolean('isEnv')->default(false);
            $table->mediumText('value')->nullable();
            $table->string('group')->nullable();
            $table->enum('type', ['text', 'textarea', 'file', 'password', 'number', 'boolean','select'])->default('text');
            $table->boolean('editable')->default(false);
//            $table->unsignedBigInteger('user_id');
//            $table->foreign('user_id')->references('id')->on('users')
//                ->onDelete('cascade');

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
        Schema::dropIfExists('settings');
    }
}
