<?php

namespace App\Models\States\RefuelingOrderStates;

use App\Models\States\RefuelingOrderStates\RefuelingOrderState;


class PendingApproval extends RefuelingOrderState
{
    public static $name = 'Pending Approval';

    public static function heroIcon(): string
    {
        return 'heroicon-o-arrow-top-right-on-square';
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
                //'submit' => 'Submit',
                //'cancel' => 'Cancel',
                //'archive' => 'Archive',
            ]
        ];
    }
}
