<?php

namespace App\Http\Middleware;

use App\Models\Penulis;
use Closure;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SsoAuthenticate
{
    // Cache hasil validasi token (menit)
    private const CACHE_TTL_MINUTES = 10;

    public function handle(Request $request, Closure $next): Response
    {
        // Bypass preflight OPTIONS request — biarkan CORS middleware yang handle
        if ($request->isMethod('OPTIONS')) {
            return $next($request);
        }

        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.',
            ], 401);
        }

        $cacheKey = 'sso_token_' . hash('sha256', $bearerToken);

        // Cek cache dulu
        $penulis = Cache::get($cacheKey);

        if (!$penulis) {
            // Coba validasi ke SSO server
            $ssoUser = $this->validateTokenFromSso($bearerToken);

            if ($ssoUser) {
                // SSO berhasil → resolve user lokal & cache
                $penulis = $this->resolveLocalUser($ssoUser);
                if ($penulis) {
                    Cache::put($cacheKey, $penulis, now()->addMinutes(self::CACHE_TTL_MINUTES));
                }
            } else {
                // SSO gagal/timeout → fallback 1: decode JWT untuk ambil sso_id
                $penulis = $this->resolveFromJwt($bearerToken);
            }
        }

        if (!$penulis) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token tidak valid atau sudah kadaluarsa. Silakan login ulang.',
            ], 401);
        }

        // Inject user — $request->user() otomatis bekerja di semua controller
        $request->setUserResolver(fn() => $penulis);
        Auth::setUser($penulis);

        return $next($request);
    }

    // Validasi token ke SSO server, return data user atau null jika gagal/timeout
    private function validateTokenFromSso(string $token): ?array
    {
        try {
            $ssoBase  = env('API_SSO_URL', 'https://hub.jtv.co.id');
            $response = Http::withOptions(['connect_timeout' => 5, 'timeout' => 8])
                ->withHeaders(['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json'])
                ->get(rtrim($ssoBase, '/') . '/api/me');

            if ($response->successful()) {
                $body = $response->json();
                return $body['user'] ?? (isset($body['email']) ? $body : null);
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('[SsoAuthenticate] SSO tidak dapat dihubungi, coba fallback JWT: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fallback: decode JWT payload (tanpa verifikasi signature) untuk ambil sub (user id SSO).
     * Dipakai saat SSO server tidak bisa dihubungi.
     */
    private function resolveFromJwt(string $token): ?Penulis
    {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 3) return null;

            $payload = json_decode(base64_decode(str_pad(
                strtr($parts[1], '-_', '+/'),
                strlen($parts[1]) % 4 === 0 ? strlen($parts[1]) : strlen($parts[1]) + 4 - (strlen($parts[1]) % 4),
                '='
            )), true);

            if (!$payload) return null;

            // Cek expiry token
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                Log::info('[SsoAuthenticate] JWT expired, tolak request.');
                return null;
            }

            $ssoId = $payload['sub'] ?? null;
            if (!$ssoId) return null;

            $penulis = Penulis::where('sso_id', $ssoId)->first();

            if ($penulis) {
                Log::info('[SsoAuthenticate] Fallback JWT berhasil untuk sso_id: ' . $ssoId);
            }

            return $penulis;

        } catch (\Exception $e) {
            Log::error('[SsoAuthenticate] Gagal decode JWT: ' . $e->getMessage());
            return null;
        }
    }

    // Cari user lokal (Penulis), auto-create jika belum ada
    private function resolveLocalUser(array $ssoUser): ?Penulis
    {
        $ssoId = $ssoUser['id'] ?? $ssoUser['sso_id'] ?? null;
        $email = $ssoUser['email'] ?? null;

        if (!$ssoId && !$email) return null;

        $penulis = null;
        if ($ssoId) {
            $penulis = Penulis::where('sso_id', $ssoId)->first();
        }
        if (!$penulis && $email) {
            $penulis = Penulis::where('email_penulis', $email)->first();
        }

        if (!$penulis) {
            $Now  = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            $nama = $ssoUser['name'] ?? 'User';

            $penulis = Penulis::create([
                'sso_id'            => $ssoId,
                'nama_penulis'      => $nama,
                'email_penulis'     => $email,
                'telp_penulis'      => $ssoUser['phone'] ?? $ssoUser['phone_number'] ?? '',
                'password'          => '',
                'usernames'         => '',
                'tentang_penulis'   => '',
                'profesi_penulis'   => '',
                'image_penulis'     => '',
                'tipe_penulis'      => '1',
                'tgl_berubah'       => $Now->format('Y-m-d H:i:s'),
                'tgl_loginterakhir' => $Now->format('Y-m-d H:i:s'),
                'seo'               => $this->slugify($nama . '-' . time()),
            ]);
        } elseif ($ssoId && !$penulis->sso_id) {
            $penulis->update(['sso_id' => $ssoId]);
        }

        return $penulis;
    }

    private function slugify(string $string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }
}
