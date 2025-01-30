<?php

namespace App\Filament\Resources\RefuelingOrderResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Forms\Get;
use App\Models\RefuelingOrder;
use App\Models\SecureDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Traits\FilamentHandleSecureDocuments;
use App\Filament\Resources\RefuelingOrderResource;
use TomatoPHP\FilamentDocs\Services\Contracts\DocsVar;
use ZeeshanTariq\FilamentAttachmate\Core\HandleAttachments;

class EditRefuelingOrder extends EditRecord
{
    //use HandleAttachments;
    use FilamentHandleSecureDocuments;

    protected static string $resource = RefuelingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            Actions\Action::make('uploadNonRelRefuelingOrderDocuments')
                ->label('Upload More')
                ->icon('heroicon-m-clipboard')
                ->form([
                    ...\App\Filament\CustomForms\NonRelationRefuelingUploadForm::schema()
                ])
                ->action(function (array $data, RefuelingOrder $record): void {
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
                }),
            // Actions\Action::make('UpdateAuthor')
            //     // ->using(function (array $data, string $model): Model {
            //     //     return $model::create($data);
            //     // })
            //     ->fillForm(fn(\Filament\Forms\Get $get): array => [
            //         // 'authorId' => Auth::user()->id,
            //         // 'author' => $get('state'),
            //     ])
            //     ->form([
            //         \Filament\Forms\Components\TextInput::make('author')
            //             ->label('Author'),
            //         \Filament\Forms\Components\Select::make('authorId')
            //             ->label('Author')
            //             ->options(User::query()->pluck('name', 'id'))
            //             ->required(),
            //         \ZeeshanTariq\FilamentAttachmate\Forms\Components\AttachmentFileUpload::make()
            //             ->label('Invoice or Receipt')
            //             ->hint('Necessary for all')
            //             ->afterStateUpdated(function ($state, $set, $get, $livewire) {
            //                 // When a file is uploaded, show the modal dialog for selecting type
            //                 if ($state && count($state) <= 2) {
            //                     $uploadedFiles = $get('attachments') ?? [];
            //                     $typesUsed = array_column($uploadedFiles, 'type');
            //                     $set('state', $uploadedFiles);
            //                     \Filament\Actions\Action::make('attachment_type_modal')
            //                         ->form([
            //                             \Filament\Forms\Components\Select::make('attachment_type')
            //                                 ->label('Attachment Type')
            //                                 ->options([
            //                                     'contract' => 'Contract',
            //                                     'invoice' => 'Invoice',
            //                                 ])
            //                                 ->required(),
            //                         ])
            //                         ->action(function ($data) use ($state, $set, $get) {
            //                             // Save the type to the uploaded file
            //                             $attachments = $get('attachments') ?? [];
            //                             // $attachments[count($attachments) - 1]['type'] = $data['attachment_type'];
            //                             // $set('attachments', $attachments);

            //                             Notification::make()
            //                                 ->title('Attachment Type Saved')
            //                                 ->success()
            //                                 ->send();
            //                         });
            //                     //->open()
            //                     // ->closeAction(fn() => Notification::make()
            //                     //     ->title('Attachment Type Not Selected')
            //                     //     ->warning()
            //                     //     ->send());
            //                 }
            //             })
            //     ]),
            // // ->deleteAction(function ($file, $set, $get) {
            // //     // Handle deletion of a file
            // //     $attachments = $get('attachments') ?? [];
            // //     $attachments = array_filter($attachments, fn ($attachment) => $attachment['filename'] !== $file);
            // //     $set('attachments', $attachments);

            // //     Notification::make()
            // //         ->title('Attachment Deleted')
            // //         ->success()
            // //         ->send();
            // // })
            // // ->formatStateUsing(function (?RefuelingOrder $record) {
            // //         return $record?->attachments()->get()->pluck('filename')->toArray();
            // //     })->dehydrated(false)
            // // ->action(function (array $data, Component $livewire): void {
            // //     // $record->author()->associate($data['authorId']);
            // //     // $record->save();
            // //     // $uploadedFiles = $get('attachments') ?? [];

            // //     //$livewire->set('state', 'modal_input');
            // //     //$m->getComponents()->state = $data;
            // //     //dd($data);
            // // }),
            Actions\Action::make('uploadRefuelingOrderDocuments')
                ->label('New Upload Set')
                ->requiresConfirmation()
                ->modalHeading('Delete All And Upload New')
                ->modalDescription('Are you sure you\'d like to delete all files and upload new ones ? This cannot be undone.')
                ->modalSubmitActionLabel('Yes, I am sure')
                ->icon('heroicon-m-clipboard')
                //->requiresConfirmation()
                ->form([
                    ...\App\Filament\CustomForms\RefuelingUploadForm::schema()
                ])
                ->before(function () {
                    // this will trigger before a save
                })
                ->action(function (array $data, $livewire): void {
                    // $record->author()->associate($data['authorId']);
                    // $record->save();
                    // $uploadedFiles = $get('attachments') ?? [];
                    // dd($data);
                    // $livewire->set('state', 'modal_input');
                    //$m->getComponents()->state = $data;
                    //dd($data);
                })
                ->afterFormValidated(function () {})



            // function (array $data) {
            //     // ...
            // },

        ];
    }
}
