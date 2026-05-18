<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Invoice;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Invoice::class)->constrained()->onDelete('restrict');
            $table->foreignIdFor(Customer::class)->constrained()->onDelete('restrict');
            $table->decimal('amount', 8, 2);
            $table->string('payment_method'); // e.g., 'bkash', 'nagad', 'cash', 'bank'
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
