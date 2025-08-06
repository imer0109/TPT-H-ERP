<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\File;
use App\Models\Log;
use App\Traits\File\FileManagementTrait;
use Exception;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    use FileManagementTrait;
    const CLIENT = 'client';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ClientResource::collection(Client::query()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (($validator = ClientRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $client = Client::create([
            'name' => $request->input('name'),
            'comment' => $request->input('comment'),
            'site' => $request->input('site'),
        ]);


        if ($request->hasFile('file')) {
            $path = $this->storeFile($request, 'file', static::CLIENT);

            $client->file()->create([
                'name' => static::CLIENT,
                'path' => $path,
                'isCover' => true
            ]);
        }

        Log::create(LogTypeEnum::CREATE, "Ajout du client '$client->name'");

        return new ClientResource($client);
    }

    public function published(string $id)
    {
        try {
            $client = Client::query()->where('id', $id)->first();

            $client->update([
                'published' => !($client->published)
            ]);

            if ($client->published) {
                Log::create(LogTypeEnum::PUBLISHED, "Publication du client '$client->name'");
            } else {
                Log::create(LogTypeEnum::PUBLISHED, "Retrait du client '$client->name'");
            }
        } catch (Exception) {
            return __404("Ce client n'existe pas");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = Validator(
            $request->all(),
            [
                "name" => ['required', 'string'],
                "comment" => ['required', 'string'],
                "site" => ['nullable', 'string'],
            ],

            attributes: [
                'name' => "Le nom du client",
                'comment' => "Le commentaire",
                'site' => "Le site du client",
            ]
        );


        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        try {
            $client = Client::query()->where('id', $id)->first();

            $client->update([
                'name' => $request->input('name'),
                'comment' => $request->input('comment'),
                'site' => $request->input('site'),
            ]);

            Log::create(LogTypeEnum::UPDATE, "Modification du client '$client->name'");

            return new ClientResource($client);
        } catch (Exception) {
            return __404("L'id de ce client n'existe pas");
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

            $path = $this->updateFile($request, 'file', static::CLIENT, $file->path);

            $file->update([
                'path' => $path
            ]);

            $clientName = $file->owner->name;

            Log::create(LogTypeEnum::UPDATE, "Modification du logo du client '$clientName'");
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
            $client = Client::query()->where('id', $id)->first();

            if ($client->file()->exists()) {
                $file = $client->file;
                $this->deleteFile($file->path);
                $file->delete();
            }

            Log::create(LogTypeEnum::DELETE, "Suppression du client '$client->name'");

            $client->delete();
        } catch (Exception) {
            return __404("Ce client n'existe pas");
        }
    }
}
