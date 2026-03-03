<?php

use App\Models\Idea;
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
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Idea::class)->constrained()->cascadeOnDelete(); // Definē ārējo atslēgu, kas saista soļus ar idejām, un nodrošina, ka soļi tiek izdzēsti, ja tiek izdzēsta saistītā ideja
            $table->string('description'); // Soļa apraksts
            $table->boolean('completed')->default(false); // Norāda, vai solis ir pabeigts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
