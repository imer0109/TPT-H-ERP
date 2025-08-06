<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\File;
use App\Models\Log;
use App\Models\Service;
use App\Traits\File\FileManagementTrait;
use Exception;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use FileManagementTrait;
    const SERVICE = 'service';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServiceResource::collection(Service::query()->get());
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (($validator = ServiceRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $service = Service::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'content' => $request->input('content'),
        ]);


        if ($request->hasFile('file')) {
            $path = $this->storeFile($request, 'file', static::SERVICE);

            $service->file()->create([
                'name' => static::SERVICE,
                'path' => $path,
                'isCover' => true
            ]);
        }

        Log::create(LogTypeEnum::CREATE, "Ajout du service '$service->title'");

        return new ServiceResource($service);
    }

    public function published(string $id)
    {
        try {
            $service = Service::query()->where('id', $id)->first();

            $service->update([
                'published' => !($service->published)
            ]);

            if ($service->published) {
                Log::create(LogTypeEnum::PUBLISHED, "Publication du service '$service->title'");
            } else {
                Log::create(LogTypeEnum::PUBLISHED, "Retrait du service '$service->title'");
            }
        } catch (Exception) {
            return __404("Ce service n'existe pas");
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $service = Service::query()->where('id', $id)->first();

            return new ServiceResource($service);
        } catch (Exception) {
            return __404("Ce service n'existe pas");
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
                "description" => ['required', 'string'],
                "content" => ['required', 'string'],
            ],

            attributes: [
                'title' => "Le titre",
                'description' => "La description",
                'content' => "Le contenu",
            ]
        );

        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        try {
            $service = Service::query()->where('id', $id)->first();

            $service->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'content' => $request->input('content'),
            ]);

            Log::create(LogTypeEnum::UPDATE, "Modification du service '$service->title'");

            return new ServiceResource($service);
        } catch (Exception) {
            return __404("L'id de ce service n'existe pas");
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

            $path = $this->updateFile($request, 'file', static::SERVICE, $file->path);

            $file->update([
                'path' => $path
            ]);

            $serviceTitle = $file->owner->title;

            Log::create(LogTypeEnum::UPDATE, "Modification d'une image du service '$serviceTitle'");
        } catch (Exception) {
            return __404("L'id de ce fichier n'existe pas");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $service = Service::query()->where('id', $id)->first();

            if ($service->file()->exists()) {
                $file = $service->file;
                $this->deleteFile($file->path);
                $file->delete();
            }

            Log::create(LogTypeEnum::DELETE, "Suppression du service '$service->title'");

            $service->delete();
        } catch (Exception) {
            return __404("Ce service n'existe pas");
        }
    }
}
