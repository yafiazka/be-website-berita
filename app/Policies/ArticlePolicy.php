<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    /**
     * Super Administrator & Admin bypass semua validasi otorisasi.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasAnyRole(['Admin', 'Super Administrator', 'super-admin'])) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Super Administrator', 'super-admin', 'Penulis', 'Jurnalis']);
    }

    public function view(User $user, Article $article): bool
    {
        return (int) $user->id === (int) $article->author_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Editor', 'Super Administrator', 'super-admin', 'Penulis', 'Jurnalis']);
    }

    public function update(User $user, Article $article): bool
    {
        return (int) $user->id === (int) $article->author_id;
    }

    public function delete(User $user, Article $article): bool
    {
        return (int) $user->id === (int) $article->author_id;
    }

    public function publish(User $user, Article $article): bool
    {
        return (int) $user->id === (int) $article->author_id;
    }
}
