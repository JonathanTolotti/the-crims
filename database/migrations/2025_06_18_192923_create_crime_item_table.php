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
        Schema::create('crime_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crime_id')->constrained('crimes')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');

            $table->float('drop_chance', 5, 4)->default(0);

            $table->unique(['crime_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crime_item');
    }
};
