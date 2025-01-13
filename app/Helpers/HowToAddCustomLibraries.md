To create a library of custom PHP functions that you can call throughout your Laravel project, you can follow these steps:

1. **Create a Helpers File:**
   Create a new file for your custom functions. A common place to put this file is in the app directory. You can name it `helpers.php` or any other name you prefer.

   ```sh
   touch app/helpers.php
   ```

2. **Define Your Custom Functions:**
   Add your custom functions to the `helpers.php` file.

   ```php
   // filepath: /C:/www/filament-tickets-demo-site/app/helpers.php

   if (!function_exists('getJsonDataFromUrl')) {
       function getJsonDataFromUrl($url): array
       {
           $json = file_get_contents($url);
           return json_decode($json, true);
       }
   }

   if (!function_exists('getJsonDataFromApiWithToken')) {
       function getJsonDataFromApiWithToken($url, $token): array
       {
           $opts = [
               "http" => [
                   "method" => "GET",
                   "header" => "Authorization: Bearer " . $token
               ]
           ];
           $context = stream_context_create($opts);
           $json = file_get_contents($url, false, $context);
           return json_decode($json, true);
       }
   }

   if (!function_exists('getJsonDataFromApiWithTokenAndPost')) {
       function getJsonDataFromApiWithTokenAndPost($url, $token, $data): array
       {
           $opts = [
               "http" => [
                   "method" => "POST",
                   "header" => "Authorization: Bearer " . $token . "\r\n" .
                               "Content-Type: application/json\r\n",
                   "content" => json_encode($data)
               ]
           ];
           $context = stream_context_create($opts);
           $json = file_get_contents($url, false, $context);
           return json_decode($json, true);
       }
   }
   ```

3. **Autoload the Helpers File:**
   To make sure your custom functions are available throughout your Laravel project, you need to autoload the `helpers.php` file. You can do this by adding it to the composer.json file.
   Open the composer.json file and add the `helpers.php` file to the `autoload` section:

   ```json
   "autoload": {
       "files": [
           "app/helpers.php"
       ]
   }
   ```

4. **Update Composer Autoload:**
   After modifying the composer.json file, run the following command to update the Composer autoload files:

   ```sh
   composer dump-autoload
   ```

5. **Use Your Custom Functions:**
   Now you can use your custom functions anywhere in your Laravel project.

   ```php
   // Example usage in a controller or seeder
   $data = getJsonDataFromUrl('https://example.com/api/data');
   ```

By following these steps, you can create a library of custom PHP functions that are easily accessible throughout your Laravel project. This approach keeps your code organized and reusable.

