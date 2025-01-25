<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Asset;
use App\Models\Address;
use App\Models\Company;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RefuelingOrder;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use TomatoPHP\FilamentDocs\Services\Contracts\DocsVar;
use App\Filament\Resources\RefuelingOrderResource\Pages;
use TomatoPHP\FilamentDocs\Filament\Actions\DocumentAction;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\RefuelingOrderResource\Pages\CreateRefuelingOrder;
use App\Filament\Resources\RefuelingOrderResource\RelationManagers\DocumentsRelationManager;

class RefuelingOrderResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = RefuelingOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function boot()
    {
        FilamentDocs::register([
            DocsVar::make('$START_DATE')
                ->label('Start Date')
                ->model(RefuelingOrder::class)
                ->column('start_date'),
            DocsVar::make('$FUEL_TYPE')
                ->label('Fuel Type')
                ->model(RefuelingOrder::class)
                ->column('fuel_type'),
        ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'lock',
            'approve',
            'draft',
            'archive'
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Refueling Order Recipient')
                    ->description('Please select the company to which this refueling order will be addressed to.')
                    ->schema([
                        Fieldset::make()
                            ->schema([
                                Forms\Components\Select::make('company_id')
                                    ->label(__('Company Name'))
                                    ->id('company_id_on_company_select')
                                    ->relationship('company', 'name')
                                    ->live()
                                    ->reactive()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Sync the company_id select when VAT select changes
                                        $set('company_vat_number', $state);
                                        $set('street_address', $state);
                                    }),
                                Forms\Components\Select::make('company_vat_number')
                                    ->label(__('Company VAT'))
                                    ->id('company_id_on_vat_select')
                                    ->relationship('company', 'vat_number')
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Sync the company_id select when VAT select changes
                                        $set('company_id', $state);
                                    }),

                                Forms\Components\Select::make('address_id')
                                     ->label(__('Company Address'))
                                     ->id('company_id_on_website_select')
                                     ->reactive()
                                     ->options(function ($get) {
                                        $companyId = $get('company_id'); // Get the selected company ID

                                        if (!$companyId) {
                                            return []; // Return empty if no company is selected
                                        }

                                        // Fetch the addresses for the selected company
                                        $company = \App\Models\Company::find($companyId);

                                        if (!$company) {
                                            return [];
                                        }

                                        // Map addresses to id => display pairs
                                        return $company->addresses->pluck('formattedAddress', 'id')->toArray();
                                    }),
                            ]),
                        Fieldset::make('Operator')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->live()
                                    ->required(),
                            ]),
                        Fieldset::make('Period of validity')
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->live()
                                    ->required(),
                                Forms\Components\DatePicker::make('end_date')
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->live()
                                    ->required(),
                            ]),
                        Fieldset::make('Asset')
                            ->schema([
                                Forms\Components\Select::make('asset_id')
                                    ->relationship('asset', 'license_plate')
                                    ->live()
                                    ->preload()
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                            $set('fuel_type', Asset::find($state)->vehicle->fuel_type??'');
                                        })
                                    ->required(),
                                Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('fuel_type')
                                            ->label('Type of fuel')
                                            ->in(['Petrol','Diesel'])
                                            ->readOnly()
                                            ->live()
                                            ->required(),
                                        Forms\Components\TextInput::make('fuel_qty')
                                            ->numeric()
                                            ->default(0)
                                            ->live()
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Forms\Components\TextInput::make('state'),
                    ])
                    ->columnSpan(2)->columns(3),
                Section::make('Refueling Order Details')
                    ->description('Here is the preview.')
                    ->schema([
                        Placeholder::make('plc_company_name')
                            ->label('Order For')
                            ->translateLabel()
                            ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                if ($livewire instanceof ViewRecord) {
                                    if ($record) {// && ($get('filament.context') === 'view')) {
                                        if ($livewire instanceof CreateRefuelingOrder) {
                                            return 'You are creating a new record.';
                                        }
                                        if ($record->company) return $get('filament.context').'(rec) '.$record?->company?->name??''    ;
                                    }
                                }


                                $companyId = $get('company_id');

                                if (!$companyId) return '';

                                // Fetch the addresses for the selected company
                                $company = \App\Models\Company::find($companyId);

                                if (!$company) return '';

                                return $company->name??'';
                            })
                            ->reactive()
                            ,//->columns(3)->columnSpan(3),
                        Placeholder::make('plc_company_address')
                            ->label('Branch Address')
                            ->translateLabel()
                            ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                if ($livewire instanceof ViewRecord) {
                                    if ($record) {
                                        if ($record->company) return '(rec) '.$record?->address?->formattedAddress??'';
                                    }
                                }

                                $id = $get('address_id');

                                if (!$id) return '';

                                // Fetch the addresses for the selected company
                                $item = \App\Models\Address::find($id);

                                if (!$item) return '';

                                return $item->formattedAddress;
                            })
                            ->reactive()
                            ,//->columns(3)->columnSpan(3),
                        Fieldset::make('Order Details')
                            ->schema([
                                Placeholder::make('plc_car_details')
                                    ->label('')
                                    ->translateLabel()
                                    ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                        if ($livewire instanceof ViewRecord) {
                                            if($record) {
                                                if ($record->asset) return '(rec) '.$record?->asset?->license_plate??'';
                                            }
                                        }

                                        $id = $get('asset_id');

                                        if (!$id) return '';

                                        // Fetch the addresses for the selected company
                                        $item = \App\Models\Asset::find($id);

                                        if (!$item) return '';

                                        return $item->license_plate;
                                    })
                                    ->reactive()
                                    ,//->columns(1)->columnSpan(3),
                                Placeholder::make('plc_car_details')
                                    ->label('')
                                    ->translateLabel()
                                    ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                        if ($livewire instanceof ViewRecord) {
                                            if ($record) {
                                                if ($record->fuel_type) return '(rec) '.$record?->fuel_type??'';
                                            }
                                        }

                                        $id = $get('asset_id');

                                        if (!$id) return '';

                                        // Fetch the addresses for the selected company
                                        $item = \App\Models\Asset::find($id);

                                        if (!$item) return '';

                                        return $item->vehicle->fuel_type;
                                    })
                                    ->reactive()
                                    ,//->columns(1)->columnSpan(3),
                                Placeholder::make('plc_car_details')
                                    ->label('')
                                    //->translateLabel()
                                    ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                        if ($livewire instanceof ViewRecord) {
                                            if($record) {
                                                return '(rec) '.$record?->fuel_qty.' ltrs';
                                            }
                                        }

                                        return $get('fuel_qty')?$get('fuel_qty').' ltrs':'0 ltrs';
                                    })
                                    ->reactive()
                                    ,//->columns(1)->columnSpan(3),
                            ])->columns(3),
                            Placeholder::make('plc_expiry')
                                ->label('Valid Until')
                                ->translateLabel()
                                ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                    if ($livewire instanceof ViewRecord) {
                                        if ($record) {
                                            return '(rec) '. date_format($record?->end_date,"d M, Y");
                                        }
                                    }

                                    if ($get('end_date')) return date("d M, Y", strtotime($get('end_date')));

                                    return '';
                                })
                                ->reactive()
                                ,//->columns(1)->columnSpan(3),

                    ])->columnSpan(1),

                    //     Select::make('vehicle_fault_template_id')
                    //     ->translateLabel()
                    //     ->columnSpanFull()
                    //     ->relationship(name: 'vehicleFaultTemplate', titleAttribute: 'title')
                    //     ->live()
                    //     ->preload()
                    //     ->afterStateUpdated(function (Set $set, ?string $state) {
                    //         $set('description', VehicleFaultTemplate::find($state)->description);
                    //         $set('precautions', VehicleFaultTemplate::find($state)->precautions);
                    //         })
                    //     ->hiddenOn('view'),
                    // Textarea::make('description')
                    //     ->columnSpanFull()
                    //     ->rows(3),
                    // TextInput::make('precautions')
                    //     ->columnSpanFull()
                    //     ->readonly(),
                    // ])->columnSpanFull(),

            ])
            ->columns(3);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Operator')
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->translateLabel()
                    ->limit(15)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('asset.license_plate')
                    ->label('Vehicle')
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->translateLabel()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Due Date')
                    ->translateLabel()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fuel_type'),
                Tables\Columns\TextColumn::make('fuel_qty')
                    ->label('Litres')
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label('Status')
                    ->translateLabel()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending_approval' => 'warning',
                        'approved' => 'success',
                        'printed' => 'success',
                        'receipt_attached' => 'success',
                        'cancelled' => 'danger',
                        'closed' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                ActionGroup::make([
                    DocumentAction::make('print')
                        ->vars(fn($record) => [
                            DocsVar::make('$PRINT_DATE')
                                ->value(date("d/m/Y", strtotime(now()))),
                            DocsVar::make('$COMPANY')
                                ->value($record->company->name),
                            DocsVar::make('$ADDRESS')
                                ->value($record->address->formattedAddress),
                            DocsVar::make('$CC')
                                ->value(''),
                            DocsVar::make('$LICENCE_PLATE')
                                ->value($record->asset->license_plate),
                            DocsVar::make('$FUEL_TYPE')
                                ->value($record->fuel_type),
                            DocsVar::make('$FUEL_QTY')
                                ->value($record->fuel_qty),
                            DocsVar::make('$OPERATOR')
                                ->value($record->user->name),
                            DocsVar::make('$DUE_DATE')
                                ->value(date("d/m/Y", strtotime($record->end_date)))
                        ])
                        ->visible(function ($record) {
                            return Gate::allows('print', $record);
                        })
                        ->after(function ($record) {
                            // Update the record's state
                            $record->update(['state' => 'printed']);
                        }),
                ])
                ->label('More actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
                ->button(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DocumentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefuelingOrders::route('/'),
            'create' => Pages\CreateRefuelingOrder::route('/create'),
            'view' => Pages\ViewRefuelingOrder::route('/{record}'),
            'edit' => Pages\EditRefuelingOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('asset')->where('user_id', Auth::user()->id);
    }
}
