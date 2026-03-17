@extends('layouts.materialize')
    @push('css')
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />
    @endpush

      @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
       
          <div class="d-flex justify-content-between">
            <h5 class="card-header align-items-center mb-2">
            
                  <span class="fw-bold mb-4" style="font-size:large;">
                    <span class="text-muted fw-light">Laporan /</span> Berita</span>
             
            </h5>
            <h5 class="card-header align-items-center mb-2">
           </h5>
          </div>
           
            <!-- Product List Widget -->
            
            <form id="reportForm" autocomplete="off">
            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
                <div class="card mb-6">
                  <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                      <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-6">
                          <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                            <div class="fc-toolbar-chunk">
                              <label class="" for="">Filter Tanggal : </label>
                           
                                <div class="form-floating form-floating-outline">
                                <div class="input-group input-daterange mb-3 mt-4" id="bs-datepicker-daterange">
                                    <input type="text" id="start_at" placeholder="Tanggal Awal" class="form-control" />
                                    <span class="input-group-text">to</span>
                                    <input type="text" id="end_at" placeholder="Tanggal Akhir" class="form-control" />
                                  </div>
                                </div>
                              </div>
                          </div>
                          <hr class="d-none d-sm-block d-lg-none me-6" />
                        </div>
                       
                        <div class="col-sm-6 col-lg-4">
                          <div class="d-flex justify-content-start align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                            {{-- <div class="form-floating form-floating-outline mb-3 mt-3">
                                <select class="select2 form-select" 
                                    id="select_biro_id" name="biro_id">                                                
                                </select>
                            </div> --}}
                            <div class="fc-toolbar-chunk">
                            <label class="" for="">Report By : </label>
                            <div class="form-floating form-floating-outline mb-3 mt-3">
                              <select class="select2 form-select" id="select_filter_by" name="select_filter_by">  
                                <option value="author" selected>Author</option>
                                <option value="editor">Editor</option>                                              
                              </select>
                            </div>
                          </div>
                          </div>
                        </div>
                        <div class="col-sm-6 col-lg-2">
                          <div class="d-flex justify-content-between align-items-start">
                            <div class="form-floating form-floating-outline mb-3 mt-5">
                              <button type="button" class="btn btn-primary" id="filterBtn">Generate Report</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
                

              <div class="card mt-4">
                <div class="card-header">
                
              </div>
                <div class="card-body">
                  @cekRolePermission('read berita')

                  {{-- $dataTable->table() --}}
                  <div class="row align-items-center">
                  <span id="title_report" class="fw-bold text-center mb-3"></span>
                  <span id="periode_report" class="fw-bold text-center mb-3"></span>
                  </div>
                  <table class="table table-bordered" id="datatableReport">
                    <thead>
                        <tr>
                            {{-- <th>No</th> --}}
                            <th>Biro</th>
                            <th>Username</th></th>
                            <th>Total Publish Berita</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                   @endcekRolePermission
                </div>
                </div>
              </div>
            </div>
        </div>
             
      @endsection

     
    @push('js')
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script>

      $( document ).ready(function() {
        $('.select2').select2({
            dropdownAutoWidth : false,
            width: 'auto'
        })

        fetchmainmenu();
          var bsDatepickerRange = $('#bs-datepicker-daterange');
            // Range
            if (bsDatepickerRange.length) {
              bsDatepickerRange.datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                clearBtn: true,
                todayHighlight: true,
                orientation: isRtl ? 'auto right' : 'auto left'
              });
            }

            $('#filterBtn').click(function() {
                  filterData();
            });

            function filterData() {
              var startDate = $('#start_at').val() + ' 00:00:00';
              var endDate = $('#end_at').val() + ' 23:59:59';
              var filter_by = $('#select_filter_by').val();
              $('#title_report').html('REPORT PUBLISH BERITA BY ' + filter_by.toUpperCase());
              $('#periode_report').html('Periode ' + moment($('#start_at').val()).format( "Do MMMM YYYY") +' - ' + moment($('#end_at').val()).format( "Do MMMM YYYY") );
              $.ajax({
                    url: "{{ route('getReportNews') }}", // Replace with your actual endpoint
                    method: 'POST',
                    data: {
                      _token: $('meta[name="csrf-token"]').attr('content'),
                        start_date: startDate,
                        end_date: endDate,
                        filter_by: filter_by
                    },
                    success: function(response) {
                        // Clear existing table rows
                        $('#datatableReport tbody').empty();
                       
                        // Populate table with new data
                        response.report.forEach(function(item) {
                            $('#datatableReport tbody').append('<tr><td>' + item.nama_biro + '</td><td>' + item.nama_pengguna + '</td><td>' + item.total + '</td></tr>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.              error('Error:', status, error);
                    }
                });
            }

           
      /*     $('#daterange span').html(start_date.format('MMMM D, YYYY') + ' - ' + end_date.format('MMMM D, YYYY'));

          $('#dateRangePicker').daterangepicker({
              startDate : start_date,
              endDate : end_date
          }, function(start_date, end_date){
              $('#dateRangePicker span').html(start_date.format('MMMM D, YYYY') + ' - ' + end_date.format('MMMM D, YYYY'));

              table.draw();
          }); */

          /* var table = $('#daterange_table').DataTable({
              processing : true,
              serverSide : true,
              ajax : {
                  url : "{{ route('getReportNews') }}",
                  data : function(data){
                      data.from_date = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                      data.to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                  }
              },
              columns : [
                  {data : 'id', name : 'id'},
                  {data : 'biro', name : 'biro'},
                  {data : 'nama_pengguna', name : 'nama_pengguna'},
                  {data : 'jumlah', name : 'jumlah'}
              ]
          });
 */
    });

    function fetchmainmenu() {
        $.ajax({
                url: "{{url('getBiro')}}",
                type: "POST",
                data: {
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#select_biro_id').html('<option value=""> -- Pilih Nama Biro -- </option>');
                    
                            $.each(res.list_biro, function (key, value) {
                            $("#select_biro_id").append('<option value="' + value
                                .id + '">' + value.nama_biro + '</option>');
                            });
                }
            });
    }
    
     $(document).on('click', '#terbaru_berita', function () {
              var id = $(this).data('id');
              terbaruBerita(id);
     });

     $(document).on('click', '#terbaik_berita', function () {
              var id = $(this).data('id');
              terbaikBerita(id);
     });

   
  </script>
@endpush