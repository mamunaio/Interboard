<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ListCustomers extends Component
{
    use WithPagination;

    // Preserve the search parameter in browser history for better UX/bookmarking
    #[Url(history: true)]
    public string $search = '';

    /**
     * Reset pagination page whenever the search query is updated to prevent empty pages.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Render the paginated customer list with active search parameters.
     */
    public function render()
    {
        $query = Customer::query()
            ->with(['internetPackage', 'mikrotikRouter']);

        // Filter by search query if present
        if (!empty(trim($this->search))) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm)
                  ->orWhere('pppoe_username', 'like', $searchTerm);
            });
        }

        // Return latest registered customers, paginated to 10 items per page
        $customers = $query->latest()->paginate(10);

        return view('livewire.customers.list-customers', [
            'customers' => $customers,
        ]);
    }
}
