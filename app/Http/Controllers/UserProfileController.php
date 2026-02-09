<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    /**
     * Affiche la liste des utilisateurs
     */
    public function index(Request $request)
    {
        // Vérifier les permissions
        // Temporairement désactivé pour permettre l'accès
        /*if (!auth()->user()->hasRole('administrateur') && !auth()->user()->hasRole('admin') && (!auth()->user()->canAccessModule('users') && !auth()->user()->hasPermission('users.utilisateurs.view') && !auth()->user()->hasRole('manager'))) {
            abort(403, 'Accès non autorisé');
        }*/
        
        $query = User::with(['roles', 'company']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        $users = $query->orderBy('nom')->paginate(15);
        $companies = Company::orderBy('raison_sociale')->get();

        return view('user-profiles.index', compact('users', 'companies'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur
     */
    public function create()
    {
        $companies = Company::orderBy('raison_sociale')->get();
        $roles = Role::orderBy('nom')->get();

        return view('user-profiles.create', compact('companies', 'roles'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'statut' => 'nullable|string|in:actif,inactif,suspendu',
            'telephone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Valeur par défaut pour le statut
        if (!isset($validated['statut'])) {
            $validated['statut'] = 'actif';
        }

        // Hash du mot de passe
        $validated['password'] = Hash::make($validated['password']);

        // Création de l'utilisateur
        $user = User::create($validated);

        // Traitement de la photo de profil
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::slug($user->prenom . '-' . $user->nom) . '_profil_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('users/profile_photos', $filename, 'public');
            $user->update(['photo' => $path]);
        }

        // Attribution des rôles
        if ($request->has('roles')) {
            $user->roles()->attach($request->roles);
        }

        return redirect()->route('user-profiles.show', $user)
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['roles', 'company', 'documents']);
        
        return view('user-profiles.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur
     */
    public function edit(User $user)
    {
        $user->load('roles');
        $companies = Company::orderBy('raison_sociale')->get();
        $roles = Role::orderBy('nom')->get();

        return view('user-profiles.edit', compact('user', 'companies', 'roles'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        // Validation des données
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'statut' => 'required|string|in:actif,inactif,suspendu',
            'telephone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Mise à jour du mot de passe si fourni
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Mise à jour de l'utilisateur
        $user->update($validated);

        // Traitement de la photo de profil
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si existante
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $file = $request->file('photo');
            $filename = Str::slug($user->prenom . '-' . $user->nom) . '_profil_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('users/profile_photos', $filename, 'public');
            $user->update(['photo' => $path]);
        }

        // Mise à jour des rôles
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('user-profiles.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        // Supprimer les documents associés
        foreach ($user->documents as $document) {
            if ($document->chemin_fichier) {
                Storage::disk('public')->delete($document->chemin_fichier);
            }
            $document->delete();
        }

        // Supprimer la photo de profil si existante
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('user-profiles.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Exporte les utilisateurs en CSV
     */
    public function export()
    {
        $users = User::with(['roles', 'company'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="utilisateurs.csv"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénom',
                'Email',
                'Téléphone',
                'Statut',
                'Entreprise'
            ]);
            
            // Données
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->nom,
                    $user->prenom,
                    $user->email,
                    $user->telephone,
                    $user->statut,
                    $user->company ? $user->company->raison_sociale : ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Affiche les utilisateurs actifs
     */
    public function actifs()
    {
        $users = User::with(['roles', 'company'])
            ->where('statut', 'actif')
            ->orderBy('nom')
            ->paginate(15);

        $companies = Company::orderBy('raison_sociale')->get();

        return view('user-profiles.index', compact('users', 'companies'))
            ->with('statut_filter', 'actif');
    }

    /**
     * Affiche les utilisateurs inactifs
     */
    public function inactifs()
    {
        $users = User::with(['roles', 'company'])
            ->where('statut', 'inactif')
            ->orderBy('nom')
            ->paginate(15);

        $companies = Company::orderBy('raison_sociale')->get();

        return view('user-profiles.index', compact('users', 'companies'))
            ->with('statut_filter', 'inactif');
    }
}