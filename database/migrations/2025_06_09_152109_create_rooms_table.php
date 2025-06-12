// database/migrations/XXXX_XX_XX_create_rooms_table.php
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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name')->unique(); // اسم القاعة (بلازا داخليه، سيسكو، إلخ)
            $table->integer('capacity')->nullable(); // سعة القاعة (اختياري لو هتحتاجها)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
