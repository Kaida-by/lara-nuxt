<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_fact_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('photo_fact_id');
            $table->unsignedBigInteger('category_id');
            $table->foreign('photo_fact_id')->references('id')->on('photo_facts')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('photo_fact_category');
    }
};
