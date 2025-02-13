<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\FuelType;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Enums\VehicleType;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\VehicleModel;
use App\Models\SecureDocument;
use App\Models\VehicleLicence;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\VehicleLicenceExporter;
use App\Filament\Imports\VehicleLicenceImporter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleLicenceResource\Pages;
use App\Filament\Resources\VehicleLicenceResource\RelationManagers;
use ZeeshanTariq\FilamentAttachmate\Forms\Components\AttachmentFileUpload;

class VehicleLicenceResource extends Resource
{
    protected static ?string $model = VehicleLicence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        // $table->string('licence_document_id', 8)->unique();
    // $table->string('plate', 8)->unique();
    // $table->string('old_plate', 8)->nullable()->default(null);
    // $table->string('vin', 20)->unique();
    // $table->string('engine_serial_no', 50)->nullable()->default(null)->description('Engine Serial #. Not always mentioned. Optional.');
    // $table->integer('engine_cc')->default(0);
    // $table->integer('engine_hp')->default(0);
    // $table->string('chassis_serial_no', 50)->nullable()->default(null)->description('Chassis Serial #. Not always mentioned. Optional.');
    // $table->foreignId('vehicle_model_id')->references('id')->on('vehicle_models')->onDelete('restrict')->nullable()->default(null);
    // $table->string('vehicle_d2')->default('')->description('Vehicle D2 field as per licence.');
    // $table->date('first_reg_date')->nullable()->default(null)->description('Registration date. Field B');
    // $table->string('color', 20)->nullable()->default(null)->description('Color of the vehicle.');
    // $table->string('license_vehicle_type')->nullable()->default(null)->description('Vehicle type as per registration licence.');
    // $table->integer('vehicle_secondary_type')->default(VehicleType::OTHER)->description('Additional vehicle type information as user-specified.');
    // $table->enum('fuel_type', ["Petrol", "Diesel", "Electric", "Hybrid", "Other"]);
    // $table->enum('emission_standard', ["Other", "Euro 1", "Euro 2", "Euro 3", "Euro 4", "Euro 5", "Euro 6"]);
    // $table->integer('weight')->default(0);
    // $table->integer('seats')->default(0);
    // $table->string('comments')->default('');
    // $table->string('registered_to')->default('');

    // 'licence_document_id',
    //     'plate',
    //     'old_plate',
    //     'vin',
    //     'engine_serial_no',
    //     'engine_cc',
    //     'engine_hp',
    //     'chassis_serial_no',
    //     'vehicle_model_id',
    //     'vehicle_d2',
    //     'first_reg_date',
    //     'color',
    //     'license_vehicle_type',
    //     'vehicle_secondary_type',
    //     'fuel_type',
    //     'emission_standard',
    //     'weight',
    //     'seats',
    //     'comments',
    //     'registered_to',

        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Vehicle Licence Record')
                    ->schema([
                            Fieldset::make('general')
                                ->label(__('General'))
                                ->schema([
                                    Forms\Components\TextInput::make('licence_document_id')->label('Slip Code')->translateLabel()->required()->maxLength(8)->placeholder('A2069785'),
                                    Forms\Components\DatePicker::make('first_reg_date')->label('Registration Date')->translateLabel()->required()->native(false)->displayFormat('d/m/Y')->placeholder('31/01/1980'),
                                    Forms\Components\TextInput::make('registered_to')->label('Registered To')->translateLabel()->required()->columnSpanFull()->hint(__('As in fields C1.1 - C1.3')),
                                    Forms\Components\TextInput::make('plate')->label('Car Plate No')->translateLabel()->required()->maxLength(8),
                                    Forms\Components\TextInput::make('old_plate')->label('Previous Car Plate')->translateLabel()->maxLength(8),
                                ]),
                            Fieldset::make('details')
                                ->label(__('Details'))
                                ->schema([
                                    Forms\Components\Select::make('license_vehicle_type')->label('Primary Type')->options(['0'=>'Επιβατικό', '1'=>'Φορτηγό', '2'=>'Άλλο'])->translateLabel()->required(),
                                    Forms\Components\Select::make('vehicle_secondary_type')->label('Secondary Type')->options(VehicleType::getLabels())->translateLabel()->required(),
                                    Forms\Components\Select::make('vehicle_model_id')
                                        ->label('Model')
                                        ->translateLabel()
                                        ->relationship(name: 'model', titleAttribute: 'model')
                                        ->getOptionLabelFromRecordUsing(fn (?VehicleModel $record) => "{$record?->model} - {$record?->vehicleManufacturer->name}")
                                        // ->options(function (Get $get) {
                                        //     $selectedManufId = $get('vehicle_manufacturer_id');
                                        //     if ($selectedManufId) {
                                        //         return VehicleModel::where('vehicle_manufacturer_id', $selectedManufId)->pluck('model', 'id')->toArray();
                                        //     }
                                        // })
                                        ->createOptionForm(
                                                \App\Filament\CustomForms\CreateModelForm::schema()
                                                // [
                                                //     Forms\Components\TextInput::make('name')
                                                //         ->required(),
                                                //     Forms\Components\TextInput::make('email')
                                                //         ->required()
                                                //         ->email(),
                                                // ]
                                        )
                                        // ->searchable()
                                        // ->preload()
                                        ->required(),
                                    Forms\Components\TextInput::make('color')->label('Colour')->translateLabel()->required()->maxLength(15),
                                    Forms\Components\TextInput::make('weight')->label('Weight (kg)')->translateLabel()->required()->numeric(),
                                    Forms\Components\TextInput::make('seats')->label('Number of seats')->translateLabel()->required()->numeric(),
                                    Forms\Components\Select::make('fuel_type')->label('Fuel')->options(FuelType::getLabels())->translateLabel()->required(),
                                    Forms\Components\Select::make('emission_standard')->label('Emission Standard')->options(self::$model::EMISSION_STANDARDS)->translateLabel()->required(),
                                ]),
                            Fieldset::make('serials')
                                ->label(__('Serials'))
                                ->schema([
                                    Forms\Components\TextInput::make('vin')->label('VIN No')->translateLabel()->required()->maxLength(18),
                                    Forms\Components\TextInput::make('chassis_serial_no')->label('Chassis Serial No')->translateLabel()->maxLength(18),
                                    Forms\Components\TextInput::make('engine_serial_no')->label('Engine Serial No')->translateLabel()->required()->maxLength(18),
                                    Forms\Components\TextInput::make('engine_cc')->label('Engine CC')->translateLabel()->required()->maxLength(18),
                                    Forms\Components\TextInput::make('engine_hp')->label('Engine Power')->translateLabel()->maxLength(18),
                                    Forms\Components\TextInput::make('vehicle_d2')->label('Slip D2')->translateLabel()->maxLength(18),
                                ]),
                            Fieldset::make('fs_comments')
                                ->label(__('Comments'))
                                ->schema([
                                    \Filament\Forms\Components\MarkdownEditor::make('comments')
                                        ->label('')->columnSpanFull()
                                        ->toolbarButtons([
                                            'blockquote',
                                            'bold',
                                            'bulletList',
                                            'heading',
                                            'italic',
                                            'link',
                                            'orderedList',
                                            'redo',
                                            'strike',
                                            'table',
                                            'undo',
                                        ])
                                ]),
                        ])
                    ->columnSpan(2),
                \Filament\Forms\Components\Group::make()
                    ->schema([
                        Section::make('Documents')
                        ->id('section_documents_list')
                        ->description(__('Here is the documents list.'))
                        ->schema([
                            Repeater::make('secureDocuments')
                                ->label('')
                                ->relationship()
                                ->reactive()
                                ->schema([
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('download')
                                            ->label(fn($state)=> Str::length($state['original_filename'])>25? Str::substr($state['original_filename'],0,25-6).'...pdf': $state['original_filename'])
                                            ->tooltip(fn($state)=> Str::length($state['original_filename'])>25?$state['original_filename']:null)
                                            ->icon('heroicon-o-arrow-down-tray')
                                            //->hidden(fn($record) => !(Gate::Allows(json_decode($modelState)->gateFunction, $record)))
                                            ->action(fn($record) => self::dostuff($record)),
                                    ])
                                ])
                        ])->visible(fn(string $context): bool => $context === 'view'),
                        Section::make('Upload Document')
                            ->id('section_documents_upload')
                            ->description(__('Please upload your documents here.'))
                            ->schema([
                                // \Filament\Forms\Components\View::make('custom.documents')
                                //     ->label('Available Documents')
                                //     ->viewData(
                                //         [
                                //             'documents' => $form->getRecord()->secureDocuments->pluck('original_filename')->toArray()
                                //         ]
                                //     ),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('uploadNonRelRefuelingOrderDocuments')
                                    ->label('Upload Documents')
                                    ->icon('heroicon-o-arrow-up-tray')
                                    ->form([
                                        ...\App\Filament\CustomForms\NonRelationRefuelingUploadForm::schema()
                                    ])
                                    ->action(function (array $data, VehicleLicence $record): void {
                                        foreach ($data['repeater_upload'] as $item) {
                                            $newSecureDocument = $record->secureDocuments()->make();
                                            $newSecureDocument->type = $item['type'];
                                            $newSecureDocument->random_filename = $item['members'];
                                            $newSecureDocument->original_filename = $item['original_filename'];
                                            $newSecureDocument->path = $item['path'];
                                            $newSecureDocument->uploaded_by_user_id = Auth::user()->id;
                                            $newSecureDocument->uploaded_at = \Illuminate\Support\Carbon::now();
                                            $newSecureDocument->expiry_date = \Illuminate\Support\Carbon::now()->addYears(20);
                                            $newSecureDocument->save();
                                        }
                                    })
                                    ->after(function (\Livewire\Component $livewire) {
                                        $livewire->dispatch('newUploadedDocument');
                                    }),

                                ]),

                                // Section::make('Secure Documents')
                                //     ->description('Upload scanned documents')
                                //     ->schema([
                                //         ...SecureDocumentUploadForm::schema(),
                                //     ])
                            ])
                    ])->columnSpan(1),
                // Section::make('Uploaded Documents')
                //     ->description('The list of documents are shown below.')
                //     ->schema([
                //         Repeater::make('secureDocuments')
                //             ->relationship()
                //             ->schema([
                //                 Forms\Components\Actions::make([
                //                     Forms\Components\Actions\Action::make('download')
                //                         ->label(fn($state)=> $state['original_filename'])
                //                         ->icon('heroicon-o-arrow-down-tray')
                //                         //->hidden(fn($record) => !(Gate::Allows(json_decode($modelState)->gateFunction, $record)))
                //                         ->action(fn($record) => self::dostuff($record)),
                //                 ])
                //             ])

                //         // \Filament\Forms\Components\View::make('custom.documents')
                //         //     ->label('Available Documents')
                //         //     ->viewData(
                //         //         [
                //         //             //'documents' => $form->getRecord()->secureDocuments->pluck('original_filename')->toArray()
                //         //             //'documents' => $form->getRecord()->secureDocuments->all(),
                //         //             'param1' => $form->getRecord()
                //         //         ]
                //         //     ),
                //     ])->visible(fn(string $context): bool => $context === 'view')->columnSpan(1),
            ])->columns(3);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\ImportAction::make()
                    ->importer(VehicleLicenceImporter::class),
                \Filament\Tables\Actions\ExportAction::make()
                    ->exporter(VehicleLicenceExporter::class)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        if (request()->routeIs('filament.admin.resources.vehicle-licences.view')) {
            return [];
        }

        return [
            \App\Filament\Resources\VehicleLicenseResource\RelationManagers\SecureDocumentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleLicences::route('/'),
            'create' => Pages\CreateVehicleLicence::route('/create'),
            'view' => Pages\ViewVehicleLicence::route('/{record}'),
            'edit' => Pages\EditVehicleLicence::route('/{record}/edit'),
        ];
    }

    public static function doStuff(SecureDocument $record)
    {

        if (\Illuminate\Support\Facades\Auth::id()) {
            return response()->download(storage_path('app/private/' . $record->random_filename));
        } else {
            abort(404);
        }
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('secureDocuments');
    }

}
