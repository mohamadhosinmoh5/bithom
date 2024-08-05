<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BackgroundTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mobile;
    /**
     * Create a new job instance.
     */
    public function __construct($mobile)
    {
        $this->mobile = $mobile;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = new User;
        $user->mobile = $this->mobile;
        $user->otp = random_int(1000, 9999);
        $user->save();
    }
}
