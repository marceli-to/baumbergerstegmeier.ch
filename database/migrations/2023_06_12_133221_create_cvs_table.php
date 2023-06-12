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
      Schema::create('cvs', function (Blueprint $table) {
        $table->id();
        $table->string('periode');
        $table->text('description');
        $table->tinyInteger('order')->default(-1);
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->foreignId('cv_category_id')->nullable()->constrained()->onDelete('cascade');
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
      Schema::dropIfExists('cvs');
    }
};
