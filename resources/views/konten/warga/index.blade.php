@extends('layouts.materialize')
    @push('css')
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
          <div class="d-flex justify-content-between">
          <h5 class="card-headealign-items-center mb-2">
            <span class="fw-bold mb-4" style="font-size:large;">
              <span class="text-muted fw-light">Konten /</span> Jurnalisme Warga
            </span>
            {{-- <a href="#" class="btn btn-md btn-primary"> Add New  </a> --}}
          </h5>

          <h5 class="card-header align-items-center mb-2">
            <a href="{{ route('jurnal.create')}}" class="btn btn-md btn-primary">   <i class="fas fa-plus"></i> &nbsp; Add New  </a>
          </h5>
        </div>
      
            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body">
                        <livewire:opini-warga-table/>
                </div>
                </div>
              </div>
              </div>
            </div>
             
        @endsection

    @push('js')

    @endpush