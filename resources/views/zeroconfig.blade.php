@extends('layouts.materialize')
@push('css')
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row gy-4">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <a href="{{route('berita.create')}}" class="btn btn-primary"> Create new post </a>
                        </div>
                    </div>

                    <div class="card-body">
                            {{-- <livewire:berita-table/> --}}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush