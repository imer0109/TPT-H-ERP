# Correction du Système de Permissions

## Problème identifié

Le compte "Gestionnaire" (manager@tpt-h.com) ne peut pas accéder aux fonctionnalités suppliers malgré les permissions assignées.

## Causes possibles

1. **Logique de vérification incorrecte** : La condition `!canAccessModule() && !hasPermission()` est incorrecte
2. **Cache des permissions** : Le cache peut contenir des données obsolètes
3. **Permissions non assignées** : Le rôle "manager" n'a peut-être pas les permissions suppliers assignées

## Solutions appliquées

### 1. Trait `ChecksSupplierPermissions` créé
- Fichier : `app/Traits/ChecksSupplierPermissions.php`
- Méthode `checkSupplierPermission()` qui vérifie dans l'ordre :
  1. Rôle administrateur
  2. Accès au module suppliers
  3. Permission spécifique

### 2. Tous les contrôleurs Supplier mis à jour
- `SupplierOrderController`
- `SupplierDeliveryController`
- `SupplierIssueController`
- `SupplierPaymentController`
- `FournisseurController`

### 3. Commandes de diagnostic créées
- `php artisan user:check-permissions manager@tpt-h.com` pour vérifier les permissions

## Actions à effectuer

### 1. Vider le cache
```bash
php artisan cache:clear
php artisan config:clear
```

### 2. Vérifier les permissions du rôle manager
```bash
php artisan tinker
>>> $managerRole = \App\Models\Role::where('slug', 'manager')->first();
>>> $managerRole->permissions()->where('module', 'suppliers')->count();
>>> $managerRole->permissions()->where('module', 'suppliers')->pluck('slug');
```

### 3. Vérifier les permissions de l'utilisateur
```bash
php artisan user:check-permissions manager@tpt-h.com
```

### 4. Réassigner les permissions au rôle manager si nécessaire
```bash
php artisan tinker
>>> $managerRole = \App\Models\Role::where('slug', 'manager')->first();
>>> $supplierPermissions = \App\Models\Permission::where('module', 'suppliers')->get();
>>> $managerRole->permissions()->syncWithoutDetaching($supplierPermissions->pluck('id'));
```

### 5. Vérifier que l'utilisateur a le rôle manager
```bash
php artisan tinker
>>> $user = \App\Models\User::where('email', 'manager@tpt-h.com')->first();
>>> $user->roles()->pluck('slug');
>>> $user->roles()->pluck('nom');
```

## Test de la correction

1. Se connecter avec manager@tpt-h.com
2. Essayer d'accéder à `/fournisseurs`
3. Essayer d'accéder à `/fournisseurs/orders`
4. Vérifier que les pages s'affichent correctement

## Si le problème persiste

1. Vérifier que le rôle "manager" existe avec le slug "manager"
2. Vérifier que les permissions suppliers existent dans la table `permissions`
3. Vérifier que les permissions sont bien assignées au rôle dans `permission_role`
4. Vérifier que l'utilisateur a bien le rôle assigné dans `role_user`
5. Vider le cache et réessayer