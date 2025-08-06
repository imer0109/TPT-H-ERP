<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Log;
use App\Models\TypeProduct;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(Category::query()->orderBy('name')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (($validator = CategoryRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $category = Category::create([
            'name' => $request->input('name'),
            'type_product_id' => $request->input('type_product_id')
        ]);

        Log::create(LogTypeEnum::CREATE, "Création de la catégorie '$category->name'");

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = validator(
            $request->all(),
            [
                'name' => ['required', 'string', 'unique:' . Category::class . ',name,' . $id],
                'type_product_id' => ['required', 'exists:' . TypeProduct::class . ',id'],
            ],

            attributes: [
                'name' => "Le nom",
                'type_product_id' => "Le Type de produit",
            ]
        );

        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        try {
            $category = Category::query()->where('id', $id)->first();

            $category->update([
                'name' => $request->input('name'),
                'type_product_id' => $request->input('type_product_id')
            ]);

            Log::create(LogTypeEnum::UPDATE, "Modification de la catégorie '$category->name'");

            return new CategoryResource($category);
        } catch (Exception) {
            return __404("L'id de cette catégorie n'existe pas");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::query()->where('id', $id)->first();

            Log::create(LogTypeEnum::DELETE, "Suppression de la catégorie '$category->name'");

            $category->delete();
        } catch (Exception) {
            return __404("Ce type n'existe pas");
        }
    }
}
