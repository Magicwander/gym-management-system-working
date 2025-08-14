<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trainer_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->date('booking_date');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('session_type', ['personal_training', 'group_session', 'consultation']);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->decimal('price', 8, 2);
            $table->timestamps();
            
            $table->index(['trainer_id', 'booking_date']);
            $table->index(['member_id', 'booking_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainer_bookings');
    }
};