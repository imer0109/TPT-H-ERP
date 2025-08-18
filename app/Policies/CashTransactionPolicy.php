<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CashTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can validate the transaction.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CashTransaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function validate(User $user, CashTransaction $transaction)
    {
        // Permettre à tous les utilisateurs de valider les transactions pour l'instant
        // Vous pouvez ajouter des conditions spécifiques plus tard
        return true;
    }
}