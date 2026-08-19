<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\Newsletter\SubscribeRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(SubscribeRequest $request): JsonResponse
    {
        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $request->email],
            ['token' => Str::random(32), 'is_verified' => true]
        );

        if ($subscriber->unsubscribed_at !== null) {
            $subscriber->update(['unsubscribed_at' => null]);
        }

        return ApiResponse::success(null, 'Terima kasih telah berlangganan buletin berita kami!');
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        $subscriber = NewsletterSubscriber::where('token', $request->token)->first();
        if (!$subscriber) {
            return ApiResponse::error('Token pembatalan langganan tidak valid.', 404);
        }

        $subscriber->update(['unsubscribed_at' => now()]);

        return ApiResponse::success(null, 'Anda telah berhasil berhenti berlangganan.');
    }
}
