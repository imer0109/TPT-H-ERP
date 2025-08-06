<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsletterResource;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{

    public function index(){
        return NewsletterResource::collection(Newsletter::query()->get());
    }

    // Ajouter un abonné
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:newsletters,email'],
        ],

        attributes: [
            'email' => "L'email"
        ]
    
    );

        if ($validator->fails()) {
            return __422($validator->errors()->first());
        }

        Newsletter::create(['email' => $request->email]);

        return __200("Vous êtes bien abonné à la newsletter !");
    }


    // Désabonner un utilisateur
    public function unsubscribe($email)
    {
        $subscriber = Newsletter::query()->where('email', $email)->first();

        if (!$subscriber) {
            return __404("Adresse email non trouvée");
        }

        $subscriber->delete();

        return __200("Désabonnement réussi !");
    }
}
