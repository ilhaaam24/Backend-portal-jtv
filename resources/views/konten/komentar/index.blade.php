@extends('layouts.materialize')

@section('content')
<div class="layout-px-spacing" style="padding-left: 30px; padding-right: 30px;">

    {{-- BAGIAN 1: STATISTIK DASHBOARD --}}
    <div class="row layout-top-spacing">
        {{-- KOTAK 1: Total Komentar --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one_hybrid widget-followers" style="background: #0F2D52; color: white; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="widget-heading">
                    <div class="w-icon" style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <p class="w-value" style="font-size: 28px; font-weight: bold; margin-bottom: 0;">{{ $totalKomentar }}</p>
                    <h6 class="" style="color: rgba(255,255,255,0.9); margin-top: 5px; font-size: 13px;">Total Komentar</h6>
                </div>
            </div>
        </div>

        {{-- KOTAK 2: Komentar Minggu Ini --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one_hybrid widget-referral" style="background: #F36D21; color: white; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="widget-heading">
                    <div class="w-icon" style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <p class="w-value" style="font-size: 28px; font-weight: bold; margin-bottom: 0;">{{ $komentarMingguIni }}</p>
                    <h6 class="" style="color: rgba(255,255,255,0.9); margin-top: 5px; font-size: 13px;">Komen Minggu Ini</h6>
                </div>
            </div>
        </div>

        {{-- KOTAK 3: Penulis Aktif --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one_hybrid widget-engagement" style="background: #1abc9c; color: white; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div class="widget-heading">
                    <div class="w-icon" style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    </div>
                    <p class="w-value" style="font-size: 28px; font-weight: bold; margin-bottom: 0;">{{ $penulisLoginMingguIni }}</p>
                    <h6 class="" style="color: rgba(255,255,255,0.9); margin-top: 5px; font-size: 13px;">Penulis Aktif Minggu Ini</h6>
                </div>
            </div>
        </div>

        {{-- KOTAK 4: KOMENTAR NEGATIF (REALTIME) --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <a href="{{ route('admin.comments.index', ['filter' => 'toxic']) }}" style="text-decoration: none;" title="Klik untuk filter komentar negatif">
                <div class="widget widget-one_hybrid widget-engagement" style="background: #e30110ff; color: white; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(231, 81, 90, 0.4); transition: transform 0.2s;">
                    <div class="widget-heading">
                        <div class="w-icon" style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        </div>
                        {{-- ID toxic-counter untuk update via JS --}}
                        <p id="toxic-counter" class="w-value" style="font-size: 28px; font-weight: bold; margin-bottom: 0; color: white;">
                            {{ $totalPenulisToxic ?? 0 }}
                        </p>
                        <h6 class="" style="color: rgba(255,255,255,0.9); margin-top: 5px; font-size: 13px;">
                            {{-- LABEL DIPERBARUI --}}
                            Komentar Negatif Terdeteksi
                            <span style="font-size: 11px; display: block; opacity: 0.8;">(Klik untuk lihat detail)</span>
                        </h6>
                    </div>
                </div>
            </a>
        </div>

    </div>

    {{-- BAGIAN 2: TABEL & SEARCH --}}
    <div class="row layout-spacing">
        <div class="col-lg-12">
            <div class="statbox widget box box-shadow">

                {{-- HEADER TABEL --}}
                <div class="widget-header">
                    <div class="row align-items-center" style="padding: 20px;">
                        <div class="col-xl-6 col-md-6 col-sm-12 col-12">
                            <h4 style="font-weight: bold; color: #0F2D52; margin: 0; display: flex; align-items: center;">
                                @if(request('filter') == 'toxic')
                                    <span style="color: #e7515a;">Daftar Komentar Negatif</span>

                                    {{-- TOMBOL RESET FILTER --}}
                                    <a href="{{ route('admin.comments.index') }}" class="btn btn-sm btn-outline-primary" style="font-size: 10px; margin-left: 15px;">Reset Filter</a>

                                    {{-- TOMBOL HAPUS SEMUA NEGATIF --}}
                                    @if($totalPenulisToxic > 0 || (isset($comments) && $comments->total() > 0))
                                    <form action="{{ route('admin.comments.destroyAllToxic') }}" method="POST" onsubmit="return confirm('PERINGATAN BAHAYA: \n\nAnda akan menghapus SEMUA komentar yang terdeteksi negatif/kasar sekaligus.\n\nData yang dihapus TIDAK BISA dikembalikan. \n\nApakah Anda yakin?');" style="display:inline-block; margin-left: 10px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="this.form.submit()" class="btn btn-sm" style="background-color: #e7515a; color: white; border: none; font-size: 10px; padding: 5px 12px; display: inline-flex; align-items: center; box-shadow: 0 2px 4px rgba(231, 81, 90, 0.4);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2" style="margin-right: 4px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                            Hapus Semua
                                        </button>
                                    </form>
                                    @endif
                                @else
                                    Daftar Komentar Terbaru
                                @endif
                            </h4>
                        </div>

                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 text-right">
                            <div class="d-flex justify-content-end align-items-center">

                                <a href="{{ route('admin.comments.create') }}" class="btn btn-sm" style="background: #F36D21; color: white; box-shadow: 0 4px 6px rgba(243, 109, 33, 0.3); border: none; padding: 10px 15px; display: inline-flex; align-items: center; text-decoration: none; white-space: nowrap; margin-right: 20px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus" style="margin-right: 5px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    <span>Tambah Komentar</span>
                                </a>

                                {{-- FORM SEARCH --}}
                                <form id="searchForm" action="{{ route('admin.comments.index') }}" method="GET" style="margin: 0; width: 100%; max-width: 300px;">
                                    <div class="input-group">
                                        {{-- Jaga filter toxic saat searching --}}
                                        @if(request('filter') == 'toxic')
                                            <input type="hidden" name="filter" value="toxic">
                                        @endif

                                        <input type="text" id="searchInput" name="q" class="form-control" placeholder="Cari isi / penulis..." value="{{ request('q') }}" style="height: 40px; border-color: #e0e6ed;" autocomplete="off">
                                        <div class="input-group-append">
                                            <button class="btn" type="submit" style="height: 40px; background-color: #0F2D52; color: white; border: none;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-content widget-content-area">

                    @if(session('success'))
                        <div class="alert alert-success mb-4" role="alert" style="background-color: #e6fffa; border-color: #b2f5ea; color: #234e52;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #234e52;"><span aria-hidden="true">&times;</span></button>
                            <strong>Berhasil!</strong> {{ session('success') }}
                        </div>
                    @endif

                    {{-- WRAPPER UNTUK AJAX UPDATE --}}
                    <div id="data-wrapper">
                        <div class="table-responsive">
                            <table class="table table-hover" style="width:100%">
                                <thead>
                                    <tr style="background-color: #f1f2f3;">
                                        <th width="5%" style="color: #0F2D52; font-weight: 700;">No</th>
                                        <th width="20%" style="color: #0F2D52; font-weight: 700;">Penulis</th>
                                        <th width="35%" style="color: #0F2D52; font-weight: 700;">Isi Komentar</th>
                                        <th width="20%" style="color: #0F2D52; font-weight: 700;">Lokasi</th>
                                        <th width="15%" style="color: #0F2D52; font-weight: 700;">Waktu</th>
                                        <th width="5%" class="text-center" style="color: #0F2D52; font-weight: 700;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($comments as $key => $comment)
                                    <tr>
                                        <td>{{ $comments->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-3" style="width: 35px; height: 35px; background: #0F2D52; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                    {{ substr($comment->user->nama_penulis ?? 'A', 0, 1) }}
                                                </div>
                                                <div style="margin-left: 10px;">
                                                    <h6 class="mb-0 font-weight-bold" style="font-size: 13px; color: #0F2D52;">
                                                        {!! request('q') ? str_ireplace(request('q'), '<span style="background:#F36D21; color:white; padding:0 3px;">'.request('q').'</span>', $comment->user->nama_penulis ?? 'Anonim') : ($comment->user->nama_penulis ?? 'Anonim') !!}
                                                    </h6>
                                                    <small class="text-muted" style="font-size: 11px;">{{ $comment->user->email_penulis ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                // Cek apakah mengandung bintang (*)
                                                $isToxic = strpos($comment->content, '*') !== false;
                                                $displayContent = Str::limit($comment->content, 100);
                                            @endphp

                                            <p class="mb-0 text-break" style="font-size: 14px; line-height: 1.5; color: #515365; {{ $isToxic ? 'color: #e7515a; font-weight:bold;' : '' }}">
                                                {!! request('q') ? str_ireplace(request('q'), '<span style="background:#F36D21; color:white; padding:0 3px;">'.request('q').'</span>', $displayContent) : $displayContent !!}
                                            </p>
                                        </td>
                                        <td>
                                            @if($comment->id_berita == 0 || $comment->id_berita == null)
                                                <span class="badge badge-warning" style="background-color: #F36D21; color: #fff; padding: 5px 8px; border-radius: 4px; font-size: 11px;">Halaman Komentar Anda</span>
                                            @else
                                                <a href="{{ config('jp.path_url_fe') . 'news/' . ($comment->berita->seo_berita ?? '#') }}" target="_blank" style="font-size: 12px; color: #F36D21; font-weight: 500; text-decoration: none;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                                    {{ Str::limit($comment->berita->judul_berita ?? 'Berita Terhapus', 30) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <span style="font-size: 12px; color: #0F2D52; font-weight: 600;">
                                                {{ $comment->created_at->format('d F Y, H:i') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn btn-sm p-2" title="Edit" style="background-color: #fff; border: 1px solid #0F2D52; color: #0F2D52;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm p-2" style="background-color: #fff; border: 1px solid #e7515a; color: #e7515a; margin-left: 5px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div style="padding: 20px; color: #888;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search mb-3"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                <h5>Data tidak ditemukan.</h5>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION --}}
                        @if($comments->hasPages())
                        <div class="text-center mt-4 px-3 py-3" style="border-top: 1px solid #e0e6ed;">
                            <div style="font-size: 13px; color: #6c757d; margin-bottom: 15px;">
                                Menampilkan <span style="font-weight: 600; color: #0F2D52;">{{ $comments->firstItem() ?? 0 }}</span> - <span style="font-weight: 600; color: #0F2D52;">{{ $comments->lastItem() ?? 0 }}</span> dari <span style="font-weight: 600; color: #0F2D52;">{{ $comments->total() }}</span> data
                            </div>

                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm justify-content-center mb-0" style="gap: 4px;">
                                    {{-- Previous Page Link --}}
                                    @if ($comments->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link" style="border-radius: 5px; border: 1px solid #dee2e6; color: #adb5bd; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $comments->previousPageUrl() }}" style="border-radius: 5px; border: 1px solid #dee2e6; color: #0F2D52; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2; transition: all 0.2s;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $current = $comments->currentPage();
                                        $last = $comments->lastPage();
                                        $start = max(1, $current - 1);
                                        $end = min($last, $current + 1);

                                        if($start > 1) $start = max(1, $current - 1);
                                        if($end < $last) $end = min($last, $current + 1);
                                    @endphp

                                    {{-- First Page Link --}}
                                    @if($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $comments->url(1) }}" style="border-radius: 5px; border: 1px solid #dee2e6; color: #0F2D52; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2; transition: all 0.2s;">1</a>
                                        </li>
                                        @if($start > 2)
                                            <li class="page-item disabled">
                                                <span class="page-link" style="border-radius: 5px; border: 1px solid #dee2e6; color: #adb5bd; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2;">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Array Of Links --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $current)
                                            <li class="page-item active">
                                                <span class="page-link" style="border-radius: 5px; border: 1px solid #0F2D52; background-color: #0F2D52; color: white; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2; font-weight: 600;">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $comments->url($page) }}" style="border-radius: 5px; border: 1px solid #dee2e6; color: #0F2D52; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2; transition: all 0.2s;">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Last Page Link --}}
                                    @if($end < $last)
                                        @if($end < $last - 1)
                                            <li class="page-item disabled">
                                                <span class="page-link" style="border-radius: 5px; border: 1px solid #dee2e6; color: #adb5bd; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2;">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $comments->url($last) }}" style="border-radius: 5px; border: 1px solid #dee2e6; color: #0F2D52; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2; transition: all 0.2s;">{{ $last }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($comments->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $comments->nextPageUrl() }}" style="border-radius: 5px; border: 1px solid #dee2e6; color: #0F2D52; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2; transition: all 0.2s;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link" style="border-radius: 5px; border: 1px solid #dee2e6; color: #adb5bd; padding: 5px 10px; font-size: 13px; min-width: 32px; text-align: center; line-height: 1.2;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. FITUR LIVE SEARCH ---
        const searchInput = document.getElementById('searchInput');
        const dataWrapper = document.getElementById('data-wrapper');
        let timeout = null;

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                dataWrapper.style.opacity = '0.5';

                timeout = setTimeout(() => {
                    const query = searchInput.value;
                    // Ambil parameter filter dari URL saat ini agar tidak hilang saat searching
                    const urlParams = new URLSearchParams(window.location.search);
                    let filterParam = '';
                    if(urlParams.has('filter')) {
                        filterParam = '&filter=' + urlParams.get('filter');
                    }

                    const url = "{{ route('admin.comments.index') }}?q=" + encodeURIComponent(query) + filterParam;

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.getElementById('data-wrapper').innerHTML;
                            dataWrapper.innerHTML = newContent;
                            dataWrapper.style.opacity = '1';
                            window.history.pushState({}, '', url);
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            dataWrapper.style.opacity = '1';
                        });
                }, 500);
            });
        }

        // --- 2. FITUR REALTIME CEK KOMENTAR NEGATIF ---
        setInterval(() => {
            const url = "{{ route('admin.comments.index') }}?get_toxic_count=true";

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.json())
            .then(data => {
                const counterElement = document.getElementById('toxic-counter');
                if (counterElement) {
                    counterElement.innerText = data.total;
                    if(data.total > 0) {
                        counterElement.parentElement.parentElement.style.boxShadow = "0 0 15px rgba(231, 81, 90, 0.8)";
                    } else {
                        counterElement.parentElement.parentElement.style.boxShadow = "0 4px 6px rgba(231, 81, 90, 0.4)";
                    }
                }
            })
            .catch(error => console.error('Error fetching toxic count:', error));
        }, 5000);

        // --- 3. HOVER EFFECT PADA PAGINATION ---
        const pageLinks = document.querySelectorAll('.page-link');
        pageLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                if(!this.parentElement.classList.contains('disabled') && !this.parentElement.classList.contains('active')) {
                    this.style.backgroundColor = '#f8f9fa';
                    this.style.borderColor = '#0F2D52';
                }
            });

            link.addEventListener('mouseleave', function() {
                if(!this.parentElement.classList.contains('disabled') && !this.parentElement.classList.contains('active')) {
                    this.style.backgroundColor = '';
                    this.style.borderColor = '#dee2e6';
                }
            });
        });
    });
</script>
@endsection
