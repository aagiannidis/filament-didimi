<?php

namespace App\Models\States\RefuelingOrder;

use App\Models\States\RefuelingOrder;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Transition;
use Spatie\ModelStates\StateConfig;

class RefuelingOrderState extends State
{
    // public const DRAFT = 'draft';
    // public const PENDING_APPROVAL = 'pending_approval';
    // public const APPROVED = 'approved';
    // public const PRINTED = 'printed';
    // public const CANCELED = 'canceled';

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, PendingApproval::class)
            ->allowTransition([Draft::class, PendingApproval::class, Approved::class, Printed::class], Cancelled::class)
            ->allowTransition(PendingApproval::class, Approved::class)
            ->allowTransition(Approved::class, Printed::class)
            ->allowTransition(Printed::class, ReceiptAttached::class)
            ->allowTransition(ReceiptAttached::class, Closed::class)
        ;
    }

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
