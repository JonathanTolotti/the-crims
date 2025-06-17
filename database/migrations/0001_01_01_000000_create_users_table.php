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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->boolean('is_vip')->default(false);
            $table->timestamp('vip_expires_at')->nullable();

            $table->unsignedBigInteger('current_level_id')->default(1)->references('id')->on('level_definitions')->onDelete('restrict');

            $table->unsignedBigInteger('experience_points')->default(0);

            $table->integer('energy_points')->default(100);
            $table->integer('max_energy_points')->default(100);

            $table->unsignedBigInteger('money')->default(1000);
            $table->unsignedBigInteger('crims_coin')->default(0);

            // Atributos base do personagem
            $table->integer('base_strength')->default(5);
            $table->integer('base_dexterity')->default(5);
            $table->integer('base_intelligence')->default(5);


        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
