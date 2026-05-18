<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
    
    <!-- Heading -->
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">MikroTik Router Manager</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add new MikroTik Router configurations and check their real-time API connection status.</p>
    </div>

    <!-- Alert Notifications -->
    @if (session()->has('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/30 dark:border-emerald-800/50 flex items-center gap-3 text-emerald-800 dark:text-emerald-300">
            <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @elseif (session()->has('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/30 dark:border-rose-800/50 flex items-center gap-3 text-rose-800 dark:text-rose-300">
            <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- 2-Column Responsive Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Section A: Add New Router Form (Left Column - 1/3) -->
        <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800 p-6 sm:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Add New Router</h2>
                <p class="text-xs text-gray-400 mt-1">Configure credentials for API communication with the MikroTik RouterOS.</p>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <!-- Router Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Router Name</label>
                    <input type="text" id="name" wire:model="name" placeholder="Core Router - Dhaka"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    @error('name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- IP Address -->
                <div>
                    <label for="ip_address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">IP Address</label>
                    <input type="text" id="ip_address" wire:model="ip_address" placeholder="192.168.88.1"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    @error('ip_address') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- API Port -->
                <div>
                    <label for="api_port" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">API Port</label>
                    <input type="number" id="api_port" wire:model="api_port" placeholder="8728"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    @error('api_port') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="api_username" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">API Username</label>
                    <input type="text" id="api_username" wire:model="api_username" placeholder="admin"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    @error('api_username') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="api_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">API Password</label>
                    <input type="password" id="api_password" wire:model="api_password" placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    @error('api_password') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-lg shadow-indigo-500/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 mt-4 flex items-center justify-center gap-2">
                    <svg class="h-5 w-5 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Register Router
                </button>
            </form>
        </div>

        <!-- Section B: Configured Routers List (Right Column - 2/3) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-5 sm:px-8 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/10">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Configured MikroTik Routers</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">List of routers configured for automated PPPoE subscriber synchronization.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                    <thead class="bg-gray-50/30 dark:bg-gray-800/20 text-gray-500 dark:text-gray-400 text-left text-xs uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 sm:px-8">Router Details</th>
                            <th class="px-6 py-4">IP Address & Port</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                        @forelse($routers as $router)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition duration-150">
                                
                                <!-- Router details -->
                                <td class="px-6 py-4 sm:px-8 whitespace-nowrap">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $router->name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Username: {{ $router->api_username }}</div>
                                </td>
                                
                                <!-- IP Address & Port -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300 font-semibold font-mono">
                                    {{ $router->ip_address }}<span class="text-gray-400 font-normal">:{{ $router->api_port }}</span>
                                </td>
                                
                                <!-- Status Badge -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($router->status)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/50 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-800/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            ONLINE
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/50 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-800/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            OFFLINE
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Action Buttons -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                    <button type="button" 
                                        wire:click="testConnection({{ $router->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="testConnection({{ $router->id }})"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/20 hover:bg-indigo-100/60 dark:hover:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed font-semibold">
                                        
                                        <!-- Connection testing Spinner specific to clicked item -->
                                        <svg wire:loading wire:target="testConnection({{ $router->id }})" class="animate-spin h-3.5 w-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        
                                        <span wire:loading.remove wire:target="testConnection({{ $router->id }})">⚡ Test Connection</span>
                                        <span wire:loading wire:target="testConnection({{ $router->id }})">Testing...</span>
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <!-- Empty State -->
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <div class="max-w-sm mx-auto flex flex-col items-center">
                                        <div class="p-4 rounded-full bg-gray-50 dark:bg-gray-800/50 text-gray-400 mb-4">
                                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">No Routers Registered</h4>
                                        <p class="text-xs text-gray-400 mt-1">Add your first MikroTik Router configuration in the form to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
        
    </div>

</div>
