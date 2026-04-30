<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedNewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $berita = $this->whenLoaded('berita');

        if (!$berita) {
            return [
                "id_saved" => $this->id,
                "id_user" => $this->id_user,
                "id_berita" => $this->id_berita,
                "created_at" => $this->created_at,
                "updated_at" => $this->updated_at,
            ];
        }

        // Gunakan BeritaResource untuk mendapatkan format standar
        $beritaResource = new BeritaResource($berita);
        $beritaData = $beritaResource->toArray($request);

        // Gabungkan metadata simpanan dengan data berita
        return array_merge([
            'id_saved' => $this->id,
            'id_berita' => $this->id_berita,
            'saved_at' => $this->created_at,
        ], $beritaData);
    }
}
