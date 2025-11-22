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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')
            ->nullable();
            $table->enum('priority', ['high','medium','low'])
            ->default('medium');
            $table->enum('status',['pending','completed'])
            ->default('pending');
            $table->date('due_date')
            ->nullable();

            //Allow tasks without categories
            $table->foreignId('category_id')
            ->nullable()
            ->constrained()
            ->onDelete('set null');

            //User who own the task
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
