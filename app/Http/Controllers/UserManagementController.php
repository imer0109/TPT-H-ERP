<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        $users = User::with(['roles:id,nom,slug', 'permissions:id,nom,slug'])->paginate(15);
        
        return view('user-management.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur
     */
    public function create()
    {
        $roles = Role::select('id', 'nom', 'slug')->get();
        $permissions = Permission::select('id', 'nom', 'slug', 'module')->get();
        
        return view('user-management.create', compact('roles', 'permissions'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request)
    {
        // Vérification des permissions de création d'utilisateur
        if (!$this->canCurrentUserCreateUser($request->roles ?? [])) {
            return redirect()->back()
                ->with('error', 'Vous n\'avez pas les autorisations nécessaires pour créer cet utilisateur.');
        }
        
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'required|in:actif,inactif,suspendu',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Création de l'utilisateur
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone,
            'statut' => $request->statut,
            'remember_token' => Str::random(60),
        ]);

        // Attribution du rôle par défaut
        $defaultRole = Role::where('slug', 'utilisateur')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id);
        }

        // Attribution des rôles sélectionnés
        if ($request->has('roles')) {
            foreach ($request->roles as $roleId) {
                if ($roleId != $defaultRole->id) { // Éviter les doublons
                    $user->roles()->attach($roleId);
                }
            }
        }

        return redirect()->route('user-management.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['roles:id,nom,slug', 'permissions:id,nom,slug,module']);
        
        return view('user-management.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur
     */
    public function edit(User $user)
    {
        $user->load('roles:id,nom,slug');
        $roles = Role::select('id', 'nom', 'slug')->get();
        
        return view('user-management.edit', compact('user', 'roles'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'required|in:actif,inactif,suspendu',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mise à jour de l'utilisateur
        $userData = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'statut' => $request->statut,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Mise à jour des rôles
        $user->roles()->detach(); // Détacher tous les anciens rôles
        
        // Réattribuer le rôle par défaut
        $defaultRole = Role::where('slug', 'utilisateur')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id);
        }

        // Réattribuer les rôles sélectionnés
        if ($request->has('roles')) {
            foreach ($request->roles as $roleId) {
                if (!$defaultRole || $roleId != $defaultRole->id) { // Éviter les doublons
                    $user->roles()->attach($roleId);
                }
            }
        }

        return redirect()->route('user-management.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        // Ne pas permettre la suppression de soi-même
        if ($user->id === auth()->id()) {
            return redirect()->route('user-management.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('user-management.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Affiche la gestion des rôles
     */
    public function roles()
    {
        $roles = Role::with(['permissions:id,nom,slug,module'])->select('id', 'nom', 'slug', 'description', 'created_at')->paginate(15);
        
        return view('user-management.roles.index', compact('roles'));
    }

    /**
     * Affiche le formulaire de création d'un rôle
     */
    public function createRole()
    {
        $permissions = Permission::select('id', 'nom', 'slug', 'module')->get();
        
        return view('user-management.roles.create', compact('permissions'));
    }

    /**
     * Enregistre un nouveau rôle
     */
    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create([
            'nom' => $request->nom,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        // Attribution des permissions
        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('user-management.roles')
            ->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un rôle
     */
    public function editRole(Role $role)
    {
        $role->load('permissions:id,nom,slug,module');
        $permissions = Permission::select('id', 'nom', 'slug', 'module')->get();
        
        return view('user-management.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Met à jour un rôle
     */
    public function updateRole(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role->update([
            'nom' => $request->nom,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        // Mise à jour des permissions
        $role->permissions()->detach();
        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('user-management.roles')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Supprime un rôle
     */
    public function destroyRole(Role $role)
    {
        // Vérifier si le rôle est utilisé par des utilisateurs
        if ($role->users()->count() > 0) {
            return redirect()->route('user-management.roles')
                ->with('error', 'Impossible de supprimer ce rôle car il est attribué à des utilisateurs.');
        }

        $role->delete();

        return redirect()->route('user-management.roles')
            ->with('success', 'Rôle supprimé avec succès.');
    }

    /**
     * Affiche la gestion des permissions
     */
    public function permissions()
    {
        $permissions = Permission::with('roles')->paginate(15);
        
        return view('user-management.permissions.index', compact('permissions'));
    }

    /**
     * Affiche le formulaire de création d'une permission
     */
    public function createPermission()
    {
        return view('user-management.permissions.create');
    }

    /**
     * Enregistre une nouvelle permission
     */
    public function storePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
            'module' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Permission::create([
            'nom' => $request->nom,
            'slug' => $request->slug,
            'description' => $request->description,
            'module' => $request->module,
        ]);

        return redirect()->route('user-management.permissions')
            ->with('success', 'Permission créée avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'une permission
     */
    public function editPermission(Permission $permission)
    {
        return view('user-management.permissions.edit', compact('permission'));
    }

    /**
     * Met à jour une permission
     */
    public function updatePermission(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'description' => 'nullable|string',
            'module' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $permission->update([
            'nom' => $request->nom,
            'slug' => $request->slug,
            'description' => $request->description,
            'module' => $request->module,
        ]);

        return redirect()->route('user-management.permissions')
            ->with('success', 'Permission mise à jour avec succès.');
    }

    /**
     * Supprime une permission
     */
    public function destroyPermission(Permission $permission)
    {
        // Vérifier si la permission est utilisée par des rôles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('user-management.permissions')
                ->with('error', 'Impossible de supprimer cette permission car elle est attribuée à des rôles.');
        }

        $permission->delete();

        return redirect()->route('user-management.permissions')
            ->with('success', 'Permission supprimée avec succès.');
    }

    /**
     * Affiche le formulaire d'édition du profil de l'utilisateur connecté
     */
    public function editProfile()
    {
        $user = auth()->user();
        $user->load('roles:id,nom,slug');
        $roles = Role::select('id', 'nom', 'slug')->get();
        
        return view('user-management.profile.edit', compact('user', 'roles'));
    }

    /**
     * Met à jour le profil de l'utilisateur connecté
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mise à jour de l'utilisateur
        $userData = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('dashboard')
            ->with('success', 'Profil mis à jour avec succès.');
    }
    
    /**
     * Vérifie si l'utilisateur actuel peut créer un utilisateur avec les rôles spécifiés
     */
    private function canCurrentUserCreateUser($selectedRoleIds)
    {
        $currentUser = auth()->user();
        
        // Vérifier si l'utilisateur actuel est administrateur
        if ($currentUser->hasRole('administrateur') || $currentUser->hasRole('admin')) {
            return true;
        }
        
        // Vérifier si l'utilisateur est RH
        if ($currentUser->hasRole('hr') || $currentUser->hasRole('rh')) {
            // L'utilisateur RH ne peut créer que des agents opérationnels et superviseurs
            $selectedRoles = Role::whereIn('id', $selectedRoleIds)->get();
            foreach ($selectedRoles as $role) {
                if (!in_array(strtolower($role->slug), ['operational', 'agent_operationnel', 'supervisor', 'superviseur'])) {
                    return false;
                }
            }
            return true;
        }
        
        // Les autres rôles ne peuvent pas créer d'utilisateurs
        return false;
    }
}