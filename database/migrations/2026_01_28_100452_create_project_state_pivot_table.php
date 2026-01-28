<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create pivot table
        Schema::create('project_state', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->unique(['project_id', 'state_id']);
        });

        // 2. Migrate existing state_id values to pivot table
        DB::table('projects')->whereNotNull('state_id')->orderBy('id')->each(function ($project) {
            DB::table('project_state')->insert([
                'project_id' => $project->id,
                'state_id' => $project->state_id,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_state');
    }
};
