<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\SackMovement;

class ProcessSackDetection implements ShouldQueue
{
    use Queueable;
    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        SackMovement::create($this->data);
        \Log::info('Data:', $this->data);
    }
}
