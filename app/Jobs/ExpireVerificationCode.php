<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// Models
use App\Models\User;

class ExpireVerificationCode implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user) {
        $this -> user = $user;
    }

    public function handle(): void {

        User::create([
            'name' => 'test',
            'email' => 'test' . rand(1, 10000000) . '@gmail.com',
            'password' => '01230123'
        ]);

        // if ($this->user && $this->user->expiration_time == now()) {
        //     $this->user->delete();
        // }

    }

}
