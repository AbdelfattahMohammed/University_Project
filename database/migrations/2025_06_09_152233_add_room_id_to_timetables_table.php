// database/migrations/XXXX_XX_XX_add_room_id_to_timetables_table.php
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
        Schema::table('timetables', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null'); // إضافة room_id
            // استخدام ->nullable() عشان لو فيه بيانات قديمة مش مربوطة بقاعة
            // استخدام onDelete('set null') عشان لو مسحت قاعة، القيم المرتبطة بيها تبقى null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
    }
};
