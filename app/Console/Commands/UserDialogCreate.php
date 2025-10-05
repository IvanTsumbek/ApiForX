<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserDialogCreate extends Command
{
    protected $signature = 'user:store';
    protected $description = 'Create user: name email';

    public function handle()
    {
        $name = $this->ask('Ваше  імʼя?');
        $validator = Validator::make(['name' => $name], [
            'name' => 'required|min:3',
        ]);
        if ($validator->fails()) {
            $this->error('Має бути не менше 3 символів');
            return Command::FAILURE;
        }

        $email = $this->ask('Ваш  email?');
        $validator = Validator::make(['email' => $email], [
            'email'    => ['required', 'email', Rule::unique('users', 'email')],
        ]);
        if ($validator->fails()) {
            $this->error('Має бути формат @');
            return Command::FAILURE;
        }

        $password = $this->ask('Пароль?');
        $validator = Validator::make(['password' => $password], [
            'password'   => 'required|min:8',
        ]);
        if ($validator->fails()) {
            $this->error('Має бути не менш 8 символів');
            return Command::FAILURE;
        }
        $password = Hash::make($password);

        User::create([
            'name' => $name,
            'email'    => $email,
            'password'   => $password,
        ]);
        $this->info("User '{$name}' created successfully.");
        return Command::SUCCESS;
    }
}
