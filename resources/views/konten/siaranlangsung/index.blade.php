@extends('layouts.materialize')
    @push('css')
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
          <h5 class="card-header d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold mb-4" style="font-size:large;">
              <span class="text-muted fw-light">Konten /</span> Siaran Langsung
            </span>
           {{--  <a href="#" class="btn btn-md btn-primary"> Add New  </a> --}}
          </h5>
            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body">
                  @livewire('livereport-table')
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