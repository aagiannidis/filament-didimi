<?php

namespace App\Filament\Resources\RefuelingOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use TomatoPHP\FilamentDocs\Filament\Actions\Table\PrintAction;
use TomatoPHP\FilamentDocs\Filament\Resources\DocumentResource\Pages as TomatoPHPDocPrintPages;


class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ref')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('body')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('documents')
            ->columns([
                Tables\Columns\TextColumn::make('ref'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // TODO: Atm, this action overrides some control checks and can be
                // shown even if we are not in edit mode.
                PrintAction::make('print')
                    ->icon('heroicon-s-printer')
                    ->title(fn($record) => 'RefuelingOrderTemplate' . '#'. $record->id)
                    ->route(
                        fn ($record) => TomatoPHPDocPrintPages\PrintDocument::getUrl(['record' => $record])
                    )
                    ->color('warning')
                    ->iconButton()
                    ->tooltip(trans('filament-docs::messages.documents.actions.print')),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
