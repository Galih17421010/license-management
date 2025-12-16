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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->integer('reminder_day');
            $table->timestamp('sent_at');

            $table->unique(['license_id', 'reminder_day', 'sent_at']);
            $table->index(['license_id', 'reminder_day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
