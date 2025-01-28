<?php

namespace App\Models\States\RefuelingOrderStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\Transition;
use Spatie\ModelStates\StateConfig;

// use App\Models\States\RefuelingOrder\Draft;
// use App\Models\States\RefuelingOrder\Closed;
// use App\Models\States\RefuelingOrder\Printed;
// use App\Models\States\RefuelingOrder\Approved;
// use App\Models\States\RefuelingOrder\Cancelled;
// use App\Models\States\RefuelingOrder\PendingApproval;
// use App\Models\States\RefuelingOrder\ReceiptAttached;


abstract class RefuelingOrderState extends State
{
    // public const DRAFT = 'draft';
    // public const PENDING_APPROVAL = 'pending_approval';
    // public const APPROVED = 'approved';
    // public const PRINTED = 'printed';
    // public const CANCELED = 'canceled';
    abstract public static function heroIcon(): string;

    abstract public static function allowableActions(): array;

    public static function allGates(): array
    {
        return [
            '{
                "gateFunction" : "approve",
                "menuLabel" : "Approve"
            }',
            '{
                "gateFunction" : "issueDocuments",
                "menuLabel" : "Print Order"
            }',
            '{
                "gateFunction" : "deny",
                "menuLabel" : "Deny"
            }',
            '{
                "gateFunction" : "denyWithActions",
                "menuLabel" : "Return"
            }',
            '{
                "gateFunction" : "close",
                "menuLabel" : "Close"
            }',
            '{
                "gateFunction" : "submit",
                "menuLabel" : "Submit"
            }',
            '{
                "gateFunction" : "cancel",
                "menuLabel" : "Cancel"
            }',
            '{
                "gateFunction" : "archive",
                "menuLabel" : "Archive"
            }',
            '{
                "gateFunction" : "examine",
                "menuLabel" : "Open For Processing"
            }'

        ];
    }


    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, PendingApproval::class)
            ->allowTransition([Draft::class, PendingApproval::class, Approved::class, Approved::class, Returned::class], Cancelled::class)
            ->allowTransition(Returned::class, Draft::class)
            ->allowTransition(PendingApproval::class, Processing::class)
            ->allowTransition(Processing::class, Approved::class)
            ->allowTransition(Processing::class, Denied::class)
            ->allowTransition(Processing::class, Returned::class)
            ->allowTransition(Approved::class, Closed::class)
            ->allowTransition([Cancelled::class, Closed::class, Denied::class], Archived::class)
            ->registerState(Approved::class)
            ->registerState(Archived::class)
            ->registerState(Cancelled::class)
            ->registerState(Closed::class)
            ->registerState(Denied::class)
            ->registerState(Draft::class)
            ->registerState(PendingApproval::class)
            ->registerState(Processing::class)
            ->registerState(Returned::class)
        ;
    }

    // Drafted->SubmittedForApproval->Processing->Approved->Closed->Archived
    // Drafted->SubmittedForApproval->Processing->Approved->Cancelled->Archived
    // Drafted->SubmittedForApproval->Processing->Denied
    // Drafted->SubmittedForApproval->Processing->Returned->

    // -x Draft
    // -x PendingApproval
    // -x Processing
    // -x Denied
    // x Returned
    // -x Approved
    // -x Closed
    // -x Cancelled
    // -x Archived







    // For Processing to advance to approved, the document_issued flag must be set
    // For Approved to Closed, the documents_verified flags must be set
    // For Processing to Denied, the documents_delete flags must be set


    // public static function config(): array
    // {
    //     return [
    //         self::DRAFT => [
    //             self::PENDING_APPROVAL,
    //             self::CANCELED
    //         ],
    //         self::PENDING_APPROVAL => [
    //             self::APPROVED,
    //             self::DRAFT,
    //             self::CANCELED
    //         ],
    //         self::APPROVED => [
    //             self::PRINTED,
    //             self::DRAFT
    //         ],
    //         self::PRINTED => [
    //             self::DRAFT
    //         ]
    //     ];
    // }
}
