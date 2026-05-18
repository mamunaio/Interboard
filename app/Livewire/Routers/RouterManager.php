<?php

declare(strict_types=1);

namespace App\Livewire\Routers;

use App\Models\MikrotikRouter;
use App\Services\MikrotikService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\Validate;
use ReflectionClass;
use Exception;

class RouterManager extends Component
{
    // Collection to store loaded routers list
    public Collection $routers;

    // Form inputs with validation attributes
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|ip')]
    public string $ip_address = '';

    #[Validate('required|integer|between:1,65535')]
    public int $api_port = 8728;

    #[Validate('required|string|max:255')]
    public string $api_username = '';

    #[Validate('required|string')]
    public string $api_password = '';

    /**
     * Mount the component and load all routers.
     */
    public function mount(): void
    {
        $this->loadRouters();
    }

    /**
     * Helper to reload routers from the database.
     */
    public function loadRouters(): void
    {
        $this->routers = MikrotikRouter::all();
    }

    /**
     * Register a new MikroTik router configuration.
     */
    public function save(): void
    {
        $this->validate();

        MikrotikRouter::create([
            'name' => $this->name,
            'ip_address' => $this->ip_address,
            'api_port' => $this->api_port,
            'api_username' => $this->api_username,
            'api_password' => $this->api_password,
            'status' => true, // default active configuration
        ]);

        $this->loadRouters();

        // Reset the form input properties
        $this->reset(['name', 'ip_address', 'api_username', 'api_password']);
        $this->api_port = 8728;

        session()->flash('success', 'New MikroTik router configuration registered successfully.');
    }

    /**
     * Test the connection to a specific router.
     *
     * @param int $routerId
     * @param MikrotikService|null $mikrotikService Injectable helper (constructed locally due to contextual model parameters)
     */
    public function testConnection(int $routerId, ?MikrotikService $mikrotikService = null): void
    {
        $router = MikrotikRouter::findOrFail($routerId);

        // Instantiate the service dynamically with the target router instance
        $service = new MikrotikService($router);

        try {
            // Utilize reflection to safely invoke and verify the private connect() handshake
            $reflector = new ReflectionClass($service);
            $method = $reflector->getMethod('connect');
            $method->setAccessible(true);
            $method->invoke($service);

            // Connection succeeded, flag status as true
            $router->update(['status' => true]);
            session()->flash('success', "API connection verified successfully for router '{$router->name}'. Status: ONLINE.");
        } catch (Exception $e) {
            // Connection failed, flag status as false
            $router->update(['status' => false]);
            session()->flash('error', "API connection failed for router '{$router->name}'. Status: OFFLINE. Error: " . $e->getMessage());
        }

        // Reload lists to render updated online badges
        $this->loadRouters();
    }

    /**
     * Render the Livewire template.
     */
    public function render()
    {
        return view('livewire.routers.router-manager');
    }
}
