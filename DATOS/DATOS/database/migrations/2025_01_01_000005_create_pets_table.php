<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('species');
            $table->string('breed')->nullable();
            $table->integer('age');
            $table->string('age_unit')->default('años');
            $table->string('gender')->nullable();
            $table->string('city')->nullable();
            $table->string('weight')->nullable();
            $table->string('size')->nullable();
            $table->foreignId('shelter_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('disponible');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('emoji')->nullable();
            $table->string('color')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
