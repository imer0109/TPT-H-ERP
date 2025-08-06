<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartenerLogoRequest;
use App\Http\Resources\PartenerLogoResource;
use App\Models\File;
use App\Models\Log;
use App\Models\PartenerLogo;
use App\Traits\File\FileManagementTrait;
use Exception;
use Illuminate\Http\Request;

class PartenerLogoController extends Controller
{

    use FileManagementTrait;
    const LOGO = 'logo';

    public function index()
    {
        return PartenerLogoResource::collection(PartenerLogo::query()->orderBy('name')->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (($validator = PartenerLogoRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $partener = PartenerLogo::create([
            'name' => $request->input('name'),
        ]);


        if ($request->hasFile('file')) {
            $path = $this->storeFile($request, 'file', static::LOGO);

            $partener->file()->create([
                'name' => static::LOGO,
                'path' => $path,
                'isCover' => true
            ]);
        }

        Log::create(LogTypeEnum::CREATE, "Ajout du logo du partenaire '$partener->name'");

        return new PartenerLogoResource($partener);
    }


    public function published(string $id)
    {
        try {
            $partener = PartenerLogo::query()->where('id', $id)->first();

            $partener->update([
                'published' => !($partener->published)
            ]);

            if ($partener->published) {
                Log::create(LogTypeEnum::PUBLISHED, "Publication du logo du partenaire '$partener->name'");
            } else {
                Log::create(LogTypeEnum::PUBLISHED, "Retrait du logo du partenaire '$partener->name'");
            }
        } catch (Exception) {
            return __404("Ce logo n'existe pas");
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

            $path = $this->updateFile($request, 'file', static::LOGO, $file->path);

            $file->update([
                'path' => $path
            ]);

            $partenerTitle = $file->owner->name;

            Log::create(LogTypeEnum::UPDATE, "Modification du logo du partenaire '$partenerTitle'");
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
            $partener = PartenerLogo::query()->where('id', $id)->first();

            if ($partener->file()->exists()) {
                $file = $partener->file;
                $this->deleteFile($file->path);
                $file->delete();
            }

            Log::create(LogTypeEnum::DELETE, "Suppression du partenaire '$partener->name'");

            $partener->delete();
        } catch (Exception) {
            return __404("Ce partenaire n'existe pas");
        }
    }
}
