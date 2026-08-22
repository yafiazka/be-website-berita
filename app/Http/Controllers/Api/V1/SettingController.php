<?php

/**
 * Tujuan: Controller API V1 untuk menyediakan konfigurasi & pengaturan website ke frontend
 * Caller: Route GET /api/v1/settings
 * Dependensi: App\Helpers\ApiResponse, Illuminate\Support\Facades\File
 * Main Functions: index()
 * Side Effects: File Read setting-api.json
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Mengambil seluruh data pengaturan website untuk Frontend
     */
    public function index(): JsonResponse
    {
        $settingFilePath = base_path('setting-api.json');

        if (!File::exists($settingFilePath)) {
            return ApiResponse::error('File pengaturan website tidak ditemukan.', 404);
        }

        $jsonContent = File::get($settingFilePath);
        $settings = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ApiResponse::error('Format konfigurasi pengaturan tidak valid.', 500);
        }

        $data = $settings['data'] ?? $settings;

        return ApiResponse::success($data, 'Pengaturan website berhasil dimuat.');
    }

    /**
     * Memperbarui pengaturan website via REST API (Dashboard Redaksi/Admin)
     */
    public function update(Request $request): JsonResponse
    {
        $settingFilePath = base_path('setting-api.json');

        $currentSettings = [];
        if (File::exists($settingFilePath)) {
            $json = json_decode(File::get($settingFilePath), true);
            $currentSettings = $json['data'] ?? $json ?? [];
        }

        // Merge existing settings with new payload
        $newSettings = array_replace_recursive($currentSettings, $request->all());

        $payload = [
            'success' => true,
            'message' => 'Pengaturan website berhasil dimuat.',
            'data' => $newSettings,
        ];

        File::put($settingFilePath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return ApiResponse::success($newSettings, 'Pengaturan website berhasil diperbarui.');
    }
}

