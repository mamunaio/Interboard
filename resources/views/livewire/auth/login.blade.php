<div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    
    <!-- Background Radial Gradients for Premium Visual Glow -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-200 dark:bg-indigo-900/10 rounded-full blur-3xl opacity-40 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-200 dark:bg-purple-900/10 rounded-full blur-3xl opacity-40 translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md z-10 space-y-6">
        
        <!-- Branding Logo / Text Block -->
        <div class="text-center">
            <div class="inline-flex items-center gap-3 bg-white dark:bg-gray-900 px-4 py-2 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm mb-4">
                <span class="flex h-6 w-6 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-6 w-6 bg-indigo-500 flex items-center justify-center">
                        <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                </span>
                <span class="text-lg font-black tracking-wider text-gray-900 dark:text-white uppercase font-mono">Interboard</span>
            </div>
            
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Welcome back</h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Please sign in to access the ISP administration dashboard.</p>
        </div>

        <!-- Glassmorphism Card Wrapper -->
        <div class="bg-white dark:bg-gray-900/90 py-8 px-6 sm:px-10 shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-850 rounded-3xl backdrop-blur-md">
            
            <form wire:submit.prevent="authenticate" class="space-y-6">
                
                <!-- Email Address Input -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </span>
                        
                        <input type="email" id="email" wire:model="email" placeholder="admin@interboard.com" autocomplete="email" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    </div>
                    @error('email') 
                        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        
                        <input type="password" id="password" wire:model="password" placeholder="••••••••" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    </div>
                    @error('password') 
                        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Remember Me and Helper Links -->
                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center text-gray-600 dark:text-gray-400 cursor-pointer select-none">
                        <input type="checkbox" wire:model="remember"
                            class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-2 transition duration-150">
                        <span class="ml-2 text-xs font-semibold">Remember me</span>
                    </label>

                    <a href="#" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition">
                        Forgot Password?
                    </a>
                </div>

                <!-- Sign In Button with processing loader -->
                <button type="submit" 
                    wire:loading.attr="disabled"
                    wire:target="authenticate"
                    class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-lg shadow-indigo-500/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    
                    <!-- Search Loading Spinner -->
                    <svg wire:loading wire:target="authenticate" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <span wire:loading.remove wire:target="authenticate">Sign In</span>
                    <span wire:loading wire:target="authenticate">Authenticating...</span>
                </button>

            </form>

        </div>
        
    </div>

</div>
