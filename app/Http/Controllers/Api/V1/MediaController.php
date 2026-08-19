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
            'file' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120', // 5MB max
        ]);

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/media', $filename, 'public');

        $url = asset('storage/' . $path);

        return ApiResponse::success([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
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
