<?php

namespace App\Traits;

use App\Models\SecureDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

trait FilamentHandleSecureDocuments
{

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
    }

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        $record = $this->record;



        // If for any reason we do stupid things and we dont get both the type and document file
        // then we will be in trouble. So lets find if we have any malicious data and throw them out
        $invalidEntries = [];

        foreach ($data['members'] as $key => $value) {
            if (is_array($value)) {
                if (count($value) === 2)  array_push($invalidEntries, $key);
            }
        }

        $data['members'] = array_filter($data['members'], (function ($value, $key) use ($invalidEntries) {
            return !in_array($key, $invalidEntries);
        }), ARRAY_FILTER_USE_BOTH);

        $data['members'] = collect($data['members'])->dot()->chunk(3)->toArray();

        foreach ($data['members'] as $key => $file_data) {
            $this->handleAttachment($record, $file_data);
        }
    }

    protected function handleAttachment(Model $record, array $attachment_info): void
    {
        $keys = array_keys($attachment_info);

        $newSecureDocument = $record->secureDocuments()->make();
        $newSecureDocument->type = $attachment_info[$keys[0]];
        $newSecureDocument->random_filename = $attachment_info[$keys[1]];
        $newSecureDocument->original_filename = $attachment_info[$keys[2]];
        $newSecureDocument->path = $attachment_info[$keys[1]];
        $newSecureDocument->uploaded_by_user_id = Auth::user()->id;
        $newSecureDocument->uploaded_at = \Illuminate\Support\Carbon::now();
        $newSecureDocument->expiry_date = \Illuminate\Support\Carbon::now()->addYears(20);
        $newSecureDocument->save();
    }
}
