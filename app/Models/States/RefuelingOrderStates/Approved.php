<?php

namespace App\Models\States\RefuelingOrderStates;

use App\Models\States\RefuelingOrderStates\RefuelingOrderState;

class Approved extends RefuelingOrderState
{
    public static $name = 'Approved';

    public static function heroIcon(): string
    {
        return 'heroicon-o-check-circle';
    }

    public static function allowableActions(): array
    {
        return [
            'menu_actions' => [
                // 'approve' => 'Approve',
                // 'issueDocuments' => 'Print Order',
                //'deny' => 'Deny',
                //'denyWithActions' => 'Return',
                'close' => 'Close',
                //'submit' => 'Submit',
                //'cancel' = > 'Cancel',
                //'archive' = > 'Archive',
            ]
        ];
    }

    // attachReceipt
    //     attachDocuments
    //     verifyReceipt
    //     verifyDocuments
}
