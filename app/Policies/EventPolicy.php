<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function update(User $user, Event $event): bool
    {
        return $event->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $event->user_id === $user->id;
    }

    public function approve(Admin $admin): bool
    {
        return $admin->can('approve_event');
    }
}
