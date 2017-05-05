<?php

namespace App\Policies;

use App\Models\Status;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function destroy (User $user , Status $status)
    {
        return $status->user_id === $user->id;
    }
}
