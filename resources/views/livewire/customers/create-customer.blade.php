<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumbs & Heading -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Customer</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Register a new subscriber and automatically sync their credentials to the selected MikroTik router.</p>
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

    <form wire:submit.prevent="save" class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-6 sm:p-8 space-y-8">
            
            <!-- Section 1: Personal Info -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-6">
                    Personal Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                        <input type="text" id="name" wire:model="name" placeholder="John Doe"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                        <input type="text" id="phone" wire:model="phone" placeholder="01712345678"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        @error('phone') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Billing Address</label>
                        <textarea id="address" wire:model="address" rows="3" placeholder="123 Main St, Dhaka"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"></textarea>
                        @error('address') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: PPPoE Credentials & Router Integration -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-6">
                    Connection & Hardware Settings
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- PPPoE Username -->
                    <div>
                        <label for="pppoe_username" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">PPPoE Username</label>
                        <input type="text" id="pppoe_username" wire:model="pppoe_username" placeholder="pppoe_john"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        @error('pppoe_username') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- PPPoE Password -->
                    <div>
                        <label for="pppoe_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">PPPoE Password</label>
                        <input type="text" id="pppoe_password" wire:model="pppoe_password" placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        @error('pppoe_password') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Mikrotik Router Select -->
                    <div>
                        <label for="mikrotik_router_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">MikroTik Router</label>
                        <select id="mikrotik_router_id" wire:model="mikrotik_router_id"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                            <option value="">Select a Router</option>
                            @foreach($routers as $router)
                                <option value="{{ $router->id }}">{{ $router->name }} ({{ $router->ip_address }})</option>
                            @endforeach
                        </select>
                        @error('mikrotik_router_id') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Internet Package Select -->
                    <div>
                        <label for="internet_package_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Internet Package</label>
                        <select id="internet_package_id" wire:model="internet_package_id"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                            <option value="">Select a Package</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }} - {{ number_format($package->price, 2) }} BDT</option>
                            @endforeach
                        </select>
                        @error('internet_package_id') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Billing Info -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-6">
                    Billing & Subscription
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Billing Day -->
                    <div>
                        <label for="billing_day" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Billing Day (1-28)</label>
                        <input type="number" id="billing_day" wire:model="billing_day" min="1" max="28" placeholder="5"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        @error('billing_day') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Custom Monthly Bill Override -->
                    <div>
                        <label for="monthly_bill" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Custom Price (Optional)</label>
                        <input type="number" id="monthly_bill" wire:model="monthly_bill" step="0.01" placeholder="Leave empty for default package price"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        @error('monthly_bill') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Connection Date -->
                    <div>
                        <label for="connection_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Connection Date</label>
                        <input type="date" id="connection_date" wire:model="connection_date"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                        @error('connection_date') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

        </div>

        <!-- Form Footer Actions -->
        <div class="px-6 py-4 sm:px-8 bg-gray-50 dark:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800 flex justify-end items-center gap-4">
            <button type="button" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-150">
                Cancel
            </button>
            
            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-lg shadow-indigo-500/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 flex items-center gap-2">
                <!-- Saving Spinner -->
                <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">Save Customer</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>
