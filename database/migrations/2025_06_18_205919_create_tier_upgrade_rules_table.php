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
        Schema::create('tier_upgrade_rules', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->unsignedInteger('from_tier');
            $table->unsignedInteger('to_tier');

            $table->foreignId('required_item_id')->constrained('items');
            $table->unsignedInteger('required_item_quantity')->default(1);
            $table->unsignedBigInteger('required_money')->default(0);

            $table->unsignedTinyInteger('success_chance');

            $table->string('failure_outcome');

            $table->timestamps();

            $table->unique(['from_tier', 'to_tier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tier_upgrade_rules');
    }
};
