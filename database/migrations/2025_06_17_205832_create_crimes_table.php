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
        Schema::create('crimes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            $table->integer('energy_cost')->unsigned();
            $table->integer('money_reward_min')->unsigned();
            $table->integer('money_reward_max')->unsigned();
            $table->integer('experience_reward')->unsigned();
            $table->integer('cooldown_seconds')->unsigned();

            $table->foreignId('required_level_id')->constrained('level_definitions');

            $table->string('primary_attribute');
            $table->unsignedTinyInteger('base_success_chance');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crimes');
    }
};
