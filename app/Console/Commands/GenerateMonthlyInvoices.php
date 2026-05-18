<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates monthly invoices for active customers based on their billing day.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $todayDay = Carbon::now()->day;
        $billingMonth = Carbon::now()->format('Y-m');
        $billingMonthClean = Carbon::now()->format('Ym');

        // Fetch all active customers whose billing day is today
        $customers = Customer::query()
            ->where('status', CustomerStatus::ACTIVE)
            ->where('billing_day', $todayDay)
            ->with('internetPackage')
            ->get();

        if ($customers->isEmpty()) {
            $this->info("No active customers with billing day {$todayDay} found today.");
            return Command::SUCCESS;
        }

        $generatedCount = 0;

        foreach ($customers as $customer) {
            // Use database transaction to guarantee database consistency and safety
            DB::transaction(function () use ($customer, $billingMonth, $billingMonthClean, &$generatedCount) {
                // Prevent duplicate invoicing if the command runs multiple times on the same day
                $exists = Invoice::query()
                    ->where('customer_id', $customer->id)
                    ->where('billing_month', $billingMonth)
                    ->exists();

                if ($exists) {
                    return;
                }

                // Resolve invoice price: custom override takes precedence over package default
                $amount = $customer->monthly_bill ?? $customer->internetPackage->price;

                // Structure a professional, unique invoice number
                $invoiceNo = sprintf(
                    'INV-%s-%s-%s',
                    $billingMonthClean,
                    str_pad((string) $customer->id, 5, '0', STR_PAD_LEFT),
                    strtoupper(Str::random(4))
                );

                Invoice::create([
                    'customer_id' => $customer->id,
                    'invoice_no' => $invoiceNo,
                    'billing_month' => $billingMonth,
                    'amount' => $amount,
                    'status' => 'unpaid', // Default billing status
                    'issue_date' => Carbon::now()->toDateString(),
                    'due_date' => Carbon::now()->addDays(7)->toDateString(),
                ]);

                $generatedCount++;
            });
        }

        $this->info("Successfully generated {$generatedCount} recurring invoice(s) for {$billingMonth}.");

        return Command::SUCCESS;
    }
}
