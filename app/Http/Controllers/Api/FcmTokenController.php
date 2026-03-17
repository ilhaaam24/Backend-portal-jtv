<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    /**
     * Register / Update FCM Token
     *
     * POST /api/fcm/register
     * Body: { "token": "fcm_token_string", "device_type": "android" }
     * Auth: Optional (bisa guest atau logged-in)
     */
    public function register(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'device_type' => 'sometimes|string|in:android,ios',
        ]);

        $token = $request->input('token');
        $deviceType = $request->input('device_type', 'android');

        // Cek apakah user login (via Sanctum)
        $idPenulis = null;
        if ($request->bearerToken()) {
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                $idPenulis = $user->id_penulis;
            }
        }

        // Upsert: update jika token sudah ada, create jika belum
        FcmToken::updateOrCreate(
            ['token' => $token],
            [
                'id_penulis' => $idPenulis,
                'device_type' => $deviceType,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token registered.',
        ]);
    }

    /**
     * Unregister FCM Token (saat logout / uninstall)
     *
     * POST /api/fcm/unregister
     * Body: { "token": "fcm_token_string" }
     */
    public function unregister(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        FcmToken::where('token', $request->input('token'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token removed.',
        ]);
    }
}
