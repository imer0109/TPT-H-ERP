<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Télécharger un document
     */
    public function download(Document $document)
    {
        // Vérifier si l'utilisateur a le droit d'accéder à ce document
        // Logique d'autorisation à implémenter selon les besoins
        
        return Storage::disk('public')->download($document->chemin_fichier, $document->nom);
    }

    /**
     * Afficher un document (pour les images)
     */
    public function show(Document $document)
    {
        // Vérifier si l'utilisateur a le droit d'accéder à ce document
        // Logique d'autorisation à implémenter selon les besoins
        
        $path = Storage::disk('public')->path($document->chemin_fichier);
        $contentType = mime_content_type($path);
        
        return response()->file($path, ['Content-Type' => $contentType]);
    }

    /**
     * Supprimer un document
     */
    public function destroy(Document $document)
    {
        // Vérifier si l'utilisateur a le droit de supprimer ce document
        // Logique d'autorisation à implémenter selon les besoins
        
        try {
            Storage::disk('public')->delete($document->chemin_fichier);
            $document->delete();
            
            return back()->with('success', 'Document supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression du document: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour les informations d'un document
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type_document' => ['required', Rule::in([
                'contrat',
                'bon_commande',
                'fiche_ouverture',
                'rccm',
                'niu',
                'piece_identite',
                'autre'
            ])],
            'description' => 'nullable|string',
        ]);

        try {
            $document->update($validated);
            
            return back()->with('success', 'Document mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du document: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger plusieurs documents sous forme d'archive ZIP
     */
    public function downloadMultiple(Request $request)
    {
        $validated = $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:documents,id',
        ]);

        $documents = Document::whereIn('id', $validated['document_ids'])->get();
        
        // Créer une archive ZIP temporaire
        $zipFileName = 'documents_' . time() . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);
        
        $zip = new \ZipArchive();
        
        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
            foreach ($documents as $document) {
                $filePath = Storage::disk('public')->path($document->chemin_fichier);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $document->nom);
                }
            }
            $zip->close();
            
            return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
        } else {
            return back()->with('error', 'Impossible de créer l\'archive ZIP.');
        }
    }
}