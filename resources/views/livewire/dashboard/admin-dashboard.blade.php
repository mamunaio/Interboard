<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
    
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Overview of your ISP billing cycles, customer status, and real-time revenue collection.</p>
        </div>
        
        <!-- Refresh Button -->
        <button wire:click="loadMetrics" 
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18" />
            </svg>
            Refresh Metrics
        </button>
    </div>

    <!-- 4-Column SaaS Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Active Customers Card -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xl shadow-gray-100/50 dark:shadow-none hover:scale-[1.02] transition duration-200 flex items-center gap-5">
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Active Customers</span>
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ number_format($totalActiveCustomers) }}</span>
            </div>
        </div>

        <!-- Suspended Customers Card -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xl shadow-gray-100/50 dark:shadow-none hover:scale-[1.02] transition duration-200 flex items-center gap-5">
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/30 text-rose-500">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Suspended</span>
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ number_format($totalSuspendedCustomers) }}</span>
            </div>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xl shadow-gray-100/50 dark:shadow-none hover:scale-[1.02] transition duration-200 flex items-center gap-5">
            <div class="p-4 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-500">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Monthly Revenue</span>
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ number_format($thisMonthRevenue, 2) }} <span class="text-sm font-medium">BDT</span></span>
            </div>
        </div>

        <!-- Pending Outstanding Due Card -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xl shadow-gray-100/50 dark:shadow-none hover:scale-[1.02] transition duration-200 flex items-center gap-5">
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-500">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Outstanding Due</span>
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ number_format($totalPendingDue, 2) }} <span class="text-sm font-medium">BDT</span></span>
            </div>
        </div>

    </div>

    <!-- Recent Payments Section -->
    <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <!-- Header -->
        <div class="px-6 py-5 sm:px-8 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/10">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Payments</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Real-time listing of the 5 most recent manual subscriber payments collected.</p>
            </div>
            
            <a href="{{ route('payments.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition">
                View All Payments →
            </a>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                <thead class="bg-gray-50/30 dark:bg-gray-800/20 text-gray-500 dark:text-gray-400 text-left text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 sm:px-8">Date & Time</th>
                        <th class="px-6 py-4">Customer Name</th>
                        <th class="px-6 py-4">Payment Method</th>
                        <th class="px-6 py-4">Invoice No</th>
                        <th class="px-6 py-4 text-right">Amount Paid</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    @forelse($recentPayments as $payment)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition duration-150">
                            <!-- Date & Time -->
                            <td class="px-6 py-4 sm:px-8 whitespace-nowrap text-gray-600 dark:text-gray-300 font-medium">
                                {{ $payment->paid_at->format('M d, Y h:i A') }}
                            </td>
                            <!-- Customer Name -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $payment->customer->name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $payment->customer->phone }}
                                </div>
                            </td>
                            <!-- Payment Method pill -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $methodColor = match($payment->payment_method) {
                                        'cash' => 'bg-emerald-50 text-emerald-700 border-emerald-200/55 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-800/30',
                                        'bkash' => 'bg-pink-50 text-pink-700 border-pink-200/55 dark:bg-pink-950/20 dark:text-pink-400 dark:border-pink-800/30',
                                        'nagad' => 'bg-orange-50 text-orange-700 border-orange-200/55 dark:bg-orange-950/20 dark:text-orange-400 dark:border-orange-800/30',
                                        'bank' => 'bg-blue-50 text-blue-700 border-blue-200/55 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-800/30',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200/55 dark:bg-gray-950/20 dark:text-gray-400 dark:border-gray-800/30'
                                    };
                                    $methodLabel = match($payment->payment_method) {
                                        'cash' => '💵 Cash',
                                        'bkash' => '📱 bKash',
                                        'nagad' => '📱 Nagad',
                                        'bank' => '🏦 Bank',
                                        default => strtoupper($payment->payment_method)
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $methodColor }}">
                                    {{ $methodLabel }}
                                </span>
                            </td>
                            <!-- Invoice No -->
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-600 dark:text-gray-300">
                                {{ $payment->invoice->invoice_no }}
                            </td>
                            <!-- Amount Paid -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-gray-900 dark:text-white font-extrabold text-base">
                                BDT {{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State Illustration -->
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="p-4 rounded-full bg-gray-50 dark:bg-gray-800/50 text-gray-400 mb-4">
                                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">No Recent Payments</h4>
                                    <p class="text-xs text-gray-400 mt-1">There are no payment collection transactions recorded in the system database yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>

</div>
