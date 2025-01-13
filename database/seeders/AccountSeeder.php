<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public int $maxRecords = 10;
    public int $jobSize = 0;
    public string $source = 'default';
    // the path to images is under wherever the unsplash disk has been set to
    public string $pathToImages = 'accounts/profile-photos/';
    public string $photoColumnName = 'photo';

    public function __construct($jobSize = 0, $source = 'default', $photoColumnName = 'photo') {
        $this->jobSize = $jobSize;
        $this->source = $source;
        $this->photoColumnName = $photoColumnName;
    } 

    protected function dumpToFile($filename, $data): void
    {
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
    }

    protected function echoAssociativeArray($data): void
    {
        foreach($data as $key => $value) {
            echo($key.' => '.$value."\n");
        }
    }

    protected function echoSimpleArrayWithIndexing($data): void
    {        
        foreach($data as $index => $datum) {
            echo(("{'id':'".$index."' ,'value':'".$datum."'},\n\r"));            
        }
    }

    public function run(): void
    {

        $inpjobSize = 0;

        if ($this->jobSize===0) {
            $inpjobSize = (int)$this->command->ask('Please enter how many accounts to create (max='.$this->maxRecords.')...');

            if ($inpjobSize > $this->maxRecords) {
                $this->command->error('Sorry but you have specified more than then allowable number of accounts to create...');    
                return;
            }

            if ($inpjobSize < 1) {
                $this->command->error('Please specify a positive integer value for the number of records to be created...');    
                return;
            }
        }
        
        $this->jobSize = $inpjobSize;
        
        $this->command->info('Will now create '.$this->jobSize.' accounts...');

        
        
        $validSources = ['local', 'unsplash', 'default'];
        if (!in_array($this->source, $validSources)) {
            $this->error('Invalid images source provider specified. Please specify either "local", "unsplash", or "default". Will continue with default...');
            $this->source = 'default';
        }

        if ($this->source==='unsplash') {
            $this->command->info('We will be fetching from unsplash once every 5 seconds so that we dont get banned...');
        }

        $this->command->getOutput()->progressStart($this->jobSize);

        for ($i = 0; $i < $this->jobSize; $i++) {
        
            try {
                $newaccount = Account::factory()
                    //->count(1)    # if you use count, it will return a collection
                    ->withSpecialParams($this->command, $this->source, $this->pathToImages, $this->photoColumnName)                    
                    // ->suspended()    # set some properties
                    // ->state([        # of add dynamically externally here
                    //     'last_name' => 'Abigail Otwell',
                    // ])
                    ->make();
                
                $newUser = User::factory()->create([
                    'name' => $newaccount->first_name." ".$newaccount->last_name,
                    'email' => 'aa'.rand(0,1000).'@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                ]);
                
                $newaccount->user()->associate($newUser)->save();
                
            } catch (\Exception $e) {
                 $this->command->error("\n".'Seeder exception:'.$e->getMessage());                
                 break;
            }      
            
            // If Unsplash is the source, then sleep for 5 seconds to avoid rate-limiting...
            sleep((($i>0)&&($this->source==='unsplash'))?5:0);

            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
        $this->command->info('Task completed successfully...');
    }
}
