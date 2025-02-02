<?php

namespace App\Filament\Resources;

use App\Exceptions\GenericServiceException;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Asset;
use App\Models\Address;
use App\Models\Company;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RefuelingOrder;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;

/*
    All supported states
    // x Approved
    // x Archived
    // x Cancelled
    // x Closed
    // x Denied
    // x Draft
    // x PendingApproval
    // x Processing
    // x Returned

*/
use Filament\Forms\Components\Section;
use App\Models\States\RefuelingOrderStates;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use App\Models\States\RefuelingOrderStates\Draft;
use App\Models\States\RefuelingOrderStates\Closed;
use App\Models\States\RefuelingOrderStates\Denied;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\States\RefuelingOrderStates\Approved;
use App\Models\States\RefuelingOrderStates\Archived;
use App\Models\States\RefuelingOrderStates\Returned;

use App\Models\States\RefuelingOrderStates\Cancelled;
use App\Filament\CustomForms\SecureDocumentUploadForm;
use App\Models\States\RefuelingOrderStates\Processing;
use TomatoPHP\FilamentDocs\Services\Contracts\DocsVar;
use App\Filament\Resources\RefuelingOrderResource\Pages;
use App\Models\States\RefuelingOrderStates\PendingApproval;
use Guava\FilamentKnowledgeBase\Contracts\HasKnowledgeBase;
use TomatoPHP\FilamentDocs\Filament\Actions\DocumentAction;
use App\Models\States\RefuelingOrderStates\RefuelingOrderState;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use ZeeshanTariq\FilamentAttachmate\Forms\Components\AttachmentFileUpload;
use App\Filament\Resources\RefuelingOrderResource\Pages\CreateRefuelingOrder;
use App\Filament\Resources\RefuelingOrderResource\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\RefuelingOrderResource\RelationManagers\SecureDocumentsRelationManager;
use App\Services\RefuelingOrderService;
use Filament\Infolists\Components\Fieldset as ComponentsFieldset;

class RefuelingOrderResource extends Resource implements HasShieldPermissions, HasKnowledgeBase
{
    protected static ?string $model = RefuelingOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getDocumentation(): array
    {
        return [
            'RefuelingOrders.RefuelingUserManual_Basic',
        ];
    }

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

            'submit',
            'examine',
            'approve',
            'deny_actionable',
            'deny',
            'issue_documents',
            'attach_receipt',
            'attach_signed_doc',
            'verify_receipt',
            'verify_signed_doc',
            'close',
            'archive',
            'cancel'
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Supplier Details')
                    ->description('This refueling order can be materialised only by the supplier as below.')
                    ->schema([
                        ComponentsFieldset::make('Company')
                            ->label('')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('company.name')
                                    ->label('Name'),
                                \Filament\Infolists\Components\TextEntry::make('company.vat_number')
                                    ->label('VAT Number'),
                                \Filament\Infolists\Components\TextEntry::make('address_id')
                                    ->label('Address')
                                    ->formatStateUsing(fn($record) => $record->company->addresses->find($record->address_id)->formattedAddress),
                            ])->columns(3)
                    ]),
                \Filament\Infolists\Components\Section::make('Order Details')
                    ->description('This refueling order applies only to the vehicle as below.')
                    ->schema([
                        ComponentsFieldset::make('Vehicle')
                            ->label('')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('asset.vehicle.license_plate')
                                    ->label('Reg.No.'),
                                \Filament\Infolists\Components\TextEntry::make('fuel_type')
                                    ->label('Fuel Type'),
                                \Filament\Infolists\Components\TextEntry::make('fuel_qty')
                                    ->label('Qty (ltrs)')
                            ])->columns(3)
                    ]),
                \Filament\Infolists\Components\Section::make('Validity')
                    ->description('This refueling order can be presented only by the employee below and only between the shown dates.')
                    ->schema([
                        ComponentsFieldset::make('Carrier')
                            ->label('')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('user.name')
                                    ->label('Full Name.'),
                                \Filament\Infolists\Components\TextEntry::make('start_date')
                                    ->label('From:'),
                                \Filament\Infolists\Components\TextEntry::make('end_date')
                                    ->label('Until:')
                            ])->columns(3)
                    ])
            ])
            ->columns(1);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Refueling Order Form')
                    ->schema([
                        Fieldset::make('Company')
                            //->description('Please select the company to which this refueling order will be addressed to.')
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
                                        $set('address_id', null);
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
                                    ->required()
                                    ->live()
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
                        Fieldset::make('Asset')
                            //->description('Please select the asset that this order is for.')
                            ->schema([
                                Forms\Components\Select::make('asset_id')
                                    ->relationship('asset', 'license_plate')
                                    ->live()
                                    ->preload()
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        $set('fuel_type', Asset::find($state)->vehicle->fuel_type ?? '');
                                    })
                                    ->required(),
                                Forms\Components\TextInput::make('fuel_type')
                                    ->label('Type of fuel')
                                    ->in(['Petrol', 'Diesel'])
                                    ->readOnly()
                                    ->live()
                                    ->required(),
                                Forms\Components\TextInput::make('fuel_qty')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->required()
                            ])->columns(3),
                        Fieldset::make('Period of validity')
                            //->description('Please define the valid period dates.')
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
                        Fieldset::make('Operator')
                            //->description('Please select driver.')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->live()
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('state'),
                    ])
                    ->columnSpan(2)->columns(3),
                Group::make()
                    ->schema([
                        Section::make('Refueling Order Details')
                            ->description('Here is the preview.')
                            ->schema([
                                Placeholder::make('plc_company_name')
                                    ->label('Order For')
                                    ->translateLabel()
                                    ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                        if ($livewire instanceof ViewRecord) {
                                            if ($record) { // && ($get('filament.context') === 'view')) {
                                                if ($livewire instanceof CreateRefuelingOrder) {
                                                    return 'You are creating a new record.';
                                                }
                                                if ($record->company) return $get('filament.context') . '(rec) ' . $record?->company?->name ?? '';
                                            }
                                        }


                                        $companyId = $get('company_id');

                                        if (!$companyId) return '';

                                        // Fetch the addresses for the selected company
                                        $company = \App\Models\Company::find($companyId);

                                        if (!$company) return '';

                                        return $company->name ?? '';
                                    })
                                    ->reactive(), //->columns(3)->columnSpan(3),
                                Placeholder::make('plc_company_address')
                                    ->label('Branch Address')
                                    ->translateLabel()
                                    ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                        if ($livewire instanceof ViewRecord) {
                                            if ($record) {
                                                if ($record->company) return '(rec) ' . $record?->address?->formattedAddress ?? '';
                                            }
                                        }

                                        $id = $get('address_id');

                                        if (!$id) return '';

                                        // Fetch the addresses for the selected company
                                        $item = \App\Models\Address::find($id);

                                        if (!$item) return '';

                                        return $item->formattedAddress;
                                    })
                                    ->reactive(), //->columns(3)->columnSpan(3),
                                Fieldset::make('Order Details')
                                    ->schema([
                                        Placeholder::make('plc_car_details')
                                            ->label('')
                                            ->translateLabel()
                                            ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                                if ($livewire instanceof ViewRecord) {
                                                    if ($record) {
                                                        if ($record->asset) return '(rec) ' . $record?->asset?->license_plate ?? '';
                                                    }
                                                }

                                                $id = $get('asset_id');

                                                if (!$id) return '';

                                                // Fetch the addresses for the selected company
                                                $item = \App\Models\Asset::find($id);

                                                if (!$item) return '';

                                                return $item->license_plate;
                                            })
                                            ->reactive(), //->columns(1)->columnSpan(3),
                                        Placeholder::make('plc_car_details')
                                            ->label('')
                                            ->translateLabel()
                                            ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                                if ($livewire instanceof ViewRecord) {
                                                    if ($record) {
                                                        if ($record->fuel_type) return '(rec) ' . $record?->fuel_type ?? '';
                                                    }
                                                }

                                                $id = $get('asset_id');

                                                if (!$id) return '';

                                                // Fetch the addresses for the selected company
                                                $item = \App\Models\Asset::find($id);

                                                if (!$item) return '';

                                                return $item->vehicle->fuel_type;
                                            })
                                            ->reactive(), //->columns(1)->columnSpan(3),
                                        Placeholder::make('plc_car_details')
                                            ->label('')
                                            //->translateLabel()
                                            ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                                if ($livewire instanceof ViewRecord) {
                                                    if ($record) {
                                                        return '(rec) ' . $record?->fuel_qty . ' ltrs';
                                                    }
                                                }

                                                return $get('fuel_qty') ? $get('fuel_qty') . ' ltrs' : '0 ltrs';
                                            })
                                            ->reactive(), //->columns(1)->columnSpan(3),
                                    ])->columns(3),
                                Placeholder::make('plc_expiry')
                                    ->label('Valid Until')
                                    ->translateLabel()
                                    ->content(function (Get $get, ?RefuelingOrder $record, $livewire): string {

                                        if ($livewire instanceof ViewRecord) {
                                            if ($record) {
                                                return '(rec) ' . date_format($record?->end_date, "d M, Y");
                                            }
                                        }

                                        if ($get('end_date')) return date("d M, Y", strtotime($get('end_date')));

                                        return '';
                                    })
                                    ->reactive(), //->columns(1)->columnSpan(3),
                            ])->columnSpan(1),
                        // Section::make('Attachments')
                        //     ->description('Upload scanned documents')
                        //     ->schema([
                        //         AttachmentFileUpload::make()
                        //             ->label('Invoice or Receipt')
                        //             ->hint('Necessary for all')
                        //             ->afterStateUpdated(function ($state, $set, $get, $livewire) {}), //->required(fn($get):bool => $get('state')==='closed'),

                        //     ])->columnSpan(1),
                        // Section::make('Secure Documents')
                        //     ->description('Upload scanned documents')
                        //     ->schema([
                        //         ...SecureDocumentUploadForm::schema(),
                        //     ])
                    ]),

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
                    ->color(fn(string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Archived' => 'gray',
                        'Cancelled' => 'danger',
                        'Closed' => 'success',
                        'Denied' => 'danger',
                        'Draft' => 'gray',
                        'Pending Approval' => 'warning',
                        'Processing' => 'orange',
                        'Returned' => 'danger',
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
                // Tables\Actions\ViewAction::make(),
                // ActionGroup::make([
                //     ...collect(...(RefuelingOrder::getStates()->values()))->map(function ($actionConfig) {
                //         return Tables\Actions\Action::make($actionConfig)
                //             ->label($actionConfig)
                //             ->icon(self::getDynamicActions()[$actionConfig])
                //             ->requiresConfirmation()
                //             ->hidden(fn($record) => !($record->state->canTransitionTo($actionConfig)))
                //             ->action(fn($record) => dd($record->id));
                //     })->toArray(),
                // ]),
                ActionGroup::make([
                    ...collect((RefuelingOrderState::allGates()))->map(function ($modelState) {
                        return Tables\Actions\Action::make(json_decode($modelState)->gateFunction)
                            ->label(json_decode($modelState)->menuLabel)
                            //->icon(self::getDynamicActions()[$actionConfig])
                            ->requiresConfirmation()
                            ->hidden(fn($record) => !(Gate::Allows(json_decode($modelState)->gateFunction, $record)))
                            ->action(fn($record) => self::dostuff($record, json_decode($modelState)->gateFunction));
                    })->toArray(),
                ]),
                DocumentAction::make('print')
                    ->label('Εγγραφο')
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
                        return Gate::allows('issueDocuments', $record);
                    })
                    ->after(function ($record) {
                        // Update the record's state
                        //$record->update(['state' => 'printed']);
                    }),

                //state->allowableActions()['menu_actions').map(Gate::Allows
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
        // Tables\Actions\ViewAction::make(),
        // Tables\Actions\EditAction::make(),

        // Tables\Actions\ActionGroup::make(
        //     self::getArray(fn($record)=>$record)
        // )->label('Authorized Actions'),


        // collect()->map(function ($inputAction) use ($record) {
        //     return Action::make($inputAction['action'])
        //         ->label($inputAction['label'])
        //         ->icon($inputAction['icon'])
        //         ->action(function () use ($record, $inputAction) {
        //             // Handle the action's logic dynamically
        //             match ($inputAction['action']) {
        //                 'viewDetails' => $this->viewDetails($record),
        //                 'editRecord' => $this->editRecord($record),
        //                 'deleteRecord' => $this->deleteRecord($record),
        //                 default => null,
        //             };
        //         });
        // })->toArray()


        // ActionGroup::make([
        //     Tables\Actions\Action::make('status')
        //         ->icon('heroicon-o-pencil')
        //         ->form([
        //             \Filament\Forms\Components\Select::make('state')
        //                 ->label('Status')
        //                 ->options(fn($record) => $record->state->transitionableStates())
        //             //->disabled(fn($record) => !auth()->user()->hasRole('manager')),)
        //             // ->default(function (RefuelingOrder $order){

        //             // }),
        //             ,
        //             \Filament\Forms\Components\TextArea::make('comment')
        //                 ->label('Comments')
        //         ])
        //         ->action(function (RefuelingOrder $order, array $data): void {
        //             // $order->status = $data['status'];
        //             // $order->comment = $data['comment'];
        //             // $order->save();
        //             //$order->state->transitionTo(RefuelingOrderStates\Approved::class);
        //             dd($order::getStates());
        //             //dd($order->state->canTransitionTo(Cancelled::class));
        //             //dd($order->state->transitionableStates());

        //             Notification::make()
        //                 ->title('Updated Order Status and Comment')
        //                 ->success()
        //                 ->send();
        //         }),
        //     DocumentAction::make('print')
        //         ->vars(fn($record) => [
        //             DocsVar::make('$PRINT_DATE')
        //                 ->value(date("d/m/Y", strtotime(now()))),
        //             DocsVar::make('$COMPANY')
        //                 ->value($record->company->name),
        //             DocsVar::make('$ADDRESS')
        //                 ->value($record->address->formattedAddress),
        //             DocsVar::make('$CC')
        //                 ->value(''),
        //             DocsVar::make('$LICENCE_PLATE')
        //                 ->value($record->asset->license_plate),
        //             DocsVar::make('$FUEL_TYPE')
        //                 ->value($record->fuel_type),
        //             DocsVar::make('$FUEL_QTY')
        //                 ->value($record->fuel_qty),
        //             DocsVar::make('$OPERATOR')
        //                 ->value($record->user->name),
        //             DocsVar::make('$DUE_DATE')
        //                 ->value(date("d/m/Y", strtotime($record->end_date)))
        //         ])
        //         ->visible(function ($record) {
        //             return Gate::allows('print', $record);
        //         })
        //         ->after(function ($record) {
        //             // Update the record's state
        //             $record->update(['state' => 'printed']);
        //         }),
        // ])
        //     ->label('More actions')
        //     ->icon('heroicon-m-ellipsis-vertical')
        //     ->color('primary')
        //     ->button(),

        // ])

    }

    public static function dostuff(RefuelingOrder $record, $action)
    {

        if ($action === 'deny') {
            try {
                app(RefuelingOrderService::class)->deny($record);
            } catch (GenericServiceException $e) {
                Notification::make()
                    ->title('Fail !')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        } else {
            dd("Please perform the " . $action . " operation on record with id = " . $record->id);
        }
    }

    public static function getRelations(): array
    {
        return [
            DocumentsRelationManager::class,
            SecureDocumentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {

        //dd(collect((RefuelingOrderState::allGates())));

        return [
            'index' => Pages\ListRefuelingOrders::route('/'),
            'create' => Pages\CreateRefuelingOrder::route('/create'),
            'view' => Pages\ViewRefuelingOrder::route('/{record}'),
            'edit' => Pages\EditRefuelingOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (User::find(Auth::user()->id)->hasRole(['super_admin', 'Secretarial', 'RefuelingOrdersManager'])) {
            return parent::getEloquentQuery()->with('asset');
        }

        return parent::getEloquentQuery()->with('asset')->where('user_id', Auth::user()->id);
    }

    public static function getArray(?RefuelingOrder $record): array
    {
        return collect(self::getAvailableTransitions($record))
            //->filter(fn($transition) => auth()->user()->can($transition['permission']))
            ->map(
                fn($transition) =>
                Tables\Actions\Action::make($transition['action'])
                    ->label($transition['label'])
                    ->icon($transition['icon'])
                    ->color($transition['color'])
                    ->requiresConfirmation()
                    ->action(fn() => $record->transitionState($transition['state']))
            )->toArray();
    }

    public static function getAvailableTransitions(RefuelingOrder $task): array
    {
        // Get only valid adjacent states based on current state
        $transitions = match ($task->state->getValue()) {
            'pending' => [
                [
                    'state' => 'in_progress',
                    'action' => 'start',
                    'label' => 'Start Task',
                    'icon' => 'heroicon-o-play',
                    'color' => 'success',
                    'permission' => 'start maintenance task'
                ],
                [
                    'state' => 'cancelled',
                    'action' => 'cancel',
                    'label' => 'Cancel',
                    'icon' => 'heroicon-o-x-circle',
                    'color' => 'danger',
                    'permission' => 'cancel maintenance task'
                ],
            ],
            'in_progress' => [
                [
                    'state' => 'on_hold',
                    'action' => 'pause',
                    'label' => 'Pause',
                    'icon' => 'heroicon-o-pause',
                    'color' => 'warning',
                    'permission' => 'pause maintenance task'
                ],
                [
                    'state' => 'completed',
                    'action' => 'complete',
                    'label' => 'Complete',
                    'icon' => 'heroicon-o-check-circle',
                    'color' => 'success',
                    'permission' => 'complete maintenance task'
                ],
            ],
            'on_hold' => [
                [
                    'state' => 'in_progress',
                    'action' => 'resume',
                    'label' => 'Resume',
                    'icon' => 'heroicon-o-play',
                    'color' => 'success',
                    'permission' => 'resume maintenance task'
                ],
                [
                    'state' => 'cancelled',
                    'action' => 'cancel',
                    'label' => 'Cancel',
                    'icon' => 'heroicon-o-x-circle',
                    'color' => 'danger',
                    'permission' => 'cancel maintenance task'
                ],
            ],
            'completed' => [
                [
                    'state' => 'in_progress',
                    'action' => 'reopen',
                    'label' => 'Reopen',
                    'icon' => 'heroicon-o-arrow-path',
                    'color' => 'warning',
                    'permission' => 'reopen maintenance task'
                ],
            ],
            'cancelled' => [
                [
                    'state' => 'pending',
                    'action' => 'reactivate',
                    'label' => 'Reactivate',
                    'icon' => 'heroicon-o-arrow-path',
                    'color' => 'success',
                    'permission' => 'reactivate maintenance task'
                ],
            ],
            default => [],
        };

        return $transitions;
    }

    public static function getDynamicActions()
    {
        return [
            'Approved' => Approved::heroIcon(),
            'Archived' => Archived::heroIcon(),
            'Cancelled' => Cancelled::heroIcon(),
            'Closed' => Closed::heroIcon(),
            'Denied' => Denied::heroIcon(),
            'Draft' => Draft::heroIcon(),
            'Pending Approval' => PendingApproval::heroIcon(),
            'Processing' => Processing::heroIcon(),
            'Returned' => Returned::heroIcon(),
        ];
    }
}
