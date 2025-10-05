<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function PHPUnit\Framework\isEmpty;

class UserDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->ask('Ваш  email?');
        if(empty($email)){
            return Command::FAILURE;
        }

        $user = User::where('email', '=', $email)->first();
        $user->delete();
        $this->info("User  deleted successfully.");
        return Command::SUCCESS;
    }
}
