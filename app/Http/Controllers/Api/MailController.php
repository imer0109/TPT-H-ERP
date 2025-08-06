<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendNewsletter(Request $request)
    {
        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email',
            'content' => 'required',
        ]);

        $emails = $request->input('emails');
        $htmlContent = $request->input('content');

        // Mail::to($email)->send(new NewsletterMail($htmlContent));
        foreach ($emails as $email) {
            Mail::send([], [], function ($message) use ($email, $htmlContent) {
                $message->to($email)
                    ->subject("Votre Newsletter")
                    ->html($htmlContent); // Utilisation de HTML brut
            });
        }



        return __200("Newsletter envoyée avec succès !");
    }
}
