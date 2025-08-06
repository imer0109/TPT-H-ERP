<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\File;
use App\Models\Log;
use App\Traits\File\FileManagementTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BlogController extends Controller
{

    use FileManagementTrait;
    const BLOG = 'blog';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BlogResource::collection(Blog::query()->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (($validator = BlogRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $blog = Blog::create([
            'type' => $request->input('type'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'author' => $request->input('author'),
            'content' => $request->input('content'),
        ]);

        $files = $this->storeMultipleFiles($request, 'files', static::BLOG);

        $index = -1;
        foreach ($files as $file) {
            $index += 1;
            $blog->files()->create([
                'name' => static::BLOG,
                'path' => $file,
                'isCover' => $index === 0 ? true : false,
            ]);
        }

        Log::create(LogTypeEnum::CREATE, "Création du blog '$blog->title'");

        return new BlogResource($blog);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $blog = Blog::query()->where('id', $id)->first();

            return new BlogResource($blog);
        } catch (Exception) {
            return __404("Ce blog n'existe pas");
        }
    }

    public function published(string $id)
    {
            $blog = Blog::query()->where('id', $id)->first();

            $blog->update([
                'published' => !($blog->published)
            ]);

            if($blog->published){
                Log::create(LogTypeEnum::PUBLISHED, "Publication du blog '$blog->title'");
            }else{
                Log::create(LogTypeEnum::PUBLISHED, "Retrait du blog '$blog->title'");
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
                "title" => ['required', 'string'],
                'type' => ['required', 'string'],
                "author" => ['required', 'string'],
                "description" => ['required', 'string'],
                "content" => ['required', 'string'],
            ],

            attributes: [
                'type' => "Le domaine",
                'title' => "Le titre",
                'description' => "La description",
                'author' => "L'auteur de la publication",
                'content' => "Le contenu",
            ]
        );

        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        try {
            $blog = Blog::query()->where('id', $id)->first();

            $blog->update([
                'type' => $request->input('type'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'author' => $request->input('author'),
                'content' => $request->input('content'),
            ]);

            Log::create(LogTypeEnum::UPDATE, "Modification du blog '$blog->title'");

            return new BlogResource($blog);
        } catch (Exception) {
            return __404("L'id de ce blog n'existe pas");
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

            $path = $this->updateFile($request, 'file', static::BLOG, $file->path);

            $file->update([
                'path' => $path
            ]);

            $blogTitle = $file->owner->title;

            Log::create(LogTypeEnum::UPDATE, "Modification d'une image du blog '$blogTitle'");

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
            $blog = Blog::query()->where('id', $id)->first();

            $path = $this->storeFile($request, 'file', static::BLOG);

            $blog->files()->create([
                'name' => static::BLOG,
                'path' => $path
            ]);

            Log::create(LogTypeEnum::CREATE, "Ajout d'une image au blog '$blog->title'");
            
        } catch (Exception) {
            return __404("L'id de ce blog n'existe pas");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $blog = Blog::query()->where('id', $id)->first();

            if ($blog->comments()->exists()) {
                return __403('Ce blog ne peut pas être supprimé car il possède des commentaires associés.');
            }

            if ($blog->files()->exists()) {
                $files = $blog->files;
                foreach ($files as $file) {
                    $this->deleteFile($file->path);
                    $file->delete();
                }
            }

            Log::create(LogTypeEnum::DELETE, "Suppression du blog '$blog->title'");

            $blog->delete();
        } catch (Exception) {
            return __404("Ce blog n'existe pas");
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

                $blogTitle = $file->owner->title;

                Log::create(LogTypeEnum::DELETE, "Suppression d'une image du blog '$blogTitle'");

                $file->delete();

            }
        } catch (Exception) {
            return __404("L'id de ce fichier n'existe pas");
        }
    }
}
