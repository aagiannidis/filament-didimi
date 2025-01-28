<?php

namespace App\Models\States\RefuelingOrderStates;

use App\Models\States\RefuelingOrderStates\RefuelingOrderState;

class Processing extends RefuelingOrderState
{
    public static $name = 'Processing';

    public static function heroIcon(): string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function allowableActions(): array
    {
        return [
            'menu_actions' => [
                // 'approve' => 'Approve',
                'issueDocuments' => 'Print Order',
                'deny' => 'Deny',
                'denyWithActions' => 'Return',
                //'close' => 'Close',
                //'submit' => 'Submit',
                'cancel' => 'Cancel',
                //'archive' => 'Archive',
            ]
        ];
    }
}
