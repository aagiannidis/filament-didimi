<?php

namespace App\Models\States\RefuelingOrderStates;

use App\Models\States\RefuelingOrderStates\RefuelingOrderState;


class Draft extends RefuelingOrderState
{
    public static $name = 'Draft';

    public static function heroIcon(): string
    {
        return 'heroicon-o-pencil-square';
    }

    public static function allowableActions(): array
    {
        return [
            'menu_actions' => [
                // 'approve' => 'Approve',
                // 'issueDocuments' => 'Print Order',
                //'deny' => 'Deny',
                //'denyWithActions' => 'Return',
                //'close' => 'Close',
                'submit' => 'Submit',
                'cancel' => 'Cancel',
                //'archive' => 'Archive',
            ]
        ];
    }
}
