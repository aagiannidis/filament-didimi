<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SecureDocumentResource\Pages;
use App\Filament\Resources\SecureDocumentResource\RelationManagers;
use App\Models\SecureDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SecureDocumentResource extends Resource
{
    protected static ?string $model = SecureDocument::class;

    protected static ?string $navigationIcon = 'mdi-lock-outline';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('doc_attachable_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('doc_attachable_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('original_filename')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('random_filename')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('path')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('flags'),
                Forms\Components\TextInput::make('uploaded_by_user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('uploaded_at'),
                Forms\Components\TextInput::make('status_history'),
                Forms\Components\DatePicker::make('expiry_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doc_attachable_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doc_attachable_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original_filename')
                    ->searchable(),
                Tables\Columns\TextColumn::make('random_filename')
                    ->searchable(),
                Tables\Columns\TextColumn::make('path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uploaded_by_user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('uploaded_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListSecureDocuments::route('/'),
            'create' => Pages\CreateSecureDocument::route('/create'),
            'view' => Pages\ViewSecureDocument::route('/{record}'),
            'edit' => Pages\EditSecureDocument::route('/{record}/edit'),
        ];
    }
}
