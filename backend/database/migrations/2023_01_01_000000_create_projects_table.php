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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('status');
            $table->integer('progress')->default(0);
            $table->date('start_date');
            $table->date('due_date');
            $table->decimal('budget_total', 15, 2);
            $table->decimal('budget_spent', 15, 2)->default(0);
            $table->string('risk_level');
            $table->string('project_type');
            $table->string('priority');
            $table->foreignId('manager_id')->constrained('users');
            $table->string('client')->nullable();
            $table->string('department');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};