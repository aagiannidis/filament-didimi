<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\AccountSeeder;

class SeedAccounts extends Command
{
    //protected $signature = 'app:seed-accounts';
    protected $signature = 'db:seed-accounts {--jobSize=0 : How many accounts to create (max=100)} {--source=default : Specify either local or unsplash. Local will randomly generate images from public disk and profile-images folder, unsplash will fetch from the unsplash API} {--column=photo : Specify the table column to hold the path to the image}';
    protected $description = 'Seed the accounts table with a specified number of records. You can specify the job';
    
    public function handle()
    { 
              
        $jobSize = (int) $this->option('jobSize');
        // Validate jobSize
        if ($jobSize < 0 || $jobSize > 100) {
            $this->error('Invalid jobSize. Please specify a value between 0 and 100.');
            return 1; 
        }

        // Validate source
        $validSources = ['local', 'unsplash', 'default'];
        if (!in_array($this->option('source'), $validSources)) {
            $this->error('Invalid source. Please specify either "local", "unsplash", or "default".');
            return 1; 
        }

        $seeder = new AccountSeeder($jobSize, $this->option('source'), $this->option('column'));
        $seeder->setCommand($this);
        $seeder->run();
    }
}
