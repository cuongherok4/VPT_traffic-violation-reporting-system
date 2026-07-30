<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fine_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_report_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('users')->restrictOnDelete();
            $table->unsignedBigInteger('amount');
            $table->text('violation_summary');
            $table->string('payment_status', 20)->default('unpaid')->index();
            $table->timestamp('issued_at')->index();
            $table->timestamp('due_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('violation_report_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fine_receipt_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 40)->index();
            $table->string('title');
            $table->text('message');
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('fine_receipts');
    }
};
