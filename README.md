
### Playing around Unsplash (Laravel) (5/1/2025)

##### <span style="color:violet">Objective: make it support variable paths to save images to. By default it saves to the same disk and same path.</span>

Changes made to marksito's laravel unsplash in order to support a path
definition where the file must be saved to.

```php
/* Thanos - Custom changes here - 5/1/2025 - start */

$dirname = (!(empty($name))) ? parsePath($name)['directory'] : '';        
$name = $name ? parsePath($name)['filename'] : Str::random(24) ;        

$name = $name ?? Str::random(24);
$image = file_get_contents($response['urls'][$key]);

while ($this->storage->exists("{$dirname}{$name}.jpg")) {
    $name = Str::random(24);
}

$this->storage->put("{$dirname}{$name}.jpg", $image);

/* Thanos - Custom changes here - 5/1/2025 - end   */

```

The parsePath function is defined in the FileHelpers.php file. This is a custom 
file with global functions. Please follow instructions on how to make such functions
globally available throughout the project.

```php
if (!function_exists('parsePath')) {
/**
 * Determine if the input is a directory path or a directory path with a filename.
 *
 * @param string $input
 * @return array
 */

    function parsePath($input)
    {
        $pathInfo = pathinfo($input);

        // Check if the input has a filename
        if (isset($pathInfo['extension'])) {
            // Input contains a filename
            return [
                'directory' => $pathInfo['dirname'],
                'filename' => $pathInfo['basename'],
            ];
        } else {
            // Input is only a directory path
            return [
                'directory' => $input,
                'filename' => null,
            ];
        }
    }
}
```

### Playing around with factories (5/1/2025)

* We have the accounts factory that is set to produce random details from some json files.
So as not to read the files everytime, we set all arrays as static. Therefore everytime the factory is called (either in a loop with ->count(1) or with ->count(100)) it will first populate the info and then wont waste any cpu time on doing it again. Had a lot of issues but solved them. 
* Also check the Account factory to see the right way of passing some parameters to the factory. We cannot override the constructor as it is not supported in Laravel.

#### Μεταφράσεις (6/1/2025)

* Publish the translations into the lang folder (if not exist)
```php
> php artisan lang:publish
```
* Install Filament-language-switcher from here: https://filamentphp.com/plugins/bezhansalleh-language-switch#requirement
* Throw most of your translations in a single json file named gr.json under the lang folder
```php
    {
        "title": "τιτλος",
        "vehicle_fault_template_id":"Τυποποιημένοι τύποι βλαβών"
    }
```

* In your forms or where-ever, refer to each string as
```php
    
    __('title')
    // for each field in the forms or tables
    ->label(__('title'))
    // or 
    ->translateLabel()

```

```php
 // List of available static properties that can be overridden
    protected static string $resource;
    protected static ?string $title = null;
    protected static ?string $breadcrumb = null;
    protected static ?string $modelLabel = null;
    protected static ?string $pluralModelLabel = null;
    protected static ?string $navigationLabel = null;
    protected static ?string $navigationGroup = null;
    protected static ?string $navigationIcon = null;
    protected static ?string $navigationSort = null;
    protected static ?string $navigationBadge = null;
    protected static ?string $navigationBadgeColor = null;
    protected static ?string $navigationUrl = null;
    protected static ?string $navigationLabelPrefix = null;
    protected static ?string $navigationLabelSuffix = null;
    protected static ?string $navigationLabelIcon = null;
    protected static ?string $navigationLabelIconColor = null;
    protected static ?string $navigationLabelIconPosition = null;
    protected static ?string $navigationLabelIconSize = null;
    protected static ?string $navigationLabelIconWeight = null;
    protected static ?string $navigationLabelIconStyle = null;
    protected static ?string $navigationLabelIconClass = null;
    protected static ?string $navigationLabelIconAttributes = null;
    protected static ?string $navigationLabelIconUrl = null;
    protected static ?string $navigationLabelIconTarget = null;
    protected static ?string $navigationLabelIconRel = null;
    protected static ?string $navigationLabelIconTitle = null;
    protected static ?string $navigationLabelIconAlt = null;
    protected static ?string $navigationLabelIconWidth = null;
    protected static ?string $navigationLabelIconHeight = null;
    protected static ?string $navigationLabelIconViewBox = null;
    protected static ?string $navigationLabelIconPreserveAspectRatio = null;
    protected static ?string $navigationLabelIconFill = null;
    protected static ?string $navigationLabelIconStroke = null;
    protected static ?string $navigationLabelIconStrokeWidth = null;
    protected static ?string $navigationLabelIconStrokeLinecap = null;
    protected static ?string $navigationLabelIconStrokeLinejoin = null;
    protected static ?string $navigationLabelIconStrokeMiterlimit = null;
    protected static ?string $navigationLabelIconStrokeDasharray = null;
    protected static ?string $navigationLabelIconStrokeDashoffset = null;
    protected static ?string $navigationLabelIconStrokeOpacity = null;
    protected static ?string $navigationLabelIconFillOpacity = null;
    protected static ?string $navigationLabelIconFillRule = null;
    protected static ?string $navigationLabelIconClipRule = null;
    protected static ?string $navigationLabelIconMask = null;
    protected static ?string $navigationLabelIconTransform = null;
    ```