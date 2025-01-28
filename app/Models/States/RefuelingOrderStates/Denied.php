<?php

namespace App\Models\States\RefuelingOrderStates;

use App\Models\States\RefuelingOrderStates\RefuelingOrderState;

class Denied extends RefuelingOrderState
{
    public static $name = 'Denied';

    public static function heroIcon(): string
    {
        return 'heroicon-o-bars_arrow_down';
    }

    public static function allowableActions(): array
    {
        return [
            'menu_actions' => [
                // 'approve' => 'Approve',
                // 'issueDocuments' => 'Print Order',
                //'deny' => 'Deny',
                //'denyWithActions' => 'Return',
                // 'close' => 'Close',
                //'submit' => 'Submit',
                //'cancel' => 'Cancel',
                'archive' => 'Archive',
            ]
        ];
    }
}
