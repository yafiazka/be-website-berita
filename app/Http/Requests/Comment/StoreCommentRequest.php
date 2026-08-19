<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isAuth = auth()->check();

        return [
            'name' => [$isAuth ? 'nullable' : 'required', 'string', 'max:255'],
            'email' => [$isAuth ? 'nullable' : 'required', 'email', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ];
    }
}
