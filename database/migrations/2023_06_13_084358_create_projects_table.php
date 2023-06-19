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
      Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('slug');
        $table->string('title');
        $table->text('text')->nullable();
        $table->text('info')->nullable();
        $table->string('periode')->nullable();
        $table->year('year');
        $table->string('location')->nullable();
        $table->tinyInteger('order')->default(-1);
        $table->tinyInteger('publish')->default(1);
        $table->tinyInteger('feature')->default(0);
        $table->tinyInteger('landing')->default(0);
        $table->unsignedBigInteger('type_id');
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
      Schema::dropIfExists('projects');
    }
};
