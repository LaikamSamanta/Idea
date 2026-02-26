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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable(); // Apraksts var būt tukšs
            $table->string('status')->default('pending'); // Statuss ar noklusējuma vērtību "pending"
            $table->string('image_path')->nullable(); // Attēla ceļš var būt tukšs
            $table->json('links')->default('[]'); // Saite var būt tukša, noklusējuma vērtība ir tukšs JSON masīvs
            $table->timestamps(); // Izveido created_at un updated_at lauku
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
