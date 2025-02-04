<?php

namespace App\Filament\CustomForms;

use Filament\Forms\Get;
use App\Enums\DocumentType;
use Filament\Forms\FormsComponent;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class SecureDocumentUploadForm extends FormsComponent
{
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
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                        ->dehydrated(false),
                    FileUpload::make('members')
                        ->disk('private')
                        ->directory('secure-documents')
                        ->visibility('private')
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
                ->reorderable(false)
        ];
    }
}
