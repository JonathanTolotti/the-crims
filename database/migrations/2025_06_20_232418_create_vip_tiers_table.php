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
        Schema::create('vip_tiers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->unique();
            $table->text('description');
            $table->unsignedInteger('price_in_cents')->default(0);
            $table->unsignedInteger('duration_in_days')->default(30);

            // Colunas de Benefícios
            $table->decimal('reward_multiplier', 5, 2)->default(1.00);
            $table->decimal('drop_rate_multiplier', 5, 2)->default(1.00);
            $table->decimal('cooldown_reduction_multiplier', 5, 2)->default(1.00);
            $table->unsignedInteger('max_energy_bonus')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vip_tiers');
    }
};
