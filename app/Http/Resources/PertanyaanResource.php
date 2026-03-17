<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DetailPenulisResource;

class PertanyaanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_pertanyaan,
            'id_pertanyaan' => $this->id_pertanyaan,
            'dari' => new DetailPenulisResource($this->dari_user),
            'kepada' => new DetailPenulisResource($this->kepada_user),

            // Data Lain
            'pertanyaan' => $this->pertanyaan,
            'jawaban' => $this->jawaban,
            'tanggal_pertanyaan' => $this->tanggal_pertanyaan,
            'tanggal_jawaban' => $this->tanggal_jawaban,
        ];
    }
}
