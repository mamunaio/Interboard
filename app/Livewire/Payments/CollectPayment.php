<?php

declare(strict_types=1);

namespace App\Livewire\Payments;

use App\Enums\CustomerStatus;
use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Jobs\SyncMikrotikPppoeUserJob;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Validate;

class CollectPayment extends Component
{
    // The parent Invoice model
    public Invoice $invoice;

    // Form inputs with Livewire v3 validation attributes
    public ?int $invoice_id = null;

    #[Validate('required|numeric|min:0.01')]
    public ?float $amount = null;

    #[Validate('required|string|in:cash,bkash,nagad,bank')]
    public string $payment_method = 'cash';

    #[Validate('nullable|string|max:255')]
    public ?string $transaction_id = null;

    /**
     * Mount the component with the target unpaid Invoice model.
     */
    public function mount(Invoice $invoice): void
    {
        // Eager load customer for status and router association checks
        $this->invoice = $invoice->load('customer');
        $this->invoice_id = $invoice->id;

        // Default the payment collection amount to the invoice total amount
        $this->amount = (float) $invoice->amount;
    }

    /**
     * Process and save the manual billing payment within a database transaction.
     */
    public function save()
    {
        $this->validate();

        // Lock database operations to secure financial transaction consistency
        DB::transaction(function () {
            // 1. Create a Payment transaction trace
            Payment::create([
                'invoice_id' => $this->invoice->id,
                'customer_id' => $this->invoice->customer_id,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'transaction_id' => $this->transaction_id ?: null,
                'paid_at' => now(),
            ]);

            // 2. Set the Invoice status to Paid
            $this->invoice->update([
                'status' => InvoiceStatus::PAID,
            ]);

            // 3. Auto-reactivate customer if currently suspended due to overdue invoices
            $customer = $this->invoice->customer;

            if ($customer->status === CustomerStatus::SUSPENDED) {
                $customer->update([
                    'status' => CustomerStatus::ACTIVE,
                ]);

                // Dispatch a background job to reactivate PPPoE connection on MikroTik router
                SyncMikrotikPppoeUserJob::dispatch($customer, 'enable');
            }
        });

        // Send a browser session success alert
        session()->flash('success', "Payment of BDT " . number_format((float) $this->amount, 2) . " successfully collected for Invoice #{$this->invoice->invoice_no}.");

        // Redirect back to invoice management or reload
        return redirect()->route('invoices.index');
    }

    /**
     * Render the payment collection TALL stack view.
     */
    public function render()
    {
        return view('livewire.payments.collect-payment');
    }
}
