<?php

namespace ZeeshanTariq\FilamentAttachmate\Forms\Components;

use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\FileUpload;


class AttachmentFileUpload extends FileUpload
{
    public static function make(string $name = null): static
    {
        return parent::make('attachments')
            ->label('Attachments')
            ->multiple()
            ->openable();
    }

}
