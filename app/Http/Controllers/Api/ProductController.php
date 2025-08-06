<?php

namespace App\Http\Controllers\Api;

use App\Enums\CategoryEnum;
use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\File;
use App\Models\Log;
use App\Models\Product;
use App\Traits\File\FileManagementTrait;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Bool_;

class ProductController extends Controller
{

    use FileManagementTrait;
    const PRODUCT = 'product';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductResource::collection(Product::query()->orderBy('name')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (($validator = ProductRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $product = Product::create([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'created_at' => $request->input('created_at'),
            'site' => $request->input('site'),
            'description' => $request->input('description'),
            'functionality' => $request->input('functionality'),
            'portfolio' =>  (bool)$request->input('portfolio'),
        ]);

        $files = $this->storeMultipleFiles($request, 'files', static::PRODUCT);

        $index = -1;
        foreach ($files as $file) {
            $index += 1;
            $product->files()->create([
                'name' => static::PRODUCT,
                'path' => $file,
                'isCover' => $index === 0 ? true : false,
            ]);
        }

        Log::create(LogTypeEnum::CREATE, "Ajout du produit '$product->name'");

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::query()->where('id', $id)->first();

            return new ProductResource($product);

        } catch (Exception) {
            return __404("Ce produit n'existe pas");
        }
    }

    public function published(string $id)
    {
        try {
            $product = Product::query()->where('id', $id)->first();

            $product->update([
                'published' => !($product->published)
            ]);

            if ($product->published) {
                Log::create(LogTypeEnum::PUBLISHED, "Publication du produit '$product->name'");
            } else {
                Log::create(LogTypeEnum::PUBLISHED, "Retrait du product '$product->name'");
            }


        } catch (Exception) {
            return __404("Ce produit n'existe pas");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = validator(
            $request->all(),
            [
                "name" => ['required', 'string', 'unique:products,name,' . $id],
                'category_id' => ['required', 'exists:categories,id'],
                'created_at' => ['required', 'string'],
                'site' => ['nullable', 'string'],
                'description' => ['required', 'string'],
                'functionality' => ['required', 'string'],
            ],

            attributes: [
                'name' => "Le nom du produit",
                'category_id' => "La catégorie du produit",
                'created_at' => "L'année de création du produit",
                'site' => "Le site du produit",
                'description' => "La description du produit",
                'functionality' => "La fonctionnalité du produit",
            ]
        );

        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        try {
            $product = Product::query()->where('id', $id)->first();

            $product->update([
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'created_at' => $request->input('created_at'),
                'site' => $request->input('site'),
                'description' => $request->input('description'),
                'functionality' => $request->input('functionality'),
                'portfolio' => $request->input('portfolio') ?? false,
            ]);

            Log::create(LogTypeEnum::UPDATE, "Modification du produit '$product->name'");

            return new ProductResource($product);
        } catch (Exception) {
            return __404("L'id de ce produit n'existe pas");
        }
    }

    public function updateImage(Request $request, string $id)
    {
        $validator = validator(
            $request->all(),
            [
                'file' => ['required', 'image', 'max:5120', 'mimes:jpeg,png,jpg'],
            ],

            attributes: [
                'file' => "Le fichier image",
            ]
        );

        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        try {
            $file = File::query()->where('id', $id)->first();

            $path = $this->updateFile($request, 'file', static::PRODUCT, $file->path);

            $file->update([
                'path' => $path
            ]);

            $productTitle = $file->owner->name;

            Log::create(LogTypeEnum::UPDATE, "Modification d'une image du produit '$productTitle'");

        } catch (Exception) {
            return __404("L'id de ce fichier n'existe pas");
        }
    }

    public function addImage(Request $request, string $id)
    {
        $validator = validator(
            $request->all(),
            [
                'file' => ['required', 'image', 'max:5120', 'mimes:jpeg,png,jpg'],
            ],

            attributes: [
                'file' => "Le fichier image",
            ]
        );

        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        try {
            $product = Product::query()->where('id', $id)->first();

            $path = $this->storeFile($request, 'file', static::PRODUCT);


            $product->files()->create([
                'name' => static::PRODUCT,
                'path' => $path
            ]);

            Log::create(LogTypeEnum::CREATE, "Ajout d'une image au produit '$product->name'");

        } catch (Exception) {
            return __404("L'id de ce produit n'existe pas");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::query()->where('id', $id)->first();

            if ($product->files()->exists()) {
                $files = $product->files;
                foreach ($files as $file) {
                    $this->deleteFile($file->path);
                    $file->delete();
                }
            }

            Log::create(LogTypeEnum::DELETE, "Suppression du produit '$product->name'");

            $product->delete();
        } catch (Exception) {
            return __404("Ce produit n'existe pas");
        }
    }


    public function deleteImage(string $id)
    {

        try {
            $file = File::query()->where('id', $id)->first();
            if ($file->isCover) {
                return __404("Vous ne pouvez pas supprimer l'image de couverture");
            } else {
                $this->deleteFile($file->path);

                $productTitle = $file->owner->name;

                Log::create(LogTypeEnum::DELETE, "Suppression d'une image du produit '$productTitle'");

                $file->delete();
            }
        } catch (Exception) {
            return __404("L'id de ce fichier n'existe pas");
        }
    }
}
