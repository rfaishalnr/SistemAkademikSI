<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PilihDospemSkripsi;
use Illuminate\Auth\Access\HandlesAuthorization;

class PilihDospemSkripsiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PilihDospemSkripsi $pilihDospemSkripsi): bool
    {
        return $user->can('view_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PilihDospemSkripsi $pilihDospemSkripsi): bool
    {
        return $user->can('update_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PilihDospemSkripsi $pilihDospemSkripsi): bool
    {
        return $user->can('delete_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, PilihDospemSkripsi $pilihDospemSkripsi): bool
    {
        return $user->can('force_delete_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, PilihDospemSkripsi $pilihDospemSkripsi): bool
    {
        return $user->can('restore_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, PilihDospemSkripsi $pilihDospemSkripsi): bool
    {
        return $user->can('replicate_pilih::dospem::skripsi');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_pilih::dospem::skripsi');
    }
}
