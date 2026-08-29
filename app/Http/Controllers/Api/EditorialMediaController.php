<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EditorialMediaController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $rawImage = $request->file('image')
            ?? $request->file('file')
            ?? $request->input('image')
            ?? $request->input('file');

        if (!$rawImage) {
            return response()->json([
                'success' => false,
                'message' => 'Berkas gambar atau base64 wajib dikirimkan.',
            ], 422);
        }

        $path = ImageService::processAndStore($rawImage, 'articles');

        if (!$path) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses dan mengoptimasi berkas gambar.',
            ], 400);
        }

        $url = str_starts_with($path, 'http') ? $path : asset('storage/' . $path);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diunggah dan dioptimasi.',
            'data'    => [
                'url'       => $url,
                'path'      => $path,
                'file_name' => basename($path),
            ],
        ], 201);
    }
}
