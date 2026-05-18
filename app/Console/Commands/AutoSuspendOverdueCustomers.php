<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\CustomerStatus;
use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Jobs\SyncMikrotikPppoeUserJob;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AutoSuspendOverdueCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:suspend-overdue-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suspends active customers whose invoices have passed their due date.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::now()->toDateString();

        // Retrieve all unpaid invoices that are past their due date, eager loading their customers
        $invoices = Invoice::query()
            ->where('status', InvoiceStatus::UNPAID)
            ->where('due_date', '<', $today)
            ->with('customer')
            ->get();

        if ($invoices->isEmpty()) {
            $this->info("No overdue unpaid invoices found today.");
            return Command::SUCCESS;
        }

        $suspendedCount = 0;

        foreach ($invoices as $invoice) {
            DB::transaction(function () use ($invoice, &$suspendedCount) {
                // Update the Invoice status to overdue
                $invoice->update([
                    'status' => InvoiceStatus::OVERDUE,
                ]);

                $customer = $invoice->customer;

                // Suspend the customer only if they are currently active
                if ($customer && $customer->status === CustomerStatus::ACTIVE) {
                    $customer->update([
                        'status' => CustomerStatus::SUSPENDED,
                    ]);

                    // Dispatch background queue job to disable PPPoE account on the Mikrotik router
                    SyncMikrotikPppoeUserJob::dispatch($customer, 'disable');

                    $suspendedCount++;
                }
            });
        }

        $this->info("Successfully flagged overdue invoices and suspended {$suspendedCount} active customer(s).");

        return Command::SUCCESS;
    }
}
