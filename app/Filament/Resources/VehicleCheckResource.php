<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Spatie\Tags\Tag;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\VehicleCheck;
use Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Forms\Components\SpatieTagsInput;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleCheckResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\VehicleCheckResource\RelationManagers;

class VehicleCheckResource extends Resource
{
    protected static ?string $model = VehicleCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // \Filament\Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make('asset_id')
                        ->relationship('asset', 'license_plate')
                        ->required()
                        ->columnSpan(2),
                    Forms\Components\Select::make('check_result')
                        ->options(self::$model::CHECK_RESULTS)
                        ->required(),
                    Forms\Components\DatePicker::make('check_date')
                        ->required(),
                    // SpatieTagsInput::make('tags')
                    //     ->type('kteo_tags')
                    //     ->nestedRecursiveRules([
                    //         'min:1',
                    //         'max:20',
                    //         'in:tag1,tag2,tag3'
                    //     ])
                    //     ->columnSpan(2),
                    Forms\Components\Select::make('tags')
                        //->options(Tag::where('type','kteo_tags')->pluck('name', 'id')->toArray()) // Predefined tags
                        //->options(Tag::where('type','kteo_tags')->pluck('name', 'id')->toArray())
//                        ->preload()
                        //->getSearchResultsUsing(fn (string $search): array => Tag::where('type', 'kteo_tags')->pluck('name', 'id')->toArray())
                        //->getOptionLabelUsing(fn ($value): ?string => Tag::where('type', 'kteo_tags')?->name)
                        //->getOptionLabelsUsing(fn (Builder $query) => $query->WithType('kteo_tags'))
                        ->label('Select Tags')
                        ->placeholder('Choose tags...')
                        ->preload()
                        ->multiple()
                        //->searchable()
                        ->relationship(
                            name: 'tags',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn (Builder $query) => $query->WithType('kteo_tags'))
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::upper($record->name))
                        ->nestedRecursiveRules([
                            'min:1',
                            'max:30',
                            self::$model::getKteoTags()
                        ]),
                        // ->saveRelationshipsUsing(function ($component, $state, $record) {
                        //     foreach ($state as $filePath) {
                        //         $record->tags()->attach([
                        //             'path' => $filePath,
                        //             'original_name' => $component->getStatePath('original_name')[$filePath] ?? null,
                        //         ]);
                        //     }
                        // }),
                    Forms\Components\TextInput::make('check_type')
                        ->required()
                        ->maxLength(50)
                        ->columnSpan(2),
                    SpatieMediaLibraryFileUpload::make('certificates')
                        ->label(Str::ucfirst(__('documents')))
                        ->collection('certificates')
                        ->downloadable()
                        ->multiple()
                        ->columnSpan(2),
                // ])->columns(2)
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('check_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('check_result')
                    ->formatStateUsing(fn (string $state): string => Str::upper($state))
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(function (string $state): string {
                        return match($state) {
                            'pass' => 'success',
                            'fail' => 'danger'
                        };
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                SpatieTagsColumn::make('tags')
                    ->label('cTags')
                    ->translateLabel()
                    ->formatStateUsing(fn (string $state): string => Str::camel($state)),
                SpatieMediaLibraryImageColumn::make('certificates')->collection('certificates'),
            ])
            ->filters([
                Tables\Filters\Filter::make('check_result')
                    //->label(trans('filament-users::user.resource.verified'))
                    ->query(fn(Builder $query): Builder => $query->where('check_result','pass')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleChecks::route('/'),
            'create' => Pages\CreateVehicleCheck::route('/create'),
            'view' => Pages\ViewVehicleCheck::route('/{record}'),
            'edit' => Pages\EditVehicleCheck::route('/{record}/edit'),
        ];
    }
}
