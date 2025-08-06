<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\Log;
use App\Models\User;
use App\Traits\File\FileManagementTrait;
use Dotenv\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{

    use FileManagementTrait;
    const USER = 'user';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::query()->orderBy('lastName')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        if (($validator = UserRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $user = User::create([
            'lastName' => $request->input('lastName'),
            'firstName' => $request->input('firstName'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => $request->input('firstName') . "1234",
            'role' => $request->input('role') ?? RoleEnum::USER,
        ]);

        $token = $user->createToken("user/$user->id")->plainTextToken;

        $user->update([
            "token" => $token,
        ]);


        // if ($request->hasFile('file')) {
        //     $path = $this->storeFile($request, 'file', static::USER);

        //     $user->file()->create([
        //         'name' => static::USER,
        //         'path' => $path
        //     ]);
        // }

        Log::create(LogTypeEnum::CREATE, "Création de l'utilisateur '$user->lastName $user->firstName'");

        return new UserResource($user);
    }


    public function login(Request $request)
    {
        $validator = Validator(
            $request->all(),
            [
                "email" => ['required', 'email'],
                "password" => ['required', 'string'],
            ],

            attributes: [
                'email' => "L'email",
                'password' => "Le mot de passe",
            ]
        );

        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }


        $credentials = $request->only(['email', 'password']);

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $user->tokens()->delete();
            $token = $user->createToken("user/$user->id")->plainTextToken;

            $user->update([
                "token" => $token,
            ]);

            Log::create(LogTypeEnum::CONNECTED, "Connexion de l'utilisateur '$user->lastName $user->firstName'");

            return new UserResource($user);
        } else {
            return __422("Ce compte n'existe pas dans nos ressources.");
        }
    }

    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();

        Log::create(LogTypeEnum::DISCONNECTED, "Déconnexion de l'utilisateur '$user->lastName $user->firstName'");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = validator(
            $request->all(),
            [
                "lastName" => ['required', 'string'],
                "firstName" => ['required', 'string'],
                "email" => ['required', 'email', 'unique:users,email,' . $id],
                "phone" => ['nullable', 'string'],
                "password" => ['nullable', 'string'],
                "role" => ['nullable', new Enum(RoleEnum::class)],
            ],

            attributes: [
                'lastName' => "Le nom",
                'firstName' => "Le prénom",
                'email' => "L'email",
                'password' => "Le mot de passe",
                'phone' => "Le numéro de téléphone",
                'role' => "Le statut",
            ]
        );


        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        try {
            $user = User::query()->where('id', $id)->first();

            $user->update([
                'lastName' => $request->input('lastName'),
                'firstName' => $request->input('firstName'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'role' => $request->input('role') ?? RoleEnum::USER,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => $request->input('password')
                ]);
            }

            Log::create(LogTypeEnum::UPDATE, "Modification des informations de l'utilisateur '$user->lastName $user->firstName'");

            return new UserResource($user);
        } catch (Exception) {
            return __404("L'id de cet utilisateur n'existe pas");
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

        $user = auth()->user();

        if ($user->file()->exists()) {
            try {
                $file = File::query()->where('id', $id)->first();

                $path = $this->updateFile($request, 'file', static::USER, $file->path);

                $file->update([
                    'path' => $path
                ]);

                Log::create(LogTypeEnum::UPDATE, "Modification du profil de l'utilisateur '$user->lastName $user->firstName'");

            } catch (Exception) {
                return __404("L'id de ce fichier n'existe pas");
            }
        } else {
            try {
                $user = User::query()->where('id', $id)->first();

                $path = $this->storeFile($request, 'file', static::USER);

                $user->file()->create([
                    'name' => static::USER,
                    'path' => $path,
                ]);

                Log::create(LogTypeEnum::UPDATE, "Ajout du profil de l'utilisateur '$user->lastName $user->firstName'");

            } catch (Exception) {
                return __404("L'id de ce utilisateur n'existe pas");
            }
        }
    }

    public function deleteImage(string $id)
    {

        try {
            $file = File::query()->where('id', $id)->first();
            
            $this->deleteFile($file->path);

            $user = $file->owner;

            Log::create(LogTypeEnum::DELETE, "Suppression du profil de l'utilisateur '$user->lastName $user->firstName'");

            $file->delete();
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
            $user = User::query()->where('id', $id)->first();

            if ($user->file()->exists()) {
                $file = $user->file;
                $this->deleteFile($file->path);
                $file->delete();
            }

            Log::create(LogTypeEnum::DELETE, "Suppression de l'utilisateur '$user->lastName $user->firstName'");

            $user->delete();
        } catch (Exception) {
            return __404("Cet utilisateur n'existe pas");
        }
    }

    public function currentUser()
    {
        $user = auth()->user();

        return new UserResource($user);
    }
}
