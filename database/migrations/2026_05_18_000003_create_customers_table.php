<?php

declare(strict_types=1);

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->text('address')->nullable();
            $table->string('pppoe_username')->unique();
            $table->string('pppoe_password');
            
            // Foreign Keys
            $table->foreignId('mikrotik_router_id')
                  ->constrained('mikrotik_routers')
                  ->onDelete('restrict');
            
            $table->foreignId('internet_package_id')
                  ->constrained('internet_packages')
                  ->onDelete('restrict');
            
            // Status: 'active', 'suspended', 'pending', default 'pending'
            $table->string('status')->default('pending');
            
            $table->decimal('monthly_bill', 8, 2)->nullable(); // optional custom price overriding package price
            $table->unsignedTinyInteger('billing_day'); // integer, 1-28
            $table->date('connection_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
