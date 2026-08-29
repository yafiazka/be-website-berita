<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,avif,svg|max:5120',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,avif,svg|max:5120',
        ]);

        $file = $request->file('image') ?? $request->file('file');
        if (!$file) {
            return ApiResponse::error('Berkas gambar wajib diunggah.', 422);
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('articles', $filename, 'public');

        $url = asset('storage/' . $path);

        return ApiResponse::success([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'path' => $path,
            'url' => $url,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ], 'Media berhasil diunggah.', 201);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'file_path' => 'required|string',
        ]);

        if (Storage::disk('public')->exists($request->file_path)) {
            Storage::disk('public')->delete($request->file_path);
            return ApiResponse::success(null, 'Media berhasil dihapus.');
        }

        return ApiResponse::error('File tidak ditemukan.', 404);
    }
}
