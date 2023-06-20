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
      Schema::create('awards', function (Blueprint $table) {
        $table->id();
        $table->year('year');
        // $table->text('title');
        $table->text('text');
        // $table->string('subtitle')->nullable();
        // $table->string('link')->nullable();
        $table->tinyInteger('order')->default(-1);
        $table->tinyInteger('publish')->default(1);
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
      Schema::dropIfExists('awards');
    }
};
