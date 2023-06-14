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
    Schema::create('teasers', function (Blueprint $table) {
      $table->id();
      $table->string('type')->default('project');
      $table->unsignedBigInteger('image_id')->nullable();
      $table->unsignedBigInteger('project_id')->nullable();
      $table->unsignedBigInteger('article_id')->nullable();
      $table->tinyInteger('position')->default(-1);
      $table->tinyInteger('column')->default(1);
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
    Schema::dropIfExists('teasers');
  }
};
