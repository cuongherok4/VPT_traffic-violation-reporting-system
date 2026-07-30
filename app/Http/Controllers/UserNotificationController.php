<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, UserNotification $notification): JsonResponse
    {
        abort_if($notification->user_id !== $request->user()->id, 403);

        $notification->update(['read_at' => now()]);

        return response()->json($notification->fresh());
    }
}
