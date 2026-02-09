<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    /**
     * Display the documents index page
     */
    public function index()
    {
        // Fetch recent documents
        $recentDocuments = Document::orderBy('created_at', 'desc')->limit(10)->get();
        
        // Show documents index page with data
        return view('hr.documents.index', compact('recentDocuments'));
    }

    /**
     * Display the work certificate generation form
     */
    public function workCertificate(Employee $employee)
    {
        return view('hr.documents.work-certificate', compact('employee'));
    }

    /**
     * Generate and download the work certificate
     */
    public function generateWorkCertificate(Request $request, Employee $employee)
    {
        // Validate the request
        $validated = $request->validate([
            'reason' => 'required|string',
            'additional_info' => 'nullable|string',
            'end_date' => 'nullable|date|after:employee.date_embauche',
        ]);
        
        // Generate PDF certificate
        $data = [
            'employee' => $employee,
            'reason' => $validated['reason'],
            'additional_info' => $validated['additional_info'],
            'end_date' => $validated['end_date'] ?? null,
            'generated_date' => now(),
        ];
        
        $pdf = Pdf::loadView('hr.documents.pdf.work-certificate', $data);
        $filename = 'certificat_travail_' . str_slug($employee->full_name) . '_' . now()->format('Y-m-d') . '.pdf';
        
        // Save to documents table
        $document = Document::create([
            'nom' => 'Certificat de Travail - ' . $employee->full_name,
            'type_document' => 'certificat_travail',
            'chemin_fichier' => 'documents/' . $filename,
            'taille' => 0, // Will be updated after saving
            'format' => 'pdf',
            'description' => 'Certificat de travail pour ' . $employee->full_name,
            'user_id' => Auth::id(),
            'documentable_id' => $employee->id,
            'documentable_type' => Employee::class,
        ]);
        
        return $pdf->download($filename);
    }

    /**
     * Display the salary certificate generation form
     */
    public function salaryCertificate(Employee $employee)
    {
        return view('hr.documents.salary-certificate', compact('employee'));
    }

    /**
     * Generate and download the salary certificate
     */
    public function generateSalaryCertificate(Request $request, Employee $employee)
    {
        // Validate the request
        $validated = $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'gross_salary' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'reason' => 'required|string',
            'additional_info' => 'nullable|string',
        ]);
        
        // Generate PDF certificate
        $data = [
            'employee' => $employee,
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'gross_salary' => $validated['gross_salary'],
            'net_salary' => $validated['net_salary'],
            'reason' => $validated['reason'],
            'additional_info' => $validated['additional_info'],
            'generated_date' => now(),
        ];
        
        $pdf = Pdf::loadView('hr.documents.pdf.salary-certificate', $data);
        $filename = 'certificat_salaire_' . str_slug($employee->full_name) . '_' . now()->format('Y-m-d') . '.pdf';
        
        // Save to documents table
        $document = Document::create([
            'nom' => 'Certificat de Salaire - ' . $employee->full_name,
            'type_document' => 'certificat_salaire',
            'chemin_fichier' => 'documents/' . $filename,
            'taille' => 0, // Will be updated after saving
            'format' => 'pdf',
            'description' => 'Certificat de salaire pour ' . $employee->full_name,
            'user_id' => Auth::id(),
            'documentable_id' => $employee->id,
            'documentable_type' => Employee::class,
        ]);
        
        return $pdf->download($filename);
    }

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
                'certificat_travail',
                'certificat_salaire',
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