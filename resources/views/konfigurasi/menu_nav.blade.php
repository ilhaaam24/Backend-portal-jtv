@extends('layouts.materialize')
@push('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">

{{-- nestable2/1.6.0/jquery.nestable.min.css --}}
    <style type="text/css">
/* .dd{position:relative;display:block;margin:0;padding:0;max-width:600px;list-style:none;font-size:13px;line-height:20px}.dd-list{display:block;position:relative;margin:0;padding:0;list-style:none}.dd-list .dd-list{padding-left:30px}.dd-empty,.dd-item,.dd-placeholder{display:block;position:relative;margin:0;padding:0;min-height:20px;font-size:13px;line-height:20px}.dd-handle{display:block;height:30px;margin:5px 0;padding:5px 10px;color:#333;text-decoration:none;font-weight:700;border:1px solid #ccc;background:#fafafa;border-radius:3px;box-sizing:border-box}.dd-handle:hover{color:#2ea8e5;background:#fff}.dd-item>button{position:relative;cursor:pointer;float:left;width:25px;height:20px;margin:5px 0;padding:0;text-indent:100%;white-space:nowrap;overflow:hidden;border:0;background:0 0;font-size:12px;line-height:1;text-align:center;font-weight:700}.dd-item>button:before{display:block;position:absolute;width:100%;text-align:center;text-indent:0}.dd-item>button.dd-expand:before{content:'+'}.dd-item>button.dd-collapse:before{content:'-'}.dd-expand{display:none}.dd-collapsed .dd-collapse,.dd-collapsed .dd-list{display:none}.dd-collapsed .dd-expand{display:block}.dd-empty,.dd-placeholder{margin:5px 0;padding:0;min-height:30px;background:#f2fbff;border:1px dashed #b6bcbf;box-sizing:border-box;-moz-box-sizing:border-box}.dd-empty{border:1px dashed #bbb;min-height:100px;background-color:#e5e5e5;background-size:60px 60px;background-position:0 0,30px 30px}.dd-dragel{position:absolute;pointer-events:none;z-index:9999}.dd-dragel>.dd-item .dd-handle{margin-top:0}.dd-dragel .dd-handle{box-shadow:2px 4px 6px 0 rgba(0,0,0,.1)}.dd-nochildren .dd-placeholder{display:none} */
.dd3-content {
        display: block;
        margin: 7px;
        padding: 5px 10px 5px 40px;
        color: #333;
        text-decoration: none;
        font-weight: bold;
        border: 1px solid #ccc;
        background: #f0f0f0;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        cursor: default;
    }

        .dd3-content:hover {
            color: #2ea8e5;
        }

    .dd-dragel > .dd3-item > .dd3-content {
        margin: 0;
    }

    .dd3-item > button {
        margin-left: 36px;
    }

    .dd3-handle {
        position: absolute;
        margin: 0;
        left: 0;
        top: 0;
        cursor: move;
        width: 36px;
        text-indent: 100%;
        white-space: nowrap;
        overflow: hidden;
        border: 1px solid #aaa;
        background: #aaa;
    }

    .dd3-handle:before {
        content: '≡';
        display: block;
        position: absolute;
        left: 0;
        top: 3px;
        width: 100%;
        text-align: center;
        text-indent: 0;
        color: #fff;
        font-size: 20px;
        font-weight: normal;
    }

    .dd3-handle:hover {
        background: #aaa;
    }
</style>



{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.css">  --}}
{{-- <link rel="stylesheet" href="{{asset('assets/css/nestable.css')}}"> --}}
{{-- <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/Nestable2-master/jquery.nestable.css" /> --}}
{{-- <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/Nestable2-master/jquery.nestable.scss" />  --}}
{{-- <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/Nestable2-master/dist/jquery.nestable.min.css" /> --}}

@endpush

        
@section('content')

<div class="container">
  {{-- menu --}}
  <div class="row justify-content-center">
      <div class="col-md-12 mt-5">
          <div class="card">
              <div class="card-body">
                  <div class="header-title">
                      Menu
                      <span style="float:right;">
                          <a href="#newModal" class="btn btn-sm btn-default pull-right" data-toggle="modal">
                              <i class="fas fa-plus"></i> &nbsp; Create menu item
                          </a>
                      </span>
                  </div>

                  {{-- new --}}
                  <div class="row mt-4 mb-4">
                    <menu id="nestable-menu">
                        <button type="button" class="btn btn-sm btn-info" data-action="expand-all">Expand All</button>
                        <button type="button" class="btn btn-sm btn-info" data-action="collapse-all">Collapse All</button>
                    </menu>
                      <div class="col-md-8">
                        
                        <!-- Draggable Handles -->
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="header">
                                        <h2>
                                            DRAGGABLE HANDLES
                                        </h2>
                                        <ul class="header-dropdown m-r--5">
                                            <li class="dropdown">
                                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="javascript:void(0);">Action</a></li>
                                                    <li><a href="javascript:void(0);">Another action</a></li>
                                                    <li><a href="javascript:void(0);">Something else here</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="body">
                                        <div class="clearfix m-b-20">
                                            <div class="dd nestable-with-handle">
                                                <ol class="dd-list">
                                                    <li class="dd-item dd3-item" data-id="13">
                                                        <div class="dd-handle dd3-handle"></div>
                                                        <div class="dd3-content">Item 13</div>
                                                    </li>
                                                    <li class="dd-item dd3-item" data-id="14">
                                                        <div class="dd-handle dd3-handle"></div>
                                                        <div class="dd3-content">Item 14</div>
                                                    </li>
                                                    <li class="dd-item dd3-item" data-id="15">
                                                        <div class="dd-handle dd3-handle"></div>
                                                        <div class="dd3-content">Item 15</div>
                                                        <ol class="dd-list">
                                                            <li class="dd-item dd3-item" data-id="16">
                                                                <div class="dd-handle dd3-handle"></div>
                                                                <div class="dd3-content">Item 16</div>
                                                            </li>
                                                            <li class="dd-item dd3-item" data-id="17">
                                                                <div class="dd-handle dd3-handle"></div>
                                                                <div class="dd3-content">Item 17</div>
                                                            </li>
                                                            <li class="dd-item dd3-item" data-id="18">
                                                                <div class="dd-handle dd3-handle"></div>
                                                                <div class="dd3-content">Item 18</div>
                                                            </li>
                                                        </ol>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                        <b>Output JSON</b>
                                        <textarea cols="30" rows="3" class="form-control no-resize" readonly>[{"id":13},{"id":14},{"id":15,"children":[{"id":17},{"id":16},{"id":18}]}]</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
            <!-- #END# Draggable Handles -->
                      </div>
                      <div class="col-md-4">
                          <div class="card">
                              <div class="card-body">
                                  <p>Drag items to move them in a different order <br> <span class="text-info">Supports (2) level deep</span></p>
                              </div>
                          </div>
                      </div>
                  </div>

                 

             
              </div>
          </div>
      </div>
  </div>

</div>
  
@endsection

@push('js')

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.js"></script> --}}
<script src="{{asset('assets/vendor/libs/Nestable2-master/jquery.nestable.js')}}"></script>
<script src="{{asset('assets/vendor/libs/Nestable2-master/dist/sortable-nestable.js')}}"></script>

<script>
    $('.dd-expand').style('');
</script>
{{-- topmenu --}}


@endpush