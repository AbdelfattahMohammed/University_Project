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
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->bigIncrements('report_id');
            $table->unsignedBigInteger('event_id');
            $table->decimal('revenue', 10, 2);
            $table->decimal('expenses', 10, 2);
            $table->decimal('total_budget', 10, 2);
            $table->date('report_date');
            $table->timestamps();

            $table->foreign('event_id')->references('event_id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
