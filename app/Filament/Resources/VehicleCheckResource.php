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
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Infolists\Components\TextEntry;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MultiSelect;
use Filament\Infolists\Components\BadgeEntry;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Forms\Components\SpatieTagsInput;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleCheckResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\SpatieMediaLibraryFileEntry;
use App\Filament\Resources\VehicleCheckResource\RelationManagers;
use Parallax\FilamentComments\Infolists\Components\CommentsEntry;
use Filament\Infolists\Components\TextEntry as ComponentsTextEntry;

class VehicleCheckResource extends Resource
{
    protected static ?string $model = VehicleCheck::class;

    protected static ?string $navigationIcon = 'mdi-checkbox-multiple-marked-outline';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\TextEntry::make('asset.license_plate'),
                \Filament\Infolists\Components\TextEntry::make('check_result'),
                \Filament\Infolists\Components\TextEntry::make('check_date'),
                \Filament\Infolists\Components\TextEntry::make('check_type'),
                \Filament\Infolists\Components\TextEntry::make('tags')
                    ->label('Tags')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->tags->pluck('name')->join(', ')
                    )
                    ->placeholder('No tags assigned'),
                // Infolists\Components\TextEntry::make('return_date'),
                // Infolists\Components\TextEntry::make('rental_cost'),
                // Infolists\Components\TextEntry::make('rental_status'),
                // Infolists\Components\TextEntry::make('asset.license_plate'),
                // CommentsEntry::make('filament_comments'),
                \Filament\Infolists\Components\RepeatableEntry::make('media')
                    ->label('Documents')
                    ->translateLabel()
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('name'),
                        \Filament\Infolists\Components\TextEntry::make('file_name')
                            ->tooltip('Download the file here...')
                            ->formatStateUsing(
                                fn($state, $record) =>
                                "<a href='{$record->getFullUrl()}' target='_blank'><i class='fa fa-download'></i> {$record->name}</a>"
                            )
                            ->icon('heroicon-o-folder-arrow-down')
                            // "<a href='{$media->getFullUrl()}' target='_blank'>{$media->name}</a>"
                            //     ->formatStateUsing(fn ($state, $record) =>
                            //         $record->getMedia('certificates')->map(fn ($media) =>
                            //             "<a href='{$media->getFullUrl()}' target='_blank'>{$media->name}</a>"
                            //         )->join(', '))
                            ->html(), // Render links as HTML
                        //     ->formatStateUsing(fn ($state) => strip_tags($state)),
                        // \Filament\Infolists\Components\TextEntry::make('created_at')
                        //     ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->diffForHumans()),
                    ])
                    ->columns(3),


                \Filament\Infolists\Components\RepeatableEntry::make('filamentComments')
                    ->label('Recent Comments')
                    ->translateLabel()
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('user.name'),
                        \Filament\Infolists\Components\TextEntry::make('comment')
                            ->formatStateUsing(fn($state) => strip_tags($state)),
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            //->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->diffForHumans()),
                            ->since()
                            ->dateTimeTooltip(),
                    ])
                    ->columns(3)
            ])
            ->columns(1);
    }

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
                        modifyQueryUsing: fn(Builder $query) => $query->WithType('kteo_tags')
                    )
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::upper($record->name))
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
                    ->formatStateUsing(fn(string $state): string => Str::upper($state))
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(function (string $state): string {
                        return match ($state) {
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
                    ->formatStateUsing(fn(string $state): string => Str::camel($state)),
                SpatieMediaLibraryImageColumn::make('certificates')->collection('certificates'),
            ])
            ->filters([
                Tables\Filters\Filter::make('check_result')
                    //->label(trans('filament-users::user.resource.verified'))
                    ->query(fn(Builder $query): Builder => $query->where('check_result', 'pass')),
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
