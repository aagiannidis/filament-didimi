<?php
namespace Database\Factories;

use Faker;
use Carbon\Carbon;
use App\Models\User;
use App\Enums\Gender;
use App\Models\Account;
use Illuminate\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use MarkSitko\LaravelUnsplash\Unsplash;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Database\Eloquent\Factories\Factory;


class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;
    
    public static $maleNames = null;
    public static $maleSurnames = null;
    public static $femaleNames = null;
    public static $femaleSurnames = null;

    public static $unsplashObj = null;

    public $pCommand;
    public $pSource='local';
    public $pLocal_path='accounts/profile-photos/';
    public $pColumn_name='photo';

    protected function loadDataset($jsonDataFile='database/data/GreekFemaleNames.json'): array
    {
        $jsonDecodedData = getJsonData(base_path($jsonDataFile));
        return array_map(function($item) {
            return $item['name'];
        }, $jsonDecodedData);
    }

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $faker = Faker\Factory::create('el_GR');

        if (is_null(self::$maleNames)) {                    
            self::$maleNames = $this->loadDataset('database/data/GreekMaleNames.json');
        }
        if (is_null(self::$maleSurnames)) {
            self::$maleSurnames = $this->loadDataset('database/data/GreekMaleSurnames.json');
        }
        if (is_null(self::$femaleNames)) {
            self::$femaleNames = $this->loadDataset('database/data/GreekFemaleNames.json');
        }
        if (is_null(self::$femaleSurnames)) {
            self::$femaleSurnames = $this->loadDataset('database/data/GreekFemaleSurnames.json');
        }
        if (is_null(self::$unsplashObj)) {
            self::$unsplashObj = new Unsplash();
        }

        $imagesFolderPath = storage_path('app/public/'.$this->pLocal_path);
                
        Storage::makeDirectory('public/'.$this->pLocal_path);

        $randomGender = Arr::random(Gender::values());
        $randomFemaleName = self::$femaleNames[rand(1,count(self::$femaleNames)-1)];
        $randomFemaleSurname = self::$femaleSurnames[rand(1,count(self::$femaleSurnames)-1)];
        $randomMaleName = self::$maleNames[rand(1,count(self::$maleNames)-1)];
        $randomMaleSurname = self::$maleSurnames[rand(1,count(self::$maleSurnames)-1)];

        $theNameFromTheStoredPhoto = 'NotFound.jpg';
        
        if ($this->pSource==='local') {

            $all_files = [];

            try {                
                $all_files = File::files($imagesFolderPath, false);

                if (count($all_files)===0) {
                    $error_message = 'No files found in '.$imagesFolderPath;
                    $this->pCommand->error("\n".'AccountFactory::'.$error_message);
                    throw new \Exception($error_message);
                }
                $theNameFromTheStoredPhoto = pathinfo($all_files[rand(0,count($all_files)-1)])['basename'];
            } catch (DirectoryNotFoundException $e) {
                $error_message = $e->getMessage();
                $this->pCommand->error("\n".'AccountFactory::'.$e->getMessage());
                throw new \Exception($error_message);
            }
        }

        if ($this->pSource==='unsplash') {
            $termsForUnsplash = $randomGender.",portrait";    
            $theNameFromTheStoredPhoto = self::$unsplashObj->randomPhoto()
                ->orientation('portrait')
                ->term($termsForUnsplash)
                ->randomPhoto()
                ->store($this->pLocal_path); 
        }

        return [
            'first_name' => ($randomGender==Gender::MALE) ? $randomMaleName : $randomFemaleName,
            'last_name' => ($randomGender==Gender::MALE) ? $randomMaleSurname : $randomFemaleSurname,
            'date_of_birth' => Carbon::createFromTimestamp(mt_rand(Carbon::now()->subYear(70)->timestamp, Carbon::now()->subYear(25)->timestamp))->toDateString(),
            'gender' => $randomGender,
            $this->pColumn_name => $theNameFromTheStoredPhoto,
            'mobile_phone' => $faker->phoneNumber('+3069########'),
            'home_phone' => $faker->phoneNumber('+30210#######'),
            'work_phone' => $faker->phoneNumber('+30213#######'),
            'user_id' => null
        ];
    }

    public function withSpecialParams($command, $source='local', $local_path='', $column_name='photo')
    {
        $this->pCommand = $command;
        $this->pSource = $source;
        $this->pLocal_path = $local_path;
        $this->pColumn_name = $column_name;

        return $this;
    }
   
}
