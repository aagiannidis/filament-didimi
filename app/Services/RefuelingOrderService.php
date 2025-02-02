<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\RefuelingOrder;
use Illuminate\Support\Facades\Auth;
use App\Policies\RefuelingOrderPolicy;
use App\Exceptions\GenericServiceException;
use Illuminate\Database\Eloquent\Collection;
use App\Models\States\RefuelingOrderStates\Denied;

class RefuelingOrderService
{
    public static function sendMessage(array $data, Collection $records)
    {
        // $textMessages = collect([]);

        // $records->map(function ($record) use ($data, $textMessages) {
        //     $textMessage = self::sendTextMessage($record, $data);

        //     $textMessages->push($textMessage);
        // });

        // TextMessage::insert($textMessages->toArray());
    }


    public function deny(RefuelingOrder $record, ?User $user = null): void
    {

        $user = $user ?? auth()->user();

        if (!$user) {
            throw new \Exception('No authenticated user available.');
        }

        if (!$user) {
            throw new GenericServiceException('Deny not allowed. You are not logged in.');
        }

        $allow = app(RefuelingOrderPolicy::class)->deny($user, $record);

        if (!($allow)) {
            throw new GenericServiceException('Deny not allowed. Policy says no.');
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user) {
                $alow = $user->canDeny($record);
            }
        }
        $allow = auth()->user->can('deny', $record);
        $allow = auth()->user->canDeny($record);


        // if (!($record->hasFlag('testflag'))) {
        //     throw new GenericServiceException('Deny not allowed. No flag.');
        // }

        $record->state->transitionTo(\App\Models\States\RefuelingOrderStates\Cancelled::class);

        if ($record->state->canTransitionTo(Denied::class)) {
            $record->state->transitionTo(Denied::class);
        } else {
            $message = 'You can only trasit to ' . implode(", ", $record->state->transitionableStates()) . ' states from here...';
            throw new GenericServiceException('Deny not allowed. ' . $message);
        }

        // $message = Str::replace('{name}', $record->name, $data['message']);

        // // send the actual message here

        // return [
        //     'message' => $message,
        //     'sent_by' => auth()?->id() ?? null,
        //     'status' => TextMessage::STATUS['PENDING'],
        //     'response' => '',
        //     'sent_to' => $record->id,
        //     'remarks' => $data['remarks'] ?? null,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ];
    }
}
