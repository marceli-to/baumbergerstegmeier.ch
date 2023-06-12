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
      Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('firstname');
        $table->string('name');
        $table->string('title')->nullable();
        $table->tinyInteger('order')->default(-1);
        $table->foreignId('team_id')->nullable()->constrained();
        $table->foreignId('employee_category_id')->nullable()->constrained();
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
      Schema::dropIfExists('employees');
    }
};
