@extends('layouts.materialize')
    @push('css')
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
          <h4 class="fw-bold mb-4"><span class="text-muted fw-light">Konten /</span> Instagram Api</h4>
            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body">
                  @livewire('instagram-table')
                        {{-- <livewire:iklan-table/> --}}
                </div>
                </div>
              </div>
              </div>
            </div>
             
        @endsection

    @push('js')
    <script src="//unpkg.com/alpinejs" defer></script>
@endpush