<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('original_name');
            $table->string('uuid');
            $table->string('src');
            $table->integer('is_main')->default(0);
            $table->integer('order');
            $table->unsignedBigInteger('entity_type_id');
            $table->bigInteger('entity_id');

            $table->foreign('entity_type_id')->references('id')->on('entity_types')->onDelete('cascade');

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
        Schema::dropIfExists('images');
    }
}
