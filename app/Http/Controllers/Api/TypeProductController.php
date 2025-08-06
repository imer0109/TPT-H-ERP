<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\TypeProductRequest;
use App\Http\Resources\TypeProductResource;
use App\Models\Log;
use App\Models\TypeProduct;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TypeProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TypeProductResource::collection(TypeProduct::query()->orderBy('name')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (($validator = TypeProductRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $typeProduct = TypeProduct::create([
            'name' => $request->input('name'),
        ]);

        Log::create(LogTypeEnum::CREATE, "Création du type de produit '$typeProduct->name'");

        return new TypeProductResource($typeProduct);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (($validator = TypeProductRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        try{
            $typeProduct = TypeProduct::query()->where('id', $id)->first();


            $typeProduct->update([
                'name' => $request->input('name'),
            ]);

            Log::create(LogTypeEnum::UPDATE, "Modification du type de produit '$typeProduct->name'");

            return new TypeProductResource($typeProduct);
        }catch(Exception){
            return __404("L'id de ce type n'existe pas");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $typeProduct = TypeProduct::query()->where('id', $id)->first();

            if($typeProduct->categories()->exists()) {
                return __403('Ce type ne peut pas être supprimé car il possède encore des catégories associées.');
            }

            Log::create(LogTypeEnum::DELETE, "Suppression du type de produit '$typeProduct->name'");

            $typeProduct->delete();
        } catch (Exception) {
            return __404("Ce type n'existe pas");
        }
    }
}
