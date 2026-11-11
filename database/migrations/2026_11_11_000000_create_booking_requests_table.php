<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 120);
            $table->string('email', 254);
            $table->string('phone', 32)->nullable();
            $table->string('room_name', 80);
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->unsignedTinyInteger('guests');
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
