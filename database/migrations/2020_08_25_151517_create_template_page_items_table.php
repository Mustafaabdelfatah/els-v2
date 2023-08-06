<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatePageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_page_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['line', 'radio', 'label', 'text', 'textarea',
                'checkbox', 'select', 'table', 'tree', 'file'])->default('text');
            $table->text('label');
            $table->boolean('enabled')->default(true);
            $table->boolean('required')->default(false);
            $table->boolean('website_view')->default(true);
            $table->text('notes')->nullable();
            $table->string('comment')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('length')->nullable();
            $table->text('childList')->nullable();
            $table->unsignedBigInteger('template_page_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('template_page_id')->references('id')->on('template_pages')
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
        Schema::dropIfExists('template_page_items');
    }
}
