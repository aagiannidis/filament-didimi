<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RefuelingOrder;
use App\Models\States\RefuelingOrder\Approved;
use App\Models\States\RefuelingOrder\PendingApproval;
use App\Models\States\RefuelingOrder\Draft;
use App\Models\States\RefuelingOrder\Printed;
use App\Models\States\RefuelingOrder\Closed;
use App\Models\States\RefuelingOrder\ReceiptAttached;
use App\Models\States\RefuelingOrder\Cancelled;
use App\Models\States\RefuelingOrder\RefuelingOrderState;

class RefuelingOrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RefuelingOrder $order): bool
    {
        return false;
    }

    public function create(User $user)
    {
        return $user->hasRole(['Operator', 'Secretarial']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RefuelingOrder $order)
    {
        if (($order->state->equals(Printed::class)) ||
            ($order->state->equals(ReceiptAttached::class)) ||
            ($order->state->equals(Closed::class)) ||
            ($order->state->equals(Approved::class))) {
                return false;
        }

        // Anyone can edit if still in draft or else only secretarial and manager
        // provided we are not in any of the above states.
        return ($order->state->equals(Draft::class)) ||
                ($user->hasRole(['Secretarial','RefuelingOrdersManager']));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RefuelingOrder $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RefuelingOrder $order): bool
    {
        return false;
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
        return $user->hasRole('RefuelingOrdersManager') &&
                $order->state->equals(PendingApproval::class);
    }

    public function print(User $user, RefuelingOrder $order)
    {
        return $user->hasRole(['Secretarial','RefuelingOrdersManager']);

        return $user->hasRole(['Secretarial','RefuelingOrdersManager']) &&
                $order->state->equals(Approved::class);
    }

}
