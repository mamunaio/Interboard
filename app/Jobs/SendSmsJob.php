<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\SmsService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
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
    public array $backoff = [10, 30];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $phone,
        public string $message
    ) {}

    /**
     * Execute the job.
     *
     * @param SmsService $smsService Resolved automatically from container
     * @throws Exception If SMS service fails to send, triggering retry.
     */
    public function handle(SmsService $smsService): void
    {
        $status = $smsService->send($this->phone, $this->message);

        // Throwing exception forces Laravel to record failure and retry according to backoff rules
        if (!$status) {
            throw new Exception("Failed to send queued SMS to recipient '{$this->phone}'. Gateway rejected request.");
        }
    }
}
