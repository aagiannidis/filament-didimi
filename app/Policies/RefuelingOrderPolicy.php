<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RefuelingOrder;

class RefuelingOrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_refueling::order');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RefuelingOrder $order): bool
    {
        return $user->can('view_refueling::order');
    }

    public function create(User $user)
    {
        return $user->can('create_refueling::order');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RefuelingOrder $order)
    {
        if ($order->isLockedForEdits()) {
            return false;
        }

        if (($user->hasRole(['Operator'])) && ($order->state->equals(\App\Models\States\RefuelingOrderStates\Draft::class))) {
            if ($user->can('update_refueling::order'))
                return true;
        }

        if ($user->hasRole(['Secretarial', 'RefuelingOrdersManager'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RefuelingOrder $order): bool
    {
        return $user->can('delete_refueling::order');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RefuelingOrder $order): bool
    {
        return $user->can('restore_refueling::order');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RefuelingOrder $order): bool
    {
        return false;
    }

    /*
        Custom Policies here
    */

    public function approve(User $user, RefuelingOrder $order)
    {
        return $user->can('approve_refueling::order') &&
            $order->state->canTransitionTo(\App\Models\States\RefuelingOrderStates\Approved::class);
    }

    public function cancel(User $user, RefuelingOrder $order)
    {
        return $user->can('cancel_refueling::order') &&
            $order->state->canTransitionTo(\App\Models\States\RefuelingOrderStates\Cancelled::class);
    }

    public function archive(User $user, RefuelingOrder $order)
    {
        return $user->can('archive_refueling::order') &&
            $order->state->canTransitionTo(\App\Models\States\RefuelingOrderStates\Archived::class);
    }

    public function issueDocuments(User $user, RefuelingOrder $order)
    {
        return $user->can('issue_documents_refueling::order') &&
            $order->state->equals(\App\Models\States\RefuelingOrderStates\Processing::class);
    }

    public function deny(User $user, RefuelingOrder $order)
    {
        return $user->can('deny_refueling::order') &&
            $order->state->canTransitionTo(\App\Models\States\RefuelingOrderStates\Cancelled::class);
    }

    public function denyWithActions(User $user, RefuelingOrder $order)
    {
        return $user->can('deny_actionable_refueling::order') &&
            $order->state->canTransitionTo(\App\Models\States\RefuelingOrderStates\Draft::class);
    }

    public function close(User $user, RefuelingOrder $order)
    {
        return $user->can('close_refueling::order') &&
            $order->state->canTransitionTo(\App\Models\States\RefuelingOrderStates\Closed::class);
    }

    public function submit(User $user, RefuelingOrder $order)
    {
        return $user->can('submit_refueling::order') &&
            $order->state->canTransitionTo(\App\Models\States\RefuelingOrderStates\PendingApproval::class);
    }

    public function examine(User $user, RefuelingOrder $order)
    {
        return $user->can('examine_refueling::order') &&
            $order->state->equals(\App\Models\States\RefuelingOrderStates\PendingApproval::class);
    }


    public function attachReceipt(User $user, RefuelingOrder $order)
    {
        return $user->can('attach_receipt_refueling::order') &&
            $order->modelAllowsAttachReceipt();
    }

    public function attachDocuments(User $user, RefuelingOrder $order)
    {
        return $user->can('attach_signed_doc_refueling::order') &&
            $order->modelAllowsAttachDocuments();
    }

    public function verifyReceipt(User $user, RefuelingOrder $order)
    {
        return $user->can('verify_receipt_refueling::order') &&
            $order->modelAllowsVerifyReceipt();
    }

    public function verifyDocuments(User $user, RefuelingOrder $order)
    {
        // if (!Gate::allows('verifyDocuments', $this)) {
        return $user->can('verify_signed_doc_refueling::order') &&
            $order->modelAllowsVerifyDocuments();
    }

    // Add flag -> printed / attached scanned signed doc / attached receipt
    // Verify scanned signed doc / Verify attached receipt

}
