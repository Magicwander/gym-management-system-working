<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained('trainer_bookings')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // credit_card, debit_card, paypal, etc.
            $table->string('transaction_id')->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->datetime('payment_date');
            $table->text('description')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_type')->nullable();
            $table->timestamps();
            
            $table->index(['member_id', 'status']);
            $table->index(['transaction_id']);
            $table->index(['payment_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_records');
    }
};