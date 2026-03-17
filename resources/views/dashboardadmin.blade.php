@extends('layouts.materialize')
@push('css')

@endpush


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row gy-4 mb-3">
           
            <div class="col-lg-5 order-1">
                <div class="card h-100">
                  <div class="card-header">
                    <div class="d-flex justify-content-between">
                      <h5 class="mb-2">Berita</h4>
                    </div>
                  </div>
                  <div class="card-body d-flex justify-content-between flex-wrap gap-2 gap-md-3">
                    <div class="d-flex gap-2 gap-md-3">
                      <div class="avatar">
                        <div class="avatar-initial bg-label-light rounded">
                          <i class="mdi mdi-file-document-outline mdi-24px"></i>
                        </div>
                      </div>
                      <div class="card-info">
                        <h5 class="mb-0"> {{ $news_count }}</h5>
                        <small class="text-muted">Total</small>
                      </div>
                    </div>
                    <div class="d-flex gap-2 gap-md-3">
                      <div class="avatar">
                        <div class="avatar-initial bg-label-info rounded">
                          <i class="mdi mdi-checkbox-marked-circle-outline mdi-24px"></i>
                        </div>
                      </div>

                      <div class="card-info">
                        <h5 class="mb-0">{{ $count_publish_news }}</h5>
                        <small class="text-muted">Publish</small>
                      </div>
                    </div>
                    <div class="d-flex gap-2 gap-md-3">
                      <div class="avatar">
                        <div class="avatar-initial bg-label-dark rounded">
                          <i class="mdi mdi-clock-time-eight-outline mdi-24px"></i>
                        </div>
                      </div>
                      <div class="card-info">
                        <h5 class="mb-0">{{ $count_draft_news }}</h5>
                        <small class="text-muted">Draft</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @if(auth()->user()->can('read opini'))
              <div class="col-lg-4 order-2">
                <div class="card h-100">
                  <div class="card-header">
                    <div class="d-flex justify-content-between">
                      <h5 class="mb-2">Jurnalisme Warga</h4>
                    </div>
                    <div class="d-flex align-items-center">
                    </div>
                  </div>
               
                  <div class="card-body d-flex justify-content-between flex-wrap gap-2">
                    <div class="d-flex gap-2">
                      <div class="avatar">
                        <div class="avatar-initial bg-label-light rounded">
                          <i class="mdi mdi-file-document-outline mdi-24px"></i>
                        </div>
                      </div>
                      <div class="card-info">
                        <h5 class="mb-0"> {{ $opini_count }}</h5>
                        <small class="text-muted">Total</small>
                      </div>
                    </div>
                    <div class="d-flex gap-2">
                      <div class="avatar">
                        <div class="avatar-initial bg-label-info rounded">
                          <i class="mdi mdi-checkbox-marked-circle-outline mdi-24px"></i>
                        </div>
                      </div>
                      <div class="card-info">
                        <h5 class="mb-0">{{ $count_publish_opini }}</h5>
                        <small class="text-muted">Publish</small>
                      </div>
                    </div>
                    <div class="d-flex gap-2">
                      <div class="avatar">
                        <div class="avatar-initial bg-label-dark rounded">
                          <i class="mdi mdi-clock-time-eight-outline mdi-24px"></i>
                        </div>
                      </div>
                      <div class="card-info">
                        <h5 class="mb-0">{{ $count_draft_opini }}</h5>
                        <small class="text-muted">Draft</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endif

              @if(auth()->user()->can('read opini'))
            <div class="col-lg-3 col-sm-6 order-3">
                <div class="card">
                  <div class="row">
                    <div class="col-6">
                      <div class="card-body">
                        <div class="card-info mb-3 pb-2">
                          <h5 class="mb-3 text-nowrap">Penulis</h5>
                        </div>
                        <div class="d-flex align-items-end gap-2 gap-md-3">
                          <div class="d-flex gap-2">
                            <div class="avatar">
                              <div class="avatar-initial bg-label-light rounded">
                                <i class="mdi mdi-account-edit mdi-36px"></i>
                              </div>
                            </div>
                          </div>
                            <div class="text-dark lh-xs"> 
                                <h3 class="mb-0 me-2">{{ $penulis }}</h4>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-6 text-end d-flex align-items-end">
                      <div class="card-body pb-0 pt-3">
                        <img src="{{ asset('') }}assets/img/illustrations/penulis1.png" alt="Ratings" class="img-fluid" width="200">
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            @endif
            @if(auth()->user()->can('read opini'))
              <div class="col-lg-3 col-sm-6 order-4 order-md-5">
                <div class="card">
                  <div class="row">
                    <div class="col-6">
                      <div class="card-body">
                        <div class="card-info mb-3 pb-2">
                          <h5 class="mb-3 text-nowrap">Pengguna</h5>
                        
                        </div>
                        <div class="d-flex align-items-end gap-2 gap-md-3">
                          <div class="d-flex gap-2">
                            <div class="avatar">
                              <div class="avatar-initial bg-label-light rounded">
                                <i class="mdi mdi-account-group-outline mdi-36px"></i>
                              </div>
                            </div>
                          </div>
                            <div class="text-dark lh-xs">  
                                <h3 class="mb-0 me-2">{{ $pengguna }}</h4>
                            </div>
                        
                        </div>
                      </div>
                    </div>
                    <div class="col-6 text-end d-flex align-items-end">
                      <div class="card-body pb-0 pt-3">
                        <img src="{{ asset('') }}assets/img/illustrations/pengguna1.png" alt="Ratings" class="img-fluid" width="200">
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            
            @endif
            <div class="col-lg-9 order-5 order-md-4">
              <div class="card h-100">
                <div class="card-header">
                  <div class="d-flex justify-content-between">
                    <h5 class="mb-0">Grafik Berita</h4>
                  </div>
                </div>
                <div class="card-body d-flex justify-content-between">
                  <div class="chart-container" style="position: relative; height:50vh; width:80vw">
                    <canvas id="myChart"></canvas>
                  </div>
                </div>
              </div>
            </div>

        </div>

       
    </div>
@endsection

@push('js')
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script src="{{ asset('') }}assets/vendor/libs/chartjs/chartjs.js"></script>
<script>
   const ctx = document.getElementById('myChart');
// var cData = json_encode($DataBerita);

var dataa = JSON.parse(`<?php echo $DataBerita; ?>`);
// console.log(dataa);
var row = dataa;
        var jumlah = [];
				var tahun = [];
				var month = [];
				var bulan = [];
				var color = [];
				var highlight = [];

        for (var x = 0; x < row.length; x++) {
          var nama_bulan = row[x].BULAN + ' ' +row[x].year;

					jumlah.push(row[x].count);
					tahun.push(row[x].year);
					month.push(row[x].month);
					bulan.push(nama_bulan);
					color.push(row[x].COLOR);
					highlight.push(row[x].HIGHLIGHT);
          // console.log(row[x].BULAN + row[x].year);
				}

        var chart_data = {
					labels: bulan,
					datasets: [{
						label: 'JUMLAH BERITA PER BULAN',
						backgroundColor: color,
						color: highlight,
						data: jumlah
					}]
				};


        var optionss = {
					responsive: true,
          animations: {
            tension: {
              duration: 1000,
              easing: 'linear',
              from: 1,
              to: 0,
              loop: true
            }
          },
          maintainAspectRatio:true,
					scales: {
            y: { // defining min and max so hiding the dataset does not change scale range
              min: 0
            }
					
					}
				};

        var group_chart = $('#myChart');

				var graph = new Chart(ctx, {
					type: "bar", // tipe chart yg mau digunakan
					data: chart_data,
          options: optionss
				});
 

</script>


@endpush