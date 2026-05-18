<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\InternetPackage;
use App\Models\MikrotikRouter;
use App\Jobs\SyncMikrotikPppoeUserJob;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\Validate;

class CreateCustomer extends Component
{
    // Form input properties
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|unique:customers,phone')]
    public string $phone = '';

    #[Validate('nullable|string')]
    public string $address = '';

    #[Validate('required|string|unique:customers,pppoe_username|alpha_dash')]
    public string $pppoe_username = '';

    #[Validate('required|string|min:4')]
    public string $pppoe_password = '';

    #[Validate('required|integer|exists:mikrotik_routers,id')]
    public ?int $mikrotik_router_id = null;

    #[Validate('required|integer|exists:internet_packages,id')]
    public ?int $internet_package_id = null;

    #[Validate('required|integer|between:1,28')]
    public ?int $billing_day = 1;

    #[Validate('nullable|numeric|min:0')]
    public ?float $monthly_bill = null;

    #[Validate('required|date')]
    public string $connection_date = '';

    // Collections for dropdown selection
    public Collection $routers;
    public Collection $packages;

    /**
     * Mount the component and initialize dropdown collections and default dates.
     */
    public function mount(): void
    {
        $this->routers = MikrotikRouter::all();
        $this->packages = InternetPackage::all();
        $this->connection_date = now()->format('Y-m-d');
    }

    /**
     * Save the new customer subscription and dispatch the sync job to the queue.
     */
    public function save(): void
    {
        $this->validate();

        $customer = Customer::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address ?: null,
            'pppoe_username' => $this->pppoe_username,
            'pppoe_password' => $this->pppoe_password,
            'mikrotik_router_id' => $this->mikrotik_router_id,
            'internet_package_id' => $this->internet_package_id,
            'billing_day' => $this->billing_day,
            'monthly_bill' => $this->monthly_bill ?: null,
            'connection_date' => $this->connection_date,
            'status' => 'pending', // Default status upon registration
        ]);

        // Dispatch background job to sync the new PPPoE user to the Mikrotik Router
        SyncMikrotikPppoeUserJob::dispatch($customer, 'add');

        // Reset the form fields except collections and connection_date defaults
        $this->reset([
            'name',
            'phone',
            'address',
            'pppoe_username',
            'pppoe_password',
            'mikrotik_router_id',
            'internet_package_id',
            'billing_day',
            'monthly_bill',
        ]);

        $this->connection_date = now()->format('Y-m-d');

        // Set session flash success message
        session()->flash('success', "Customer '{$customer->name}' created successfully and queued for MikroTik router sync.");
    }

    /**
     * Render the Livewire component view.
     */
    public function render()
    {
        return view('livewire.customers.create-customer');
    }
}
