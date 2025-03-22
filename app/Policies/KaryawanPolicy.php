<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Auth\Access\Response;

class KaryawanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Karyawan $karyawan): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Karyawan $karyawan): bool
    {
        return true;
    }

    public function delete(User $user, Karyawan $karyawan): bool
    {
        return true;
    }

    public function restore(User $user, Karyawan $karyawan): bool
    {
        return true;
    }

    public function forceDelete(User $user, Karyawan $karyawan): bool
    {
        return true;
    }
}
