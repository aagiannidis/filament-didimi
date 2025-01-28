<?php

namespace App\Filament\Resources\RefuelingOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\SecureDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SecureDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'secureDocuments';

    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('SecureDocument')
    //                 ->required()
    //                 ->maxLength(255),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Documents')
            ->columns([
                Tables\Columns\TextColumn::make('original_filename'),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->requiresConfirmation()
                    //->hidden(fn($record) => !(Gate::Allows(json_decode($modelState)->gateFunction, $record)))
                    ->action(fn($record) => self::dostuff($record)),
            ])
            ->bulkActions([]);
    }

    public static function doStuff(SecureDocument $record)
    {

        if (Auth::id()) {
            return response()->download(storage_path('app/private/' . $record->path));
        } else {
            abort(404);
        }
    }
}
