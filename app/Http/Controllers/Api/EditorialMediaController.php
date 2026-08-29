<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorialMediaController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif,svg,gif|max:5120',
            'file'  => 'nullable|image|mimes:jpeg,png,jpg,webp,avif,svg,gif|max:5120',
        ]);

        $file = $request->file('image') ?? $request->file('file');

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'Berkas gambar wajib diunggah.',
            ], 422);
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('articles', $filename, 'public');
        $url = asset('storage/' . $path);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diunggah.',
            'data'    => [
                'url'       => $url,
                'path'      => $path,
                'file_name' => $file->getClientOriginalName(),
                'size'      => $file->getSize(),
            ],
        ], 201);
    }
}
