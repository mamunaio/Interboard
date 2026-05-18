<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6">
    <!-- Breadcrumbs & Heading -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Collect Payment</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Record a manual transaction payment against unpaid subscriber invoices.</p>
    </div>

    <!-- Alert Success Notification -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/30 dark:border-emerald-800/50 flex items-center justify-between text-emerald-800 dark:text-emerald-300">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <!-- Header Billing Details Summary (Premium SaaS Card Section) -->
        <div class="bg-gradient-to-r from-indigo-500 to-violet-600 p-6 sm:p-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-100">Invoice Number</span>
                    <h2 class="text-xl font-bold mt-0.5">{{ $invoice->invoice_no }}</h2>
                    
                    <div class="flex items-center gap-4 mt-4">
                        <div>
                            <span class="block text-xs text-indigo-100">Billing Month</span>
                            <span class="text-sm font-semibold">{{ $invoice->billing_month }}</span>
                        </div>
                        <div class="w-px h-8 bg-white/20"></div>
                        <div>
                            <span class="block text-xs text-indigo-100">Subscriber</span>
                            <span class="text-sm font-semibold">{{ $invoice->customer->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-100 block">Total Due</span>
                    <span class="text-3xl font-black">{{ number_format((float) $invoice->amount, 2) }} <span class="text-lg font-medium">BDT</span></span>
                    
                    <!-- Suspended Notice if Customer is Suspended -->
                    @if($invoice->customer->status->value === 'suspended')
                        <span class="inline-flex items-center gap-1 mt-3 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-100 border border-rose-400/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                            Suspended (Auto-unbans on Pay)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <form wire:submit.prevent="save" class="p-6 sm:p-8 space-y-6">
            
            <!-- Collection Input Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Payment Amount -->
                <div class="md:col-span-2">
                    <label for="amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Collected Amount (BDT)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-medium">BDT</span>
                        <input type="number" id="amount" wire:model="amount" step="0.01" min="0.01"
                            class="w-full pl-14 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 font-bold text-lg">
                    </div>
                    @error('amount') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Payment Method Select -->
                <div>
                    <label for="payment_method" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Payment Method</label>
                    <select id="payment_method" wire:model.live="payment_method"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 font-semibold">
                        <option value="cash">💵 Cash Payment</option>
                        <option value="bkash">📱 bKash MFS</option>
                        <option value="nagad">📱 Nagad MFS</option>
                        <option value="bank">🏦 Bank Transfer</option>
                    </select>
                    @error('payment_method') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Transaction ID (MFS/Bank) -->
                <div>
                    <label for="transaction_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Transaction ID 
                        @if($payment_method === 'cash')
                            <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                        @else
                            <span class="text-xs text-indigo-500 font-bold">(Required for MFS/Bank)</span>
                        @endif
                    </label>
                    <input type="text" id="transaction_id" wire:model="transaction_id" placeholder="e.g. TRX9834521"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    @error('transaction_id') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Form Footer Actions -->
            <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end items-center gap-4">
                <button type="button" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-150">
                    Cancel
                </button>
                
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-lg shadow-indigo-500/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 flex items-center gap-2">
                    
                    <!-- Processing Spinner -->
                    <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    <span wire:loading.remove wire:target="save">Confirm Payment</span>
                    <span wire:loading wire:target="save">Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
