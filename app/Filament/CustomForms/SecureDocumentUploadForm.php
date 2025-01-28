<?php

namespace App\Filament\CustomForms;

use App\Enums\DocumentType;
use Filament\Forms\FormsComponent;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class SecureDocumentUploadForm extends FormsComponent
{

    // 'doc_attachable_id',
    //     'doc_attachable_type',
    //     'type',
    //     'original_filename',
    //     'random_filename',
    //     'flags',
    //     'uploaded_by_user_id',
    //     'uploaded_at',
    //     'status_history',
    //     'expiry_date',
    // ];

    // protected $casts = [
    //     'flags' => 'array',
    //     'status_history' => 'array',
    //     'uploaded_at' => 'datetime',
    //     'expiry_date' => 'datetime',
    // ];

    public static function schema(): array
    {
        return [
            Repeater::make('members')
                ->schema([
                    Select::make('type')
                        ->options(DocumentType::class)
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
                        ->dehydrated(false),
                    FileUpload::make('secureDocuments')
                        ->disk('public')
                        ->directory('secure-documents')
                        ->visibility('public')
                        ->acceptedFileTypes(['application/pdf'])
                        ->moveFiles()
                        ->minFiles(0)
                        ->maxFiles(1)
                        // ->getUploadedFileNameForStorageUsing(
                        //     fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                        //         ->prepend('custom-prefix-'),
                        // )
                        ->storeFileNamesIn('attachment_file_names')
                        ->downloadable()
                        ->previewable(false)
                        ->dehydrated(false),
                ])
                //->relationship('secureDocuments')
                ->reorderable(false)



            // Group::make()
            //     ->schema([
            //         Textarea::make('street_address')
            //             ->required()
            //             ->columnSpanFull(),
            //         TextInput::make('street_number')
            //             ->required()
            //             ->numeric()
            //             ->maxLength(6),
            //         TextInput::make('unit_number')
            //             ->numeric()
            //             ->maxLength(5),
            //         TextInput::make('postal_code')
            //             ->required()
            //             ->numeric()
            //             ->maxLength(8),
            //     ]),
            // Group::make()
            //     ->schema([
            //         Fieldset::make('Address Coordinates')
            //             ->schema([
            //                 TextInput::make('latitude')
            //                     ->numeric(),
            //                 TextInput::make('longitude')
            //                     ->numeric(),
            //             ]),
            //         Textarea::make('additional_info')
            //             ->rows(5)
            //             ->columnSpanFull(),
            //         Checkbox::make('is_correspondence')
            //             ->label('Correspondence')
            //             ->translateLabel(),

            //     ]),



        ];
    }
}
