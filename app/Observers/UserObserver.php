<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
   public function updated(User $user)
{
    // Hanya kirim email jika kolom 'status' atau 'role' berubah
    if ($user->isDirty('status') || $user->isDirty('role')) {
        $user->notify(new \App\Notifications\StatusMitraNotification($user));
    }
}

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
