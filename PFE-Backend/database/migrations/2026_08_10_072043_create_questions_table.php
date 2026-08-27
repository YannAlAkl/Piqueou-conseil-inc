<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->cascadeOnDelete();
            $table->foreignId('question_type_id')->constrained('question_types')->restrictOnDelete();
            $table->text('question');
            $table->text('description')->nullable();
            $table->json('options')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('required')->default(true);
            $table->timestamps();

            $table->index(['questionnaire_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
