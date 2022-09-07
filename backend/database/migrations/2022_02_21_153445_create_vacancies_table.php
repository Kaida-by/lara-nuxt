<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->float('salary');
            $table->unsignedBigInteger('salary_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('entity_type_id');
            $table->unsignedBigInteger('status_id');

            $table->foreign('entity_type_id')->references('id')->on('entity_types')->onDelete('cascade');
            $table->foreign('salary_id')->references('id')->on('salary_types')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('entity_status')->onDelete('cascade');

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
        Schema::dropIfExists('vacancies');
    }
}
