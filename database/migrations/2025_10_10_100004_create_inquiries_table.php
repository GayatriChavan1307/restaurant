<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('inquiry_number')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('country')->nullable();
            $table->integer('quantity');
            $table->string('unit')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'processing', 'quoted', 'converted', 'closed'])->default('pending');
            $table->text('notes')->nullable();
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->string('quoted_currency', 3)->nullable();
            $table->timestamp('quoted_at')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['customer_email', 'status']);
            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};