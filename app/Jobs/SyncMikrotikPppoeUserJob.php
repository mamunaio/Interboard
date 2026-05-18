<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Customer;
use App\Services\MikrotikService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;

class SyncMikrotikPppoeUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array<int, int>
     */
    public array $backoff = [10, 30, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Customer $customer,
        public string $action
    ) {
        if (!in_array($this->action, ['add', 'enable', 'disable', 'remove'], true)) {
            throw new InvalidArgumentException("Invalid action '{$this->action}' specified for MikroTik sync job.");
        }
    }

    /**
     * Execute the job.
     *
     * @param MikrotikService $mikrotikService Resolved from the service container.
     * @throws Exception If the synchronization action on the router fails.
     */
    public function handle(MikrotikService $mikrotikService): void
    {
        // Re-instantiate the service using the customer's specific router instance
        // to ensure the connection parameters are correct for this customer.
        $mikrotikService = new MikrotikService($this->customer->mikrotikRouter);

        $result = match ($this->action) {
            'add' => $mikrotikService->addPppoeUser(
                $this->customer->pppoe_username,
                $this->customer->pppoe_password,
                $this->customer->internetPackage->pppoe_profile_name
            ),
            'enable'  => $mikrotikService->enablePppoeUser($this->customer->pppoe_username),
            'disable' => $mikrotikService->disablePppoeUser($this->customer->pppoe_username),
            'remove'  => $mikrotikService->removePppoeUser($this->customer->pppoe_username),
            default   => throw new InvalidArgumentException("Invalid action '{$this->action}' executed inside MikroTik sync job."),
        };

        // If the operation returned false, or an error array containing traps, throw an Exception to trigger a retry.
        if ($result === false || (is_array($result) && !empty($result))) {
            throw new Exception("MikroTik PPPoE sync operation '{$this->action}' failed for customer '{$this->customer->name}' ({$this->customer->pppoe_username}).");
        }
    }
}
