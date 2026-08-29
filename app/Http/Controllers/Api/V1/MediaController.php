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
        $rawImage = $request->file('image')
            ?? $request->file('file')
            ?? $request->input('image')
            ?? $request->input('file');

        if (!$rawImage) {
            return ApiResponse::error('Berkas gambar atau data base64 wajib dikirimkan.', 422);
        }

        $path = \App\Services\ImageService::processAndStore($rawImage, 'articles');
        if (!$path) {
            return ApiResponse::error('Gagal memproses dan mengoptimasi berkas gambar.', 400);
        }

        $url = str_starts_with($path, 'http') ? $path : asset('storage/' . $path);

        return ApiResponse::success([
            'file_name' => basename($path),
            'file_path' => $path,
            'path' => $path,
            'url' => $url,
        ], 'Media berhasil diunggah dan dioptimasi.', 201);
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
