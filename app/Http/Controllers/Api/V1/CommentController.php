<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getArticleComments(string $slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $comments = Comment::where('article_id', $article->id)
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        return ApiResponse::success(CommentResource::collection($comments), 'Komentar artikel.');
    }

    public function store(StoreCommentRequest $request, string $slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $data = $request->validated();
        $data['article_id'] = $article->id;

        if ($request->user()) {
            $data['user_id'] = $request->user()->id;
            $data['name'] = $request->user()->name;
            $data['email'] = $request->user()->email;
            $data['status'] = 'approved'; // trusted logged in user
        } else {
            $data['status'] = 'pending'; // guest requires moderation
        }

        $comment = Comment::create($data);

        return ApiResponse::success(new CommentResource($comment->load('user')), 'Komentar berhasil dikirim.', 201);
    }

    // Dashboard Moderation
    public function dashboardIndex(Request $request): JsonResponse
    {
        $query = Comment::with(['article', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $comments = $query->paginate((int)$request->input('per_page', 20));

        return ApiResponse::paginated(CommentResource::collection($comments));
    }

    public function approve(int $id): JsonResponse
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return ApiResponse::error('Komentar tidak ditemukan.', 404);
        }

        $comment->update(['status' => 'approved']);

        return ApiResponse::success(new CommentResource($comment), 'Komentar telah disetujui.');
    }

    public function markSpam(int $id): JsonResponse
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return ApiResponse::error('Komentar tidak ditemukan.', 404);
        }

        $comment->update(['status' => 'spam']);

        return ApiResponse::success(new CommentResource($comment), 'Komentar ditandai sebagai spam.');
    }

    public function destroy(int $id): JsonResponse
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return ApiResponse::error('Komentar tidak ditemukan.', 404);
        }

        $comment->delete();

        return ApiResponse::success(null, 'Komentar berhasil dihapus.');
    }
}
