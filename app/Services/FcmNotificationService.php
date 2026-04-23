<?php

namespace App\Services;

use App\Models\FcmToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmNotificationService
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Kirim notifikasi ke SEMUA device yang terdaftar
     */
    public function sendToAll(string $title, string $body, array $data = [], ?string $imageUrl = null): void
    {
        $tokens = FcmToken::pluck('token')->toArray();

        if (empty($tokens)) {
            Log::info('[FCM] Tidak ada device token terdaftar.');
            return;
        }

        $notification = Notification::create($title, $body, $imageUrl);
        
        // Tambahkan image ke data payload agar bisa dihandle custom oleh aplikasi (misal: BigPicture style)
        if ($imageUrl && !isset($data['image'])) {
            $data['image'] = $imageUrl;
        }

        // FCM batch limit = 500 tokens per request
        $chunks = array_chunk($tokens, 500);

        foreach ($chunks as $chunk) {
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            try {
                $report = $this->messaging->sendMulticast($message, $chunk);

                Log::info('[FCM] Sent: ' . $report->successes()->count() .
                    ', Failed: ' . $report->failures()->count());

                // Hapus token yang invalid (unregistered / expired)
                if ($report->hasFailures()) {
                    $this->removeInvalidTokens($report, $chunk);
                }
            } catch (\Exception $e) {
                Log::error('[FCM] Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Kirim notifikasi ke user tertentu
     */
    public function sendToUser(int $idPenulis, string $title, string $body, array $data = [], ?string $imageUrl = null): void
    {
        $tokens = FcmToken::where('id_penulis', $idPenulis)->pluck('token')->toArray();

        if (empty($tokens)) {
            return;
        }

        $notification = Notification::create($title, $body, $imageUrl);
        
        // Tambahkan image ke data payload agar bisa dihandle custom oleh aplikasi
        if ($imageUrl && !isset($data['image'])) {
            $data['image'] = $imageUrl;
        }

        $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

        try {
            $report = $this->messaging->sendMulticast($message, $tokens);

            if ($report->hasFailures()) {
                $this->removeInvalidTokens($report, $tokens);
            }
        } catch (\Exception $e) {
            Log::error('[FCM] Error sendToUser: ' . $e->getMessage());
        }
    }

    /**
     * Hapus token yang sudah tidak valid dari database
     */
    private function removeInvalidTokens($report, array $tokens): void
    {
        foreach ($report->failures()->getItems() as $failure) {
            $target = $failure->target();
            $error = $failure->error();

            // Token sudah tidak valid — hapus dari DB
            if ($error && in_array($error->value, ['UNREGISTERED', 'INVALID_ARGUMENT'])) {
                $tokenValue = $target->value();
                FcmToken::where('token', $tokenValue)->delete();
                Log::info('[FCM] Removed invalid token: ' . substr($tokenValue, 0, 20) . '...');
            }
        }
    }
}
