<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;


class DeleteExpiredUsers extends Command {

    protected $signature = 'php artisan users:delete-expired';
    protected $description = 'Deleting Expired Users';

    public function handle() {
        User::whereNull('email_verified_at')
            -> whereRaw('DATE(created_at) != CURDATE()')
            -> delete();
    }

}
