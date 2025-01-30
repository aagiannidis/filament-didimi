<?php

namespace App\Filament\CustomForms;

use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Enums\DocumentType;
use App\Models\RefuelingOrder;
use Filament\Forms\FormsComponent;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use App\Traits\FilamentHandleSecureDocuments;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class NonRelationRefuelingUploadForm extends FormsComponent
{

    public static function schema(): array
    {
        return [
            Repeater::make('repeater_upload')
                ->schema([
                    Select::make('type')
                        ->options(DocumentType::class)
                        // ->options(function ($container) {
                        //     // Get the values to exclude from the $data array (modify as needed based on context)
                        //     $removeEnumValues = $container->getData();

                        //     // Fetch all enum cases and exclude the specified values
                        //     return collect(DocumentType::cases())
                        //         ->reject(fn($case) => in_array($case->value, $removeEnumValues))
                        //         ->mapWithKeys(fn($case) => [$case->name => $case->value]);
                        // })
                        // ->options(function ($state, callable $get,) {
                        //     // Get the current selection from all repeaters
                        //     $selectedTypes = collect($get('../../members'))
                        //         ->pluck('type')
                        //         ->filter()
                        //         ->toArray();
                        // array_merge(...$a);

                        //     $currentValue = $state;

                        //     // Fetch all document types and exclude selected ones
                        //     return collect(DocumentType::cases())
                        //         ->filter(fn($case) => (!in_array($case->name, $selectedTypes)) || $case->name === $currentValue)
                        //         ->mapWithKeys(fn($case) => [$case->value => $case->name]);
                        // })
                        // ->afterStateUpdated(function ($state, callable $get) {})
                        ->live()
                        ->required()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                    //->dehydrated(),
                    Hidden::make('uploaded_by_user_id')
                        ->required()
                        ->formatStateUsing((fn() => \Illuminate\Support\Facades\Auth::user()->id))
                        ->dehydrated(true),
                    Hidden::make('path')
                        ->default('nothing')
                        ->columnSpanFull()
                        ->dehydrated(true),
                    Hidden::make('uploaded_at')
                        ->default(now())
                        ->dehydrated(true),
                    Hidden::make('status_history')
                        ->default('')
                        ->dehydrated(true),
                    Hidden::make('expiry_date')
                        ->default(\Illuminate\Support\Carbon::now()->addYears(10))
                        ->dehydrated(true),

                    FileUpload::make('members')
                        ->label('File to upload')
                        ->disk('private')
                        ->directory('secure-documents')
                        ->visibility('private')
                        ->acceptedFileTypes(['application/pdf'])
                        ->moveFiles(false)
                        ->minFiles(0)
                        ->maxFiles(1)
                        ->storeFileNamesIn('original_filename')
                        ->downloadable()
                        ->previewable(false)
                        //->required()
                        ->dehydrated(true),
                ])
                ->reorderable(false)
        ];
    }
}
