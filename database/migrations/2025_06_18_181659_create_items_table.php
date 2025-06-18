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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->unique();
            $table->text('description');
            $table->string('item_type');
            $table->string('image_path')->nullable();
            $table->boolean('stackable')->default(false);

            // --- Campos específicos para EQUIPAMENTOS ---
            $table->string('equipment_slot')->nullable();
            $table->integer('strength_bonus')->default(0);
            $table->integer('dexterity_bonus')->default(0);
            $table->integer('intelligence_bonus')->default(0);

            // --- Campos específicos para CONSUMÍVEIS ---
            $table->string('effect_type')->nullable();
            $table->integer('effect_amount')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
