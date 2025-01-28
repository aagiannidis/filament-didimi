<?php

namespace App\Models\States\RefuelingOrderStates;

use App\Models\States\RefuelingOrderStates\RefuelingOrderState;


class Closed extends RefuelingOrderState
{
    public static $name = 'Closed';

    public static function heroIcon(): string
    {
        return 'heroicon-o-clipboard-document-check';
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
                'archive' => 'Archive',
            ]
        ];
    }
}
