<?php

namespace App\Filament\Resources\RefuelingOrderResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\RefuelingOrder;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\FilamentHandleSecureDocuments;
use App\Filament\Resources\RefuelingOrderResource;
use ZeeshanTariq\FilamentAttachmate\Core\HandleAttachments;

class CreateRefuelingOrder extends CreateRecord
{
    //use HandleAttachments;
    use FilamentHandleSecureDocuments;

    protected static string $resource = RefuelingOrderResource::class;

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('UpdateAuthor')
                ->using(function (array $data, string $model): Model {
                    return $model::create($data);
                })
                ->fillForm(fn($get): array => [
                    'authorId' => Auth::user()->id,
                    'author' => $get('state'),
                ])
                ->form([
                    \Filament\Forms\Components\TextInput::make('author')
                        ->label('Author'),
                    \Filament\Forms\Components\Select::make('authorId')
                        ->label('Author')
                        ->options(User::query()->pluck('name', 'id'))
                        ->required(),
                    \ZeeshanTariq\FilamentAttachmate\Forms\Components\AttachmentFileUpload::make()
                        ->label('Invoice or Receipt')
                        ->hint('Necessary for all')
                        ->afterStateUpdated(function ($state, $set, $get, $livewire) {
                            // When a file is uploaded, show the modal dialog for selecting type
                            if ($state && count($state) <= 2) {
                                $uploadedFiles = $get('attachments') ?? [];
                                $typesUsed = array_column($uploadedFiles, 'type');
                                //$set('state',$uploadedFiles);
                                // \Filament\Actions\Action::make('attachment_type_modal')
                                //     ->form([
                                //         \Filament\Forms\Components\Select::make('attachment_type')
                                //             ->label('Attachment Type')
                                //             ->options([
                                //                 'contract' => 'Contract',
                                //                 'invoice' => 'Invoice',
                                //             ])
                                //             ->required(),
                                //     ])
                                //     ->action(function ($data) use ($state, $set, $get) {
                                //         // Save the type to the uploaded file
                                //         // $attachments = $get('attachments') ?? [];
                                //         // $attachments[count($attachments) - 1]['type'] = $data['attachment_type'];
                                //         // $set('attachments', $attachments);
                                //     })
                            }



                            //             // Notification::make()
                            //             //     ->title('Attachment Type Saved')
                            //             //     ->success()
                            //             //     ->send();
                            //         });
                            // //         ->open();
                            // //         // ->closeAction(fn () => Notification::make()
                            // //         //     ->title('Attachment Type Not Selected')
                            // //         //     ->warning()
                            // //         //     ->send())
                            // }

                            //}
                        })
                        // ->deleteAction(function ($file, $set, $get) {
                        //     // Handle deletion of a file
                        //     $attachments = $get('attachments') ?? [];
                        //     $attachments = array_filter($attachments, fn ($attachment) => $attachment['filename'] !== $file);
                        //     $set('attachments', $attachments);

                        //     // Notification::make()
                        //     //     ->title('Attachment Deleted')
                        //     //     ->success()
                        //     //     ->send();
                        // })
                        ->formatStateUsing(function (?RefuelingOrder $record) {
                            return $record?->attachments()->get()->pluck('filename')->toArray();
                        })->dehydrated(false)

                ])
                ->action(function (array $data, Component $livewire): void {
                    // $record->author()->associate($data['authorId']);
                    // $record->save();
                    // $uploadedFiles = $get('attachments') ?? [];

                    $livewire->set('state', 'modal_input');
                    //$m->getComponents()->state = $data;
                    //dd($data);
                })
        ];
    }
}
