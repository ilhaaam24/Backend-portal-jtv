@extends('layouts.materialize')

@section('content')
{{-- MENYAMAKAN PADDING: Kiri-kanan 30px --}}
<div class="layout-px-spacing" style="padding-left: 30px; padding-right: 30px;">
    
    <div class="row layout-top-spacing">
        {{-- Menggunakan col-lg-8 agar lebar form konsisten dengan halaman create --}}
        <div class="col-lg-8 col-12 layout-spacing">
            
            <div class="statbox widget box box-shadow">
                <div class="widget-header">                                
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            {{-- Judul dengan styling yang sama --}}
                            <h4 style="font-weight: bold; color: #0F2D52; padding: 20px 20px 0 20px;">Edit Komentar</h4>
                        </div>
                    </div>
                </div>

                {{-- Content area dengan padding 20px --}}
                <div class="widget-content widget-content-area" style="padding: 20px;">
                    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- 1. PILIH PENULIS --}}
                        <div class="form-group mb-4">
                            <label style="color: #3b3f5c; font-weight: 600;">Penulis</label>
                            <select class="form-control basic" name="user_id" required style="border-color: #e0e6ed; color: #3b3f5c;">
                                @foreach($penulis as $p)
                                    <option value="{{ $p->id_penulis }}" {{ $comment->user_id == $p->id_penulis ? 'selected' : '' }}>
                                        {{ $p->nama_penulis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. PILIH BERITA --}}
                        <div class="form-group mb-4">
                            <label style="color: #3b3f5c; font-weight: 600;">Lokasi Komentar</label>
                            <select class="form-control basic" name="id_berita" style="border-color: #e0e6ed; color: #3b3f5c;">
                                <option value="" {{ $comment->id_berita == null ? 'selected' : '' }}>-- Komentar Umum --</option>
                                @foreach($berita as $b)
                                    <option value="{{ $b->id_berita }}" {{ $comment->id_berita == $b->id_berita ? 'selected' : '' }}>
                                        {{ Str::limit($b->judul_berita, 80) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. ISI KOMENTAR --}}
                        <div class="form-group mb-4">
                            <label style="color: #3b3f5c; font-weight: 600;">Isi Komentar</label>
                            <textarea class="form-control" name="content" rows="6" required style="border-color: #e0e6ed; color: #3b3f5c;">{{ $comment->content }}</textarea>
                        </div>

                        {{-- TOMBOL AKSI (Style disamakan dengan Create) --}}
                        <div style="margin-top: 30px;">
                            <button type="submit" class="btn" style="background-color: #F36D21; color: white; border: none; padding: 10px 25px;">
                                Update Komentar
                            </button>
                            
                            <a href="{{ route('admin.comments.index') }}" class="btn" style="background-color: #3b3f5c; color: white; border: none; padding: 10px 25px; margin-left: 10px;">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection