<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\CustomerStatus;
use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Livewire\Component;

class AdminDashboard extends Component
{
    // High-level dashboard metric values
    public int $totalActiveCustomers = 0;
    public int $totalSuspendedCustomers = 0;
    public float $thisMonthRevenue = 0.0;
    public float $totalPendingDue = 0.0;

    /**
     * Initialize the dashboard state and load critical business metrics.
     */
    public function mount(): void
    {
        $this->loadMetrics();
    }

    /**
     * Compute and load aggregated stats from Postgres database.
     */
    public function loadMetrics(): void
    {
        $this->totalActiveCustomers = Customer::query()
            ->where('status', CustomerStatus::ACTIVE)
            ->count();

        $this->totalSuspendedCustomers = Customer::query()
            ->where('status', CustomerStatus::SUSPENDED)
            ->count();

        $this->thisMonthRevenue = (float) Payment::query()
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');

        $this->totalPendingDue = (float) Invoice::query()
            ->whereIn('status', [InvoiceStatus::UNPAID, InvoiceStatus::OVERDUE])
            ->sum('amount');
    }

    /**
     * Render the admin dashboard view with dynamically updated lists.
     */
    public function render()
    {
        $recentPayments = Payment::query()
            ->with(['customer', 'invoice'])
            ->latest('paid_at')
            ->limit(5)
            ->get();

        return view('livewire.dashboard.admin-dashboard', [
            'recentPayments' => $recentPayments,
        ]);
    }
}
