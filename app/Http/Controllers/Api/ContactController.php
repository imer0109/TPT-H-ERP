<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\Log;
use Exception;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContactResource::collection(Contact::query()->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (($validator = ContactRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $contact = Contact::create([
            'type' => $request->input('type'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'question' => $request->input('question'),
        ]);



        return new ContactResource($contact);
    }

    public function read(string $id)
    {

        try {
            $contact = Contact::query()->where('id', $id)->first();

            if (!$contact->isRead) {
                $contact->update([
                    'isRead' => true
                ]);
            }

            Log::create(LogTypeEnum::READ, "Lecture de la demande du contact '$contact->name'");
        } catch (Exception) {
            return __404("Ce contact n'existe pas");
        }
    }


    public function destroy(string $id)
    {
        try {
            $contact = Contact::query()->where('id', $id)->first();

            Log::create(LogTypeEnum::DELETE, "Suppression du contact de '$contact->name'");

            $contact->delete();
        } catch (Exception) {
            return __404("Ce contact n'existe pas");
        }
    }
}
