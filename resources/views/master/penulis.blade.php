@extends('layouts.materialize')
@push('css')

@endpush
    
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-medium mb-1">Penulis List</h4>
    <p class="mb-4">
      A role provided access to predefined menus and features so that depending on assigned role an
      administrator can have access to what user needs.
    </p>
        <h2>Detail Opini by {{ $opini->nama_penulis }} </h2>
    <div class="row gy-4">
        @foreach ($opini->opini as $item)
            
        
        <div class="col-lg-4">
            <div class="card h-100">
            <div class="card-body">
                <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">  {{ $item['judul_opini'] }} 😀</h4>
                <p class="pb-1">Add 15 team members</p>
                <h4 class="text-primary mb-1">
                    {{ $item['id_opini'] }}
                </h4>
                <p class="mb-2 pb-1">40% OFF 😍</p>
                <a href="javascript:;" class="btn btn-sm btn-primary waves-effect waves-light">Upgrade Plan</a>
            </div>
            <div class="avatar avatar-xl">
                <img src="{{ asset('') }}assets/img/avatars/1.png" alt="Avatar" class="rounded-circle">
              </div>
          {{--   <img src="{{ asset('') }}assets/img/illustrations/illustration-upgrade-account.png" class="
            position-absolute bottom-0 end-0 me-3 mb-3" height="162" alt="Upgrade Account"> --}}
            </div>
        </div>

        @endforeach

    </div>

</div>
@endsection

@push('js')

@endpush