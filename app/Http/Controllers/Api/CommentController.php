<?php

namespace App\Http\Controllers\Api;

use App\Enums\LogTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Log;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CommentResource::collection(Comment::query()->orderBy('date', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (($validator = CommentRequest::validate())->fails()) {
            return __422($validator->errors()->first());
        }

        $comment = Comment::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'comment' => $request->input('comment'),
            'blog_id' => $request->input('blog_id'),
            'date' => now(),
        ]);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        // try {
        //     $blog = Blog::query()->findOrFail($id);

        //     $comments = Comment::query()->where('blog_id', $id)->get();

        //     return CommentResource::Collection($comments);
        // } catch (ModelNotFoundException $e) {
        //     __422("L'id du blog est invalide");
        // } catch (\Exception $e) {
        //     return __422("L'id du blog est invalide");
        // }
    }

    public function published(string $id)
    {
        $comment = Comment::query()->where('id', $id)->first();

        $comment->update([
            'published' => !($comment->published)
        ]);

        if ($comment->published) {
            Log::create(LogTypeEnum::PUBLISHED, "Publication du commentaire de l'utilisateur '$comment->name'");
        } else {
            Log::create(LogTypeEnum::PUBLISHED, "Retrait du commentaire de l'utilisateur '$comment->name'");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $comment = Comment::query()->where('id', $id)->first();

            Log::create(LogTypeEnum::DELETE, "Suppression du commentaire de l'utilisateur '$comment->name'");

            $comment->delete();
        } catch (Exception) {
            return __404("Ce commentaire n'existe pas");
        }
    }
}
