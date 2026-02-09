<?php

namespace App\Traits;

trait ChecksSupplierPermissions
{
    /**
     * Vérifie si l'utilisateur a la permission d'accéder au module suppliers ou une permission spécifique
     * 
     * @param string|null $permission Permission spécifique à vérifier (ex: 'suppliers.orders.view')
     * @return bool
     */
    protected function checkSupplierPermission($permission = null)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Administrateur a toujours accès
        if ($user->hasRole('administrateur')) {
            return true;
        }
        
        // Vérifier accès au module suppliers
        if ($user->canAccessModule('suppliers')) {
            return true;
        }
        
        // Vérifier permission spécifique si fournie
        if ($permission && $user->hasPermission($permission)) {
            return true;
        }
        
        return false;
    }
}