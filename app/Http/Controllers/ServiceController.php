<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->get();
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $service = Service::create([
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
            ]);

            DB::commit();

            return redirect()->route('services.index')
                ->with('success', 'Service créé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du service.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);
            $service->update([
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
            ]);

            DB::commit();

            return redirect()->route('services.index')
                ->with('success', 'Service mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du service.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);
            $service->delete();

            DB::commit();

            return redirect()->route('services.index')
                ->with('success', 'Service supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression du service.');
        }
    }
}