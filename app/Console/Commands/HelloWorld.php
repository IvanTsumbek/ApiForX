<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HelloWorld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hello:user {name} {--green} {--red}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Простая команда, выводящая Hello World';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $greeting = "Привет, $name!";

        if ($this->option('green')) {
            $this->info($greeting);
        } if ($this->option('red')) {
            $this->error($greeting);
        } else {
            $this->line("$greeting");
        }    
    }
}
