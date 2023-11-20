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
      Schema::table('projects', function (Blueprint $table) {
        // add state_id column as foreign key
        $table->foreignId('state_id')->after('location')->nullable()->constrained();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      // drop state_id column
      Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn('state_id');
      });
    }
};
