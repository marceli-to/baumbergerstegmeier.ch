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
      Schema::create('contact', function (Blueprint $table) {
        $table->id();
        $table->text('address');
        $table->text('description')->nullable();
        $table->string('maps_uri')->nullable();
        $table->text('imprint')->nullable();
        $table->text('privacy')->nullable();
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
      Schema::dropIfExists('contact');
    }
};
