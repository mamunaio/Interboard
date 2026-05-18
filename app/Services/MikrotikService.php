<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MikrotikRouter;
use Exception;
use Illuminate\Support\Facades\Log;
use RouterOS\Client;
use RouterOS\Query;

/**
 * Custom exception representing failures to connect to the MikroTik router's API.
 */
class MikrotikConnectionException extends Exception {}

/**
 * Service class responsible for securely managing communication with MikroTik RouterOS.
 */
class MikrotikService
{
    /**
     * Store the active RouterOS client instance.
     */
    private ?Client $client = null;

    /**
     * Instantiate the service with a promoted MikrotikRouter model.
     * The router's password is cast as encrypted in the model and decrypted automatically.
     */
    public function __construct(
        protected MikrotikRouter $router
    ) {}

    /**
     * Establish or retrieve the active MikroTik RouterOS API connection.
     *
     * @throws MikrotikConnectionException
     */
    private function connect(): Client
    {
        if ($this->client !== null) {
            return $this->client;
        }

        try {
            $this->client = new Client([
                'host' => $this->router->ip_address,
                'user' => $this->router->api_username,
                'pass' => $this->router->api_password, // Decrypted automatically by Model casts
                'port' => $this->router->api_port,
                'timeout' => 5,
            ]);

            return $this->client;
        } catch (Exception $e) {
            $errorMessage = "Failed to connect to MikroTik router '{$this->router->name}' at {$this->router->ip_address}:{$this->router->api_port}. Error: " . $e->getMessage();
            
            Log::critical($errorMessage, [
                'router_id' => $this->router->id,
                'exception' => $e
            ]);

            throw new MikrotikConnectionException($errorMessage, (int) $e->getCode(), $e);
        }
    }

    /**
     * Add a new PPPoE user secret on the MikroTik router.
     *
     * @return bool|array Returns true on success, or the raw error array response if a trap occurs.
     */
    public function addPppoeUser(string $username, string $password, string $profile): bool|array
    {
        try {
            $client = $this->connect();

            $query = (new Query('/ppp/secret/add'))
                ->equal('name', $username)
                ->equal('password', $password)
                ->equal('service', 'pppoe')
                ->equal('profile', $profile);

            $response = $client->query($query)->read();

            if ($this->hasTrapError($response)) {
                Log::error("Failed to add PPPoE user {$username} on router {$this->router->name}", [
                    'response' => $response
                ]);
                return $response;
            }

            return true;
        } catch (Exception $e) {
            Log::error("MikroTik addPppoeUser Exception: " . $e->getMessage(), [
                'username' => $username,
                'router_id' => $this->router->id
            ]);
            return false;
        }
    }

    /**
     * Enable an existing PPPoE user secret.
     */
    public function enablePppoeUser(string $username): bool
    {
        try {
            $client = $this->connect();
            $id = $this->getSecretId($username);

            if ($id === null) {
                Log::warning("PPPoE user {$username} not found on router {$this->router->name} to enable.");
                return false;
            }

            $query = (new Query('/ppp/secret/enable'))
                ->equal('.id', $id);

            $response = $client->query($query)->read();
            return !$this->hasTrapError($response);
        } catch (Exception $e) {
            Log::error("MikroTik enablePppoeUser Exception: " . $e->getMessage(), [
                'username' => $username,
                'router_id' => $this->router->id
            ]);
            return false;
        }
    }

    /**
     * Disable an existing PPPoE user secret and immediately disconnect their active session.
     */
    public function disablePppoeUser(string $username): bool
    {
        try {
            $client = $this->connect();
            $id = $this->getSecretId($username);

            if ($id === null) {
                Log::warning("PPPoE user {$username} not found on router {$this->router->name} to disable.");
                return false;
            }

            $query = (new Query('/ppp/secret/disable'))
                ->equal('.id', $id);

            $response = $client->query($query)->read();

            // Kick user from active connections to apply the disable immediately
            $this->disconnectActiveUser($username);

            return !$this->hasTrapError($response);
        } catch (Exception $e) {
            Log::error("MikroTik disablePppoeUser Exception: " . $e->getMessage(), [
                'username' => $username,
                'router_id' => $this->router->id
            ]);
            return false;
        }
    }

    /**
     * Remove an existing PPPoE user secret and immediately terminate their active session.
     */
    public function removePppoeUser(string $username): bool
    {
        try {
            $client = $this->connect();
            $id = $this->getSecretId($username);

            if ($id === null) {
                Log::warning("PPPoE user {$username} not found on router {$this->router->name} to remove.");
                return false;
            }

            $query = (new Query('/ppp/secret/remove'))
                ->equal('.id', $id);

            $response = $client->query($query)->read();

            // Kick user from active connections to terminate active sessions
            $this->disconnectActiveUser($username);

            return !$this->hasTrapError($response);
        } catch (Exception $e) {
            Log::error("MikroTik removePppoeUser Exception: " . $e->getMessage(), [
                'username' => $username,
                'router_id' => $this->router->id
            ]);
            return false;
        }
    }

    /**
     * Terminate the active session of a PPPoE user.
     */
    public function disconnectActiveUser(string $username): bool
    {
        try {
            $client = $this->connect();

            $query = (new Query('/ppp/active/print'))
                ->where('name', $username);

            $response = $client->query($query)->read();

            if (!empty($response) && isset($response[0]['.id'])) {
                $removeQuery = (new Query('/ppp/active/remove'))
                    ->equal('.id', $response[0]['.id']);

                $client->query($removeQuery)->read();
                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::warning("MikroTik active user {$username} could not be disconnected: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper method to fetch the internal database ID of a PPPoE secret by username.
     */
    private function getSecretId(string $username): ?string
    {
        try {
            $client = $this->connect();

            $query = (new Query('/ppp/secret/print'))
                ->where('name', $username);

            $response = $client->query($query)->read();

            if (!empty($response) && isset($response[0]['.id'])) {
                return $response[0]['.id'];
            }
        } catch (Exception $e) {
            Log::error("MikroTik failed to fetch secret ID for {$username}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Helper to verify if the RouterOS API response array contains any error traps.
     */
    private function hasTrapError(array $response): bool
    {
        foreach ($response as $item) {
            if (is_array($item) && (isset($item['!trap']) || isset($item['category']))) {
                return true;
            }
        }
        return false;
    }
}
