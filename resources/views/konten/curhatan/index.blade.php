@extends('layouts.materialize')

@section('title', 'Curhat Warga')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Breadcrumb --}}
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Konten /</span> Curhat Warga
        </h4>

        <div class="card">

            {{-- HEADER: SEARCH & FILTER --}}
            <div class="card-header d-flex flex-wrap justify-content-between gap-3">

                {{-- Kiri: Form Filter & Search --}}
                <form action="{{ route('konten.curhatan') }}" method="GET" class="d-flex flex-wrap align-items-center gap-3">

                    {{-- Input Search --}}
                    <div class="input-group input-group-merge" style="width: 250px;">
                        <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari..." aria-label="Cari...">
                    </div>

                    {{-- Dropdown Filter --}}
                    <div style="width: 200px;">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">📂 Semua Curhatan</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>⏳ Belum Dibalas
                            </option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>✅ Sudah Dibalas
                            </option>
                        </select>
                    </div>

                    {{-- Tombol Reset --}}
                    @if (request('search') || request('status') != '')
                        <a href="{{ route('konten.curhatan') }}" class="btn btn-outline-danger">
                            <i class="mdi mdi-refresh me-1"></i> Reset
                        </a>
                    @endif
                </form>

                {{-- Kanan: Info Total --}}
                <div class="d-flex align-items-center">
                    <span class="badge bg-label-primary">Total: {{ $curhatans->count() }}</span>
                </div>
            </div>

            {{-- NOTIFIKASI SUKSES --}}
            @if (session('success'))
                <div class="px-4">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <i class="mdi mdi-check-circle-outline me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            {{-- TABEL DATA --}}
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama & Email</th>
                            <th width="30%">Pesan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Lampiran</th>
                            <th>Waktu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($curhatans as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                {{-- Nama & Email --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-heading">{{ $item->name }}</span>
                                        <small class="text-muted">{{ $item->email }}</small>
                                    </div>
                                </td>

                                {{-- Pesan --}}
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 300px;"
                                        title="{{ $item->message }}">
                                        {{ Str::limit($item->message, 60) }}
                                    </span>
                                </td>

                                {{-- Status Badge (Menggunakan class bawaan template: bg-label-success) --}}
                                <td class="text-center">
                                    @if ($item->is_replied)
                                        <span class="badge bg-label-success rounded-pill">✅ Sudah Dibalas</span>
                                    @else
                                        <span class="badge bg-label-danger rounded-pill">❌ Belum Dibalas</span>
                                    @endif
                                </td>

                                {{-- Lampiran --}}
                                <td class="text-center">
                                    @if ($item->image_path)
                                        {{-- Ubah 'btn-outline-secondary' menjadi 'btn-primary' agar background biru --}}
                                        <a href="{{ asset('storage/' . $item->image_path) }}" target="_blank"
                                            class="btn btn-sm btn-icon btn-primary" title="Lihat Foto">
                                            <i class="mdi mdi-image-outline"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Waktu --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $item->created_at->format('d M Y') }}</span>
                                        <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('curhatan.detail', ['id' => $item->id]) }}" 
   class="btn btn-sm btn-icon btn-info" {{-- btn-info biasanya warna biru muda/cyan --}}
   title="Lihat Detail & Balas">
   <i class="mdi mdi-eye-outline"></i>
</a>

                                        {{-- Tombol Hapus --}}
                                        <a href="{{ route('curhatan.destroy', ['id' => $item->id]) }}"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"
                                            class="btn btn-sm btn-icon btn-danger" title="Hapus">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="mdi mdi-folder-open-outline mdi-48px text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum ada data curhatan masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
