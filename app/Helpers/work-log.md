### 6/2/2025

#### How to setup mysqldump to work in windows with spatie backup

```php
Add a line to .env (change the path to the correct path - in linux type which mysqldump)

    (linux) DB_MYSQLDUMP_PATH=/usr/local/mysql/bin
    (windows) DB_MYSQLDUMP_PATH="C:/Program Files/MySQL/MySQL Server 8.0/bin/"

and then under mysql in config/database.php, add the following

    'dump' => [
        'dump_binary_path' => env('DB_MYSQLDUMP_PATH'),
    ],
```

### 5/2/2025

#### How to embed custom blade views into a resource form.

I added a custom view (resources->views->custom->models.blade.php) and injected it into the VehicleModels resource form. It shows up only in viewing mode. Data are being passed directly onto the view, or alternatively the view can use the $getRecord() to do anything it wants with the actual record that the form is showing.

```php
...resources\views\custom\models.blade.php

@php
    // either pass the $models from the resource form
    dd($models);
    // or obtain the record and do stuff with it
    $models = $getRecord()->toArray();
    dd($models);
@endphp

@if(is_array($models) && count($models))
    <ul class="space-y-2">
        @foreach($models as $item)
            <li class="flex items-center">
                {{$item}}
            </li>
        @endforeach
    </ul>
@else
    <p>No models uploaded.</p>
@endif
```

In the resource form (VehicleManufacturer)

```php
 \Filament\Forms\Components\Section::make('Registered Models')
    ->description('The list of registered models are shown below.')
    ->schema([
        \Filament\Forms\Components\View::make('custom.models')
            ->label('Available Models')
            ->viewData(
                [
                    'models' => $form->getRecord()->vehicleModels->pluck('model')->toArray()
                ]
            ),
    ])
    ->visible(fn(string $context): bool => $context === 'view')
```
