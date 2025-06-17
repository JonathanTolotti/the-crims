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
        Schema::create('crime_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('crime_id')->constrained('crimes')->onDelete('cascade');

            $table->boolean('was_successful');
            $table->integer('money_gained')->unsigned()->default(0);
            $table->integer('experience_gained')->unsigned()->default(0);

            $table->timestamp('attempted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crime_logs');
    }
};
