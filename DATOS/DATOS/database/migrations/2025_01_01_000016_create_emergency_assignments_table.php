<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('message')->nullable();
            $table->string('status')->default('en_camino');
            $table->timestamps();

            $table->unique(['emergency_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_assignments');
    }
};
