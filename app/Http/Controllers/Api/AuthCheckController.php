<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailPenulisResource;
use App\Http\Resources\OpiniAuthorResource;
use App\Http\Resources\OpiniResource;
use App\Models\Opini;
use App\Models\Penulis;
use App\Models\TbOpini;
use App\Services\Query\Tulisan\Tulisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthCheckController extends Controller
{
    // 1. LOGIN BIASA (Forward to SSO)
    public function index(Request $request)
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Auto-generate payload for SSO
        $ssoBase = env('API_SSO_URL', 'https://hub.jtv.co.id');
        $loginUrl = rtrim($ssoBase, '/') . '/api/login';

        $payload = [
            'email' => $email,
            'password' => $password,
            'device_uuid' => (string) Str::uuid(),
            'device_name' => $request->header('User-Agent', 'JTV Portal Web'),
            'platform' => 'Web',
            'app_id' => env('APP_ID', 'portal-jtv-123'),
        ];

        try {
            $response = Http::withOptions([
                'connect_timeout' => 5,
                'timeout' => 10,
            ])->post($loginUrl, $payload);

            if ($response->successful() && isset($response['access_token'])) {
                $ssoData   = $response->json();
                $ssoUser   = $ssoData['user'] ?? null;

                if (!$ssoUser || (!isset($ssoUser['email']) && !isset($ssoUser['id']))) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid SSO response data'], 500);
                }

                $ssoId = $ssoUser['id'] ?? null;
                $emailSso = $ssoUser['email'] ?? null;

                $penulis = null;
                if ($ssoId) {
                    $penulis = Penulis::where('sso_id', $ssoId)->first();
                }
                if (!$penulis && $emailSso) {
                    $penulis = Penulis::where('email_penulis', $emailSso)->first();
                }

                if (!$penulis) {
                    $nama  = $ssoUser['name'] ?? 'User';
                    $phone = $ssoUser['phone'] ?? $ssoUser['phone_number'] ?? $ssoUser['no_telp'] ?? '';

                    $penulis = Penulis::create([
                        'sso_id'           => $ssoId,
                        'nama_penulis'     => $nama,
                        'email_penulis'    => $emailSso,
                        'telp_penulis'     => $phone,
                        'password'         => '',
                        'usernames'        => '',
                        'tentang_penulis'  => '',
                        'profesi_penulis'  => '',
                        'image_penulis'    => '',
                        'tipe_penulis'     => '1',
                        'tgl_berubah'      => $Now->format('Y-m-d H:i:s'),
                        'tgl_loginterakhir'=> $Now->format('Y-m-d H:i:s'),
                        'seo'              => $this->slugify($nama . '-' . time()),
                    ]);
                }

                $penulis->tokens()->delete();
                $sanctumToken = $penulis->createToken('auth_token')->plainTextToken;

                $penulis->update([
                    'sso_id' => $ssoId,
                    'tgl_loginterakhir'=> $Now->format('Y-m-d H:i:s'),
                ]);

            return response()->json([
                'status'        => 'success',
                'message'       => 'Anda berhasil masuk akun',
                'access_token'  => $ssoData['access_token'],
                'refresh_token' => $ssoData['refresh_token'] ?? null,
                'token_type'    => $ssoData['token_type'] ?? 'Bearer',
                'expires_in'    => $ssoData['expires_in'] ?? null,
                'token'         => $sanctumToken,
                'data'          => \App\Http\Resources\DetailPenulisResource::make($penulis),
            ]);
        } else {
            $errorMsg = $response->json('message') ?? 'Email atau Password tidak sesuai!';
            return response()->json(['status' => 'error', 'message' => $errorMsg], 401);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Layanan SSO sedang gangguan.'], 500);
    }
}

    // 2. LOGIN / REGISTER via FIREBASE
    public function firebase(Request $request)
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

        $nama = $request->nama;
        $email = $request->email;
        $phone = $request->nohp;
        $photo = $request->photo;
        $uid = $request->uid;

        $penulis = Penulis::where('email_penulis', $email)->first();

        if ($penulis) {
            // LOGIN EXISTING USER
            $penulis->update(['uid' => $uid]);
            $penulis->tokens()->delete();
            $token = $penulis->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'  => "success",
                'message' => "Login Berhasil via Google",
                'token'   => $token,
                'data'    => $penulis
            ]);
        } else {
            // REGISTER NEW USER
            $newdata = [
                'uid' => $uid,
                'nama_penulis' => $nama,
                'email_penulis' => $email,
                'telp_penulis' => $phone,
                'password' => '',
                'usernames' => '',
                'tentang_penulis' => '',
                'profesi_penulis' => '',
                'tipe_penulis' => '1',
                'tgl_berubah' => $Now->format('Y-m-d H:i:s'),
                'tgl_loginterakhir' => $Now->format('Y-m-d H:i:s'),
                'seo' => $this->slugify($nama . '-' . time()),
            ];

            // Handle Foto
            if ($photo != '') {
                try {
                    $contents = file_get_contents($photo);
                    if ($contents !== false) {
                        $fullname = "google_" . time() . ".jpg";
                        Storage::disk('jtv')->put("foto-profil/$fullname", $contents);
                        $newdata['image_penulis'] = $fullname;
                    }
                } catch (\Exception $e) {
                    $newdata['image_penulis'] = '';
                }
            } else {
                $newdata['image_penulis'] = '';
            }

            $create = Penulis::create($newdata);

            if ($create) {
                $token = $create->createToken('auth_token')->plainTextToken;
                $datapenulis = DetailPenulisResource::make($create);

                return response()->json([
                    "status" => "success",
                    "message" => "Pendaftaran akun berhasil via Google",
                    "token" => $token,
                    "data" => $datapenulis,
                ]);
            } else {
                return response()->json(["status" => "error", "message" => "Gagal mendaftarkan akun"], 500);
            }
        }
    }

    // 3. SIGNUP MANUAL
    public function signup(Request $request)
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

        $nama = $request->nama;
        $email = $request->email;
        $phone = $request->phone;
        $password = md5($request->password);

        $cek_query = Penulis::where('email_penulis', $email)->first();

        if ($cek_query) {
            return response()->json(['status' => "error", 'message' => "Email Sudah Terdaftar!"], 400);
        }

        $newdata = [
            'nama_penulis' => $nama,
            'email_penulis' => $email,
            'telp_penulis' => $phone,
            'password' => $password,
            'usernames' => '',
            'tentang_penulis' => '',
            'profesi_penulis' => '',
            'image_penulis' => '',
            'tipe_penulis' => '1',
            'tgl_berubah' => $Now->format('Y-m-d H:i:s'),
            'tgl_loginterakhir' => $Now->format('Y-m-d H:i:s'),
            'seo' => $this->slugify($nama . '-' . time()),
        ];

        $create = Penulis::create($newdata);

        if ($create) {
            $token = $create->createToken('auth_token')->plainTextToken;
            $datapenulis = DetailPenulisResource::make($create);

            return response()->json([
                "status" => "success",
                "message" => "Pendaftaran berhasil!",
                "token" => $token,
                "data" => $datapenulis,
            ]);
        } else {
            return response()->json(["status" => "error", "message" => "Gagal Mendaftar"], 500);
        }
    }

    // 4. LOGOUT
    public function logout(Request $request)
    {
        // Beritahu SSO bahwa session di sana juga diakhiri
        try {
            $ssoBase     = env('API_SSO_URL', 'https://hub.jtv.co.id');
            $bearerToken = $request->bearerToken();
            if ($bearerToken) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . $bearerToken,
                    'Accept'        => 'application/json',
                ])->post(rtrim($ssoBase, '/') . '/api/logout');
            }
        } catch (\Exception $e) {
            \Log::warning('Logout SSO error (portal): ' . $e->getMessage());
        }

        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['status' => 'success', 'message' => 'Logout Berhasil.']);
    }

    // 4b. REFRESH TOKEN (Forward ke SSO)
    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        if (!$refreshToken) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Refresh token diperlukan',
            ], 400);
        }

        try {
            $ssoBase = env('API_SSO_URL', 'https://hub.jtv.co.id');
            $response = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(rtrim($ssoBase, '/') . '/api/refresh-token', [
                'refresh_token' => $refreshToken,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $response->json('message') ?? 'Refresh token gagal diperbarui',
                ], $response->status());
            }

            $data = $response->json();

            return response()->json([
                'status'        => 'success',
                'access_token'  => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'token_type'    => $data['token_type'] ?? 'Bearer',
                'expires_in'    => $data['expires_in'] ?? null,
            ]);

        } catch (\Exception $e) {
            \Log::error('Refresh token error (portal): ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Refresh token gagal diperbarui',
            ], 500);
        }
    }

    // 5. PROFILE
    public function akunProfil(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return response()->json([
                'nama'  => $user->nama_penulis,
                'photo' => $user->imagePenulis(),
                'email' => $user->email_penulis,
                'phone' => $user->telp_penulis,
                'seo'   => $user->seo,
            ]);
        }
        return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 404);
    }

    // 6. HELPER KIRIM EMAIL
    function send($recipient, $subjectMessage, $bodyMessage)
    {
        $apiURL = 'https://api.brevo.com/v3/smtp/email';
        $sender = [
            'name' => 'Portal JTV',
            'email' => 'no-reply@portaljtv.com'
        ];
        $headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'api-key' => env('BREVO_API_KEY'),
        ];
        $postInput = [
            'sender' => $sender,
            'to' => $recipient,
            'subject' => $subjectMessage,
            'htmlContent' => $bodyMessage
        ];
        $response = Http::withHeaders($headers)->post($apiURL, $postInput);
        return json_decode($response->getBody(), true);
    }

    // 7. RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $emailTo = $request->input('email');
        $linkRedirect = config('jp.path_url_fe');
        $datapenulis = Penulis::where('email_penulis', $emailTo)->first();

        if ($datapenulis) {
            $tokenencrypt = md5($datapenulis->id_penulis);
            $namaTo = $datapenulis->nama_penulis;
            $linkRedirected = $linkRedirect . "/reset-password/" . $tokenencrypt;

            $recipient[] = ['name' => $namaTo, 'email' => $emailTo];
            $subject = "Konfirmasi Reset Kata Sandi";
            $body = "<html><head></head><body><p>Halo $namaTo,<br></p>Klik link dibawah ini untuk reset password.<br> <a href='$linkRedirected' title='Verify Now'>Reset Password</a></p><br><p>Informasi selengkapnya hubungi PortalJTV.com</p></body></html>";

            $kirimEmail = $this->send($recipient, $subject, $body);
            if ($kirimEmail) {
                return response()->json(['status' => 'success', 'message' => 'Link reset password telah dikirim ke ' . $emailTo]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Gagal mengirim email provider.']);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Akun email ' . $emailTo . ' tidak ditemukan!']);
    }

    // 8. SUBMIT TULISAN
    public function tulisanSubmit(Request $request)
    {
        $data_penulis = $request->user();

        if (!$data_penulis) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $judul = $request->input('judul');
        $seo_kategori = $request->input('tipe');
        $keyword = $request->input('keywords');
        $foto = $request->input('foto');
        $isi_tag = $keyword ? implode('# ', $keyword) : null;

        $param_insert = [
            'id_penulis_opini' => $data_penulis->id_penulis,
            'judul_opini' => $judul,
            'seo_opini' => $this->slugify($judul),
            'artikel_opini' => $request->input('isi'),
            'tipe_opini' => $seo_kategori,
            'date_input_opini' => $request->input('tanggal'),
            'tag_opini' => $isi_tag,
            'status_opini' => 'Draft',
            'updated_at' => now(),
        ];

        // Upload Foto
        if ($foto != '') {
            try {
                if (strpos($foto, 'base64,') !== false) {
                    $foto = explode('base64,', $foto)[1];
                }
                $contents = base64_decode($foto);
                if ($contents) {
                    $filename = 'opini_' . time() . '.jpg';
                    Storage::disk('jtv')->put("upload-gambar/$filename", $contents);
                    $param_insert['gambar_opini'] = $filename;
                }
            } catch (\Exception $e) {
                $param_insert['gambar_opini'] = '';
            }
        } else {
            $param_insert['gambar_opini'] = '';
        }

        $create = TbOpini::create($param_insert);

        return $create
            ? response()->json(['status' => 'success', 'message' => 'Submit berhasil!'])
            : response()->json(['status' => 'error', 'message' => 'Submit Gagal!']);
    }

    // 9. UPDATE TULISAN
    public function tulisanUpdate(Request $request, TbOpini $opini)
    {
        $profile = $request->user();

        if (!$profile) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        if ($opini->id_penulis_opini != $profile->id_penulis) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden: Bukan tulisan anda'], 403);
        }

        $judul = $request->input('judul');
        $keyword = $request->input('keywords');
        $foto = $request->input('foto');
        $isi_tag = $keyword ? implode('# ', $keyword) : null;

        $param_update = [
            'judul_opini' => $judul,
            'seo_opini' => $this->slugify($judul),
            'artikel_opini' => $request->input('isi'),
            'tipe_opini' => $request->input('tipe'),
            'date_input_opini' => $request->input('tanggal'),
            'tag_opini' => $isi_tag,
            'status_opini' => 'Draft',
            'updated_at' => now(),
        ];

        if ($foto != '') {
            try {
                if (strpos($foto, 'base64,') !== false) {
                    $foto = explode('base64,', $foto)[1];
                }
                $contents = base64_decode($foto);
                if ($contents) {
                    $filename = 'opini_' . time() . '.jpg';
                    Storage::disk('jtv')->put("upload-gambar/$filename", $contents);
                    $param_update['gambar_opini'] = $filename;
                }
            } catch (\Exception $e) {
                // Ignore error image
            }
        }

        $updated = $opini->update($param_update);

        return $updated
            ? response()->json(['status' => 'success', 'message' => 'Update berhasil!', 'updated' => $updated])
            : response()->json(['status' => 'error', 'message' => 'Update Gagal!']);
    }

    // 10. DELETE TULISAN
    public function tulisanDestroy(Request $request, TbOpini $opini)
    {
        $profile = $request->user();

        if (!$profile) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        if ($opini->id_penulis_opini != $profile->id_penulis) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        $deleted = $opini->delete();

        return $deleted
            ? response()->json(['status' => 'success', 'message' => 'Delete Successfully!'])
            : response()->json(['status' => 'error', 'message' => 'Gagal delete!']);
    }

    // 11. LIST TULISAN
    public function tulisanList(Request $request, Tulisan $tulisan)
    {
        $profile = $request->user();

        if (!$profile) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $limit = request('limit') ?? config('jp.api_paginate');

        $daftarTulisan = OpiniResource::collection(
            $tulisan->getOpiniAuthor($profile->id_penulis, $limit)
        )->additional([
            'section' => [
                'title' => $profile->nama_penulis,
                'link' => config('jp.path_url_be') . "api/tulisan/list",
            ]
        ]);

        return $daftarTulisan;
    }

    // 12. UPDATE PROFILE PENULIS
    public function penulisUpdateProfile(Request $request)
    {
        $penulis = $request->user();

        if (!$penulis) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $nama = $request->nama;
        $email = $request->email;
        $phone = $request->phone;
        $password = $request->password;

        $param_update = [
            'nama_penulis' => $nama,
            'email_penulis' => $email,
            'telp_penulis' => $phone,
            'tgl_berubah' => now(),
        ];

        // --- HANDLE FOTO PROFIL (BASE64) ---
        if ($request->foto) {
            try {
                $imageData = $request->foto;
                
                // Pisahkan header base64 (data:image/png;base64,...)
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $extension = strtolower($type[1]); // png, jpg, jpeg
                    
                    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        throw new \Exception('Format gambar tidak didukung');
                    }

                    $imageData = base64_decode($imageData);
                    if ($imageData === false) {
                        throw new \Exception('Gagal decode base64');
                    }
                } else {
                    throw new \Exception('Format base64 tidak valid');
                }

                // Nama file unik
                $fileName = Str::slug($nama) . '-' . time() . '.' . $extension;
                $path = "foto-profil/" . $fileName;

                // Simpan ke Storage menggunakan disk 'jtv' (public/assets)
                Storage::disk('jtv')->put($path, $imageData);

                // Hapus foto lama jika ada
                if ($penulis->image_penulis && Storage::disk('jtv')->exists("foto-profil/" . $penulis->image_penulis)) {
                    Storage::disk('jtv')->delete("foto-profil/" . $penulis->image_penulis);
                }

                $param_update['image_penulis'] = $fileName;

            } catch (\Exception $e) {
                Log::error('[ProfileUpdate] Gagal upload foto: ' . $e->getMessage());
                // Lanjut update data lain, tapi foto dilewati
            }
        }

        if ($password != '' && $password != null) {
            $newpassword = md5($password);
            $param_update['password'] = $newpassword;
        }

        $update = $penulis->update($param_update);

        if ($update) {
            return response()->json(['status' => 'success', 'message' => 'Update profile berhasil!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Update profile Gagal!']);
        }
    }

    // HELPER: SLUGIFY
    private function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }
}
