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
        Schema::create('whatsapp_reminder_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('phone', 20);

            // Tipe reminder: 3_days_before | due_date | overdue_1_day | manual
            $table->string('reminder_type', 30)->default('manual');

            $table->text('message');

            // Status pengiriman: sent | failed
            $table->string('status', 10)->default('sent');

            $table->timestamp('sent_at')->nullable();

            // Response raw dari Fonnte API
            $table->text('response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_reminder_logs');
    }
};
