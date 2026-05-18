<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
    
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Customers List</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage all your broadband subscribers, billing statuses, and their active hardware profiles.</p>
        </div>
        
        <!-- Add Customer Button -->
        <a href="{{ route('customers.create') }}" 
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-lg shadow-indigo-500/20 transition duration-150 justify-center">
            <svg class="h-5 w-5 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Customer
        </a>
    </div>

    <!-- Main Table Container Card -->
    <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <!-- Filters Header (Search Bar with Debounced Input) -->
        <div class="px-6 py-5 sm:px-8 border-b border-gray-100 dark:border-gray-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gray-50/50 dark:bg-gray-800/10">
            <div class="relative max-w-md w-full">
                <!-- Search Input Field -->
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg wire:loading.remove wire:target="search" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    
                    <!-- Eager Searching Spinner -->
                    <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                
                <input type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search by name, phone, or PPPoE username..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
            </div>
            
            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} subscribers
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                <thead class="bg-gray-50/30 dark:bg-gray-800/20 text-gray-500 dark:text-gray-400 text-left text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 sm:px-8">Name</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">PPPoE Username</th>
                        <th class="px-6 py-4">Package</th>
                        <th class="px-6 py-4 text-right">Monthly Bill</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody wire:loading.class="opacity-60" wire:target="search" class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition duration-150">
                            
                            <!-- Subscriber Name -->
                            <td class="px-6 py-4 sm:px-8 whitespace-nowrap">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $customer->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $customer->address ?: 'No Address Specified' }}</div>
                            </td>
                            
                            <!-- Phone -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300 font-medium">
                                {{ $customer->phone }}
                            </td>
                            
                            <!-- PPPoE Username -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                <span class="px-2 py-1 rounded bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700/50 font-mono text-xs">
                                    {{ $customer->pppoe_username }}
                                </span>
                            </td>
                            
                            <!-- Package -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $customer->internetPackage->name }}
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    Router: {{ $customer->mikrotikRouter->name }}
                                </div>
                            </td>
                            
                            <!-- Monthly Bill -->
                            <td class="px-6 py-4 whitespace-nowrap text-right font-extrabold text-gray-900 dark:text-white">
                                BDT {{ number_format($customer->monthly_bill ?? $customer->internetPackage->price, 2) }}
                            </td>
                            
                            <!-- Status Badges -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusColor = match($customer->status->value) {
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200/50 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-800/30',
                                        'suspended' => 'bg-rose-50 text-rose-700 border-rose-200/50 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-800/30',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200/50 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-800/30',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200/50 dark:bg-gray-950/20 dark:text-gray-400 dark:border-gray-800/30'
                                    };
                                    $dotColor = match($customer->status->value) {
                                        'active' => 'bg-emerald-500',
                                        'suspended' => 'bg-rose-500',
                                        'pending' => 'bg-amber-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                    {{ ucfirst($customer->status->value) }}
                                </span>
                            </td>
                            
                            <!-- Actions Placeholder buttons -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                <a href="{{ route('customers.show', $customer->id) }}" 
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 transition duration-150">
                                    View
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}" 
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 hover:bg-indigo-100/60 dark:hover:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 transition duration-150">
                                    Edit
                                </a>
                            </td>
                            
                        </tr>
                    @empty
                        <!-- Empty Search Results Illustration -->
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="p-4 rounded-full bg-gray-50 dark:bg-gray-800/50 text-gray-400 mb-4">
                                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">No Customers Found</h4>
                                    <p class="text-xs text-gray-400 mt-1">There are no subscribers matching the search query '<strong>{{ $search }}</strong>'.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination Links Footer -->
        @if($customers->hasPages())
            <div class="px-6 py-5 sm:px-8 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/20">
                {{ $customers->links() }}
            </div>
        @endif
        
    </div>

</div>
