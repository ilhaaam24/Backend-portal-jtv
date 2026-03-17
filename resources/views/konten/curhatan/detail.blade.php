@extends('layouts.materialize')

@section('title', 'Detail Curhatan')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        
        {{-- Breadcrumb --}}
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Konten / Curhat Warga /</span> Detail
        </h4>

        {{-- TOMBOL KEMBALI (Yang disempurnakan) --}}
        <div class="mb-4">
            <a href="{{ route('konten.curhatan') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            
            {{-- KOLOM KIRI: Detail Pesan --}}
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md me-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    {{ substr($curhatan->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0 text-nowrap">{{ $curhatan->name }}</h5>
                                <small class="text-muted">{{ $curhatan->email }}</small>
                            </div>
                        </div>
                        
                        {{-- Status Badge --}}
                        <div>
                            @if($curhatan->is_replied)
                                <span class="badge bg-label-success">✅ Sudah Dibalas</span>
                            @else
                                <span class="badge bg-label-danger">⏳ Belum Dibalas</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <small class="text-muted text-uppercase">Isi Pesan:</small>
                        <div class="p-3 bg-lighter rounded mt-2 mb-4 border">
                            <p class="mb-0" style="white-space: pre-line; color: #566a7f;">
                                {{ $curhatan->message }}
                            </p>
                        </div>
                        <div class="d-flex align-items-center text-muted small mb-4">
                            <i class="mdi mdi-clock-outline me-1"></i> 
                            Diterima pada: {{ $curhatan->created_at->format('d F Y, H:i') }} WIB
                        </div>

                        {{-- Lampiran Foto --}}
                        @if($curhatan->image_path)
                            <hr class="my-4">
                            <h6 class="mb-3"><i class="mdi mdi-image-outline me-1"></i> Lampiran Foto</h6>
                            <div class="border rounded p-2 d-inline-block bg-light">
                                <a href="{{ asset('storage/' . $curhatan->image_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $curhatan->image_path) }}" 
                                         alt="Lampiran" 
                                         class="img-fluid rounded" 
                                         style="max-height: 300px; object-fit: contain;">
                                </a>
                            </div>
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $curhatan->image_path) }}" target="_blank" class="btn btn-xs btn-label-primary">
                                    <i class="mdi mdi-fullscreen me-1"></i> Perbesar
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Form Balas --}}
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header border-bottom mb-3">
                        <h5 class="card-title mb-0">Balas via Email</h5>
                    </div>
                    
                    <div class="card-body">
                        {{-- Notifikasi --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Info jika sudah dibalas --}}
                        @if($curhatan->is_replied)
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="mdi mdi-information-outline me-2"></i>
                                <div class="small">Pesan ini sudah pernah dibalas sebelumnya.</div>
                            </div>
                        @endif

                        <form action="{{ route('curhatan.reply') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $curhatan->id }}">

                            <div class="mb-3">
                                <label class="form-label text-muted small text-uppercase">Kepada</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="mdi mdi-email-outline"></i></span>
                                    <input type="text" class="form-control" value="{{ $curhatan->email }}" disabled>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small text-uppercase">Pesan Balasan <span class="text-danger">*</span></label>
                                <textarea name="reply_message" class="form-control" rows="6" placeholder="Tulis tanggapan Anda di sini..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-send me-2"></i> Kirim Balasan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection