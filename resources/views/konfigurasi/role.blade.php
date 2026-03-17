@extends('layouts.main')
@push('css')
{{-- <link href="{{ asset('theme/src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('theme/src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('theme/src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" /> --}}

<link rel="stylesheet" type="text/css" href="{{ asset('theme/src/plugins/src/table/datatable/datatables.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('theme/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/src/plugins/css/light/table/datatable/custom_dt_custom.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('theme/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/src/plugins/css/dark/table/datatable/custom_dt_custom.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

@endpush

@section('content')
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Konfigurasi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Roles</li>
            </ol>
        </nav>
    </div>

    
        <div class="row layout-top-spacing">
            <div class="row layout-spacing">
                <div class="col-lg-12">
             
                        
<div class="container">
    <h1>How To Install Yajra Datatable In Laravel 10</h1>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th width="100px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<div class="container">
    <div class="card">
        <div class="card-header">Manage Users</div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>

                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area">
                            
                            <table id="style-1" class="table style-1 dt-table-hover non-hover">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column dt-no-sorting"> Record no. </th>
                                        <th>Name</th>
                                        <th>Customers</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th class="">Status</th>
                                        <th class="text-center dt-no-sorting">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="checkbox-column"> 1 </td>
                                        <td class="user-name">Sean Freeman</td>
                                        <td class="">
                                            <a class="profile-img" href="javascript: void(0);">
                                                <img src="{{ asset('/theme/src/assets') }}/img/profile-3.jpeg" alt="product">
                                            </a>
                                        </td>
                                        <td>seanfreeman@admin.com</td>
                                        <td>555-555-5555</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class=" align-self-center d-m-success  mr-1 data-marker"></div>
                                                <span class="label label-success">Approved</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">View Response</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="checkbox-column"> 2 </td>
                                        <td class="user-name">Donna Rogers</td>
                                        <td class="">
                                            <a class="profile-img" href="javascript: void(0);">
                                                <img src="{{ asset('/theme/src/assets') }}/img/profile-2.jpeg" alt="product">
                                            </a>
                                        </td>
                                        <td>donnarogers@user.com</td>
                                        <td>555-555-6666</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class=" align-self-center d-m-warning  mr-1 data-marker"></div>
                                                <span class="label label-warning">Suspended</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">View Response</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="checkbox-column"> 3 </td>
                                        <td class="user-name">Irene Collins</td>
                                        <td class="">
                                            <a class="profile-img" href="javascript: void(0);">
                                                <img src="{{ asset('/theme/src/assets') }}/img/profile-4.jpeg" alt="product">
                                            </a>
                                        </td>
                                        <td>irene@superadmin.com</td>
                                        <td>777-555-5555</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class=" align-self-center d-m-danger  mr-1 data-marker"></div>
                                                <span class="label label-danger">Closed</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink3">
                                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">View Response</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="checkbox-column"> 4 </td>
                                        <td class="user-name">Vincent Carpenter</td>
                                        <td class="">
                                            <a class="profile-img" href="javascript: void(0);">
                                                <img src="{{ asset('/theme/src/assets') }}/img/profile-5.jpeg" alt="product">
                                            </a>
                                        </td>
                                        <td>vincentC@admin.com</td>
                                        <td>555-666-5555</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class=" align-self-center d-m-success  mr-1 data-marker"></div>
                                                <span class="label label-success">Approved</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink4">
                                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">View Response</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="checkbox-column"> 5 </td>
                                        <td class="user-name">Susan Phillips</td>
                                        <td class="">
                                            <a class="profile-img" href="javascript: void(0);">
                                                <img src="{{ asset('/theme/src/assets') }}/img/profile-6.jpeg" alt="product">
                                            </a>
                                        </td>
                                        <td>susan@yahoo.com</td>
                                        <td>444-444-4444</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class=" align-self-center d-m-warning  mr-1 data-marker"></div>
                                                <span class="label label-warning">Suspended</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink5" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink5">
                                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">View Response</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="checkbox-column"> 6 </td>
                                        <td class="user-name">Alexander Gray</td>
                                        <td class="">
                                            <a class="profile-img" href="javascript: void(0);">
                                                <img src="{{ asset('/theme/src/assets') }}/img/profile-7.jpeg" alt="product">
                                            </a>
                                        </td>
                                        <td>alexander@gmail.com</td>
                                        <td>111-111-1111</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class=" align-self-center d-m-danger  mr-1 data-marker"></div>
                                                <span class="label label-danger">Closed</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink6">
                                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">View Response</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="checkbox-column"> 7 </td>
                                        <td class="user-name">Judy Holmes</td>
                                        <td class="">
                                            <a class="profile-img" href="javascript: void(0);">
                                                <img src="{{ asset('/theme/src/assets') }}/img/profile-8.jpeg" alt="product">
                                            </a>
                                        </td>
                                        <td>judy@customer.com</td>
                                        <td>111-666-1111</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class=" align-self-center d-m-success  mr-1 data-marker"></div>
                                                <span class="label label-success">Approved</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                                    <a class="dropdown-item" href="javascript:void(0);">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">View Response</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</div>
@endsection

@push('js')
{{-- <script src="{{ asset('theme/src/plugins/src/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('theme/src/assets/js/dashboard/dash_1.js') }}"></script> --}}
{{-- <script src="{{ asset('theme/src/plugins/src/table/datatable/datatables.js') }}"></script> --}}

<script>
      c1 = $('#style-1').DataTable({
            headerCallback:function(e, a, t, n, s) {
                e.getElementsByTagName("th")[0].innerHTML=`
                <div class="form-check form-check-primary d-block">
                    <input class="form-check-input chk-parent" type="checkbox" id="form-check-default">
                </div>`
            },
            columnDefs:[ {
                targets:0, width:"30px", className:"", orderable:!1, render:function(e, a, t, n) {
                    return `
                    <div class="form-check form-check-primary d-block">
                        <input class="form-check-input child-chk" type="checkbox" id="form-check-default">
                    </div>`
                }
            }],
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "lengthMenu": [5, 10, 20, 50],
            "pageLength": 10
        });

        multiCheck(c1);

       
</script>

<script type="text/javascript">
    $(function () {
        
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('role.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'name', name: 'name'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
        
    });
  </script>
@endpush