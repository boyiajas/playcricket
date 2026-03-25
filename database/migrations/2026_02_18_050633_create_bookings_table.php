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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('facility');
            $table->date('date');
            $table->string('start_time'); // HH:mm format
            $table->integer('duration_hours');
            $table->string('member_id')->nullable(); // For now, storing as string ID from mock
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])->default('PENDING');
            $table->enum('payment_status', ['UNPAID', 'PAID', 'FAILED'])->default('UNPAID');
            $table->string('payment_id')->nullable(); // PayFast payment ID (m_payment_id)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
