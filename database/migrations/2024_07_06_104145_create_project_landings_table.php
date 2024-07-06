<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('project_landing', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('image_id');
        $table->unsignedBigInteger('project_id');
        $table->integer('position');
        $table->integer('column');
        $table->boolean('publish');
        $table->unsignedBigInteger('state_id')->nullable();
        $table->unsignedBigInteger('category_id')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_landings');
    }
};
