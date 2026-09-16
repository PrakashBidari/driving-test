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
        Schema::create('assessment_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('test_date')->nullable();
            $table->enum('result', ['pending', 'pass', 'fail'])->default('pending');
            $table->enum('status', ['draft', 'completed'])->default('draft');
            $table->string('signature_path')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'test_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_forms');
    }
};
