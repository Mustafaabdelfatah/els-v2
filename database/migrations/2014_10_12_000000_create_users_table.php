<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('type', ['admin'])->default('admin');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->text('avatar')->nullable();
            $table->boolean('active')->default(true);
            $table->string('notification_perefared')->default('database');
            $table->string('guid')->unique()->nullable();
            $table->string('domain')->nullable();
            $table->unsignedBigInteger('user_reset_password')->nullable();

            $table->boolean('mail_notify')->default(true);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_reset_password')->references('id')->on('users')
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
        Schema::dropIfExists('users');
    }
}
