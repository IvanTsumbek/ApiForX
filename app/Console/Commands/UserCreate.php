<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserCreate extends Command
{
    protected $signature = 'user:create {name} {email} {password}';
    protected $description = 'Create user: name email';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $user = [
            'name' => $name,
            'email' => $email,
            'password' =>  $password
        ];

        $validator = Validator::make($user, [
            'name' => 'required|min:3',
            'email'    => ['required', 'email', Rule::unique('users', 'email')],
            'password'   => 'required|min:8',
        ], [
            'name.min' => 'Має бути не менше 3 символів',
            'email.email'  => 'Має бути формат @',
            'password.min'   => 'має бути не менш 8 символів',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $msg) {
                $this->error($msg);
            }
            return Command::FAILURE;
        }
        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        $this->info("User '{$validated['name']}' created successfully.");
        return Command::SUCCESS;
    }
}
