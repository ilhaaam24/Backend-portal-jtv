@extends('layouts.materialize')

@section('content')
{{-- PERBAIKAN: Tambah padding kiri-kanan 30px agar konsisten dengan halaman index --}}
<div class="layout-px-spacing" style="padding-left: 30px; padding-right: 30px;">
    
    <div class="row layout-top-spacing">
        {{-- Menggunakan col-lg-8 agar form tidak terlalu lebar (lebih fokus) --}}
        <div class="col-lg-8 col-12 layout-spacing">
            
            <div class="statbox widget box box-shadow">
                <div class="widget-header">                                
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            {{-- Judul Form --}}
                            <h4 style="font-weight: bold; color: #0F2D52; padding: 20px 20px 0 20px;">Tambah Komentar Baru</h4>
                        </div>
                    </div>
                </div>

                <div class="widget-content widget-content-area" style="padding: 20px;">
                    <form action="{{ route('admin.comments.store') }}" method="POST">
                        @csrf
                        
                        {{-- 1. PILIH PENULIS --}}
                        <div class="form-group mb-4">
                            <label style="color: #3b3f5c; font-weight: 600;">Pilih Penulis (User)</label>
                            <select class="form-control basic" name="user_id" required style="border-color: #e0e6ed; color: #3b3f5c;">
                                <option value="">-- Pilih Siapa Yang Komen --</option>
                                @foreach($penulis as $p)
                                    <option value="{{ $p->id_penulis }}">{{ $p->nama_penulis }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. PILIH BERITA --}}
                        <div class="form-group mb-4">
                            <label style="color: #3b3f5c; font-weight: 600;">Lokasi Komentar (Berita)</label>
                            <select class="form-control basic" name="id_berita" style="border-color: #e0e6ed; color: #3b3f5c;">
                                <option value="">-- Komentar Umum (Halaman Komentar Anda) --</option>
                                @foreach($berita as $b)
                                    <option value="{{ $b->id_berita }}">{{ Str::limit($b->judul_berita, 80) }} ({{ $b->date_publish_berita }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Biarkan opsi ini default jika ini adalah komentar umum.</small>
                        </div>

                        {{-- 3. ISI KOMENTAR --}}
                        <div class="form-group mb-4">
                            <label style="color: #3b3f5c; font-weight: 600;">Isi Komentar</label>
                            <textarea class="form-control" name="content" rows="6" required style="border-color: #e0e6ed; color: #3b3f5c;" placeholder="Tulis komentar disini..."></textarea>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div style="margin-top: 30px;">
                            <button type="submit" class="btn" style="background-color: #F36D21; color: white; border: none; padding: 10px 25px;">
                                Simpan Komentar
                            </button>
                            
                            <a href="{{ route('admin.comments.index') }}" class="btn" style="background-color: #3b3f5c; color: white; border: none; padding: 10px 25px; margin-left: 10px;">
                                Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection