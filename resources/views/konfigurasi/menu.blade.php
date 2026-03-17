@extends('layouts.materialize')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css"> 
<style>
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

<style>
    .item-list,.info-box{background: #fff;padding: 10px;}
    .item-list-body{max-height: 250px;overflow-y: scroll;}
    .panel-body p{margin-bottom: 5px;}
    .info-box{margin-bottom: 15px;}
    .item-list-footer{padding-top: 10px;}
    .panel-heading a{display: block;}
    .form-inline{display: inline;}
    .form-inline select{padding: 4px 10px;}
    .btn-menu-select{padding: 4px 10px}
    .disabled{pointer-events: none; opacity: 0.7;}
    .menu-item-bar{background: #eee;padding: 5px 10px;border:1px solid #d7d7d7;margin-bottom: 5px; width: 75%; cursor: move;display: block;}
    #serialize_output{display: block;}
    .menulocation label{font-weight: normal;display: block;}
    body.dragging, body.dragging * {cursor: move !important;}
    .dragged {position: absolute;z-index: 1;}
    ol.example li.placeholder {position: relative;}
    ol.example li.placeholder:before {position: absolute;}
    #menuitem{list-style: none;}
    #menuitem ul{list-style: none;}
    .input-box{width:75%;background:#fff;padding: 10px;box-sizing: border-box;margin-bottom: 5px;}
    .input-box .form-control{width: 50%}

    .select2-container {
    margin: 0;
    width: 50% !important;}
  </style>	
 	
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css"> --}}
@endpush

        
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row gy-4">
    <!-- Gamification Card -->
    <div class="card mb-1">
    {{--   <h5 class="card-header"> <span>Menus</span></h5> --}}
   
      <div class="row">
        <div class="col-md-12">
          <div class="card-body" class="padding: inherit;">
            <form id="formAccountSettingsApiKey" method="POST" onsubmit="return false" class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
              <div class="row gy-3 fv-plugins-icon-container">
                <div class="col-12">
                  @if(count($menus) > 0)		
                  Select a menu to edit: 		
                  <form action="{{url('manage-menus')}}" class="form-inline">
                    <select name="id" class="select2 form-select mb-2" style="width: 50%!important">
                      @foreach($menus as $menu)
                          @if($desiredMenu != '')
                          <option value="{{$menu->id}}" @if($menu->id == $desiredMenu->id) selected @endif>{{$menu->name}}</option>
                        @else
                          <option value="{{$menu->id}}">{{$menu->name}}</option>
                        @endif
                      @endforeach
                    </select>
                    <button class="btn btn-sm btn-default btn-menu-select">Select</button>
                  </form> 
                  or
                  @endif 
                  <a href="{{url('manage-menus?id=new')}}">Create a new menu</a>.	
                </div>
               
              </div>
            <input type="hidden"></form>
          </div>
        </div>
      
      </div>
    </div>


    <div class="col-md-12 col-lg-4">
      <div class="card">
        <div class="card-header"><h5>Add Menu Items</h5></div>
        <div class="card-body">
          <div class="d-flex align-items-end row">
            <div class="accordion accordion-header-primary" id="accordionStyle1">
              <div class="accordion-item active">
                <h2 class="accordion-header">
                  <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-1" aria-expanded="true">
                    Categories
                  </button>
                </h2>
                <div class="panel-body">						
                

                <div id="accordionStyle1-1" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1">
                  <div class="accordion-body">
                    <div class="item-list-body mb-3">
                    <div class="app-calendar-events-filter mb-3">
                      @foreach ($categories as $cat)
                        <div class="form-check form-check-secondary mb-3">
                          <input class="form-check-input input-filter" type="checkbox" name="select-category[]" value="{{$cat->id}}" data-value="{{$cat->id}}" >
                          <label class="form-check-label" for="select-personal">{{$cat->title}}</label>
                        </div>
                      @endforeach
                      
                    </div>
                    </div>

                    
                    <div class="col-12 d-flex justify-content-between">
                      <div class="form-check custom-option custom-option-icon waves-effect " 
                      style="text-align: left;
                      padding: 0.6em;
                      font-size: 0.8375rem;
                      /* color: rgb(255, 255, 255); 
                      background-color: #6cd6e9;*/
                      ">
                     
                        <div class="form-check-primary">
                            <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all">
                            {{-- <i class="mdi mdi-home-city-outline"></i> --}}
                            <span class="custom-option-title">Select All</span>
                            {{-- <small>List property as Builder, list your project and get highest reach.</small> --}}                      
                        </div>
                      </div>
  
                      &nbsp;
                      <button class="btn btn-info waves-effect"
                      style=" --bs-btn-font-size: 0.6375rem;--bs-btn-padding-x: 0.375rem;">
                        <span class="align-middle d-sm-inline-block d-none me-sm-1" id="add-categories">Add To Menu</span>
                      </button>
                    </div>
                

                   

                  </div>
                </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-2" aria-expanded="false">
                    Post
                  </button>
                </h2>
                <div id="accordionStyle1-2" class="accordion-collapse collapse" data-bs-parent="#accordionStyle1">
                  <div class="accordion-body">
                    <div class="dd">
                        <ol class="dd-list">
                            <li class="dd-item" data-id="1">
                                <div class="dd-handle">Item 1</div>
                            </li>
                            <li class="dd-item" data-id="2">
                                <div class="dd-handle">Item 2</div>
                            </li>
                            <li class="dd-item" data-id="3">
                                <div class="dd-handle">Item 3</div>
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">Item 4</div>
                                    </li>
                                    <li class="dd-item" data-id="5" data-foo="bar">
                                        <div class="dd-nodrag">Item 5</div>
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-3" aria-expanded="false">
                    Custom Link
                  </button>
                </h2>
                <div id="accordionStyle1-3" class="accordion-collapse collapse" data-bs-parent="#accordionStyle1">
                  <div class="accordion-body">
                        <div class="panel panel-default">
                       
                          
                            <div class="panel-body">						
                            <div class="item-list-body">
                              <div class="form-floating form-floating-outline mb-3 mt-3">
                                <input type="url" class="form-control" id="url" name="url" placeholder="https://" aria-describedby="floatingInputUrl">
                                <label for="url">URL</label>
                                <div id="floatingInputUrl" class="form-text">
                                 {{-- Fill Url with correct link. --}}
                                </div>
                              </div>

                              <div class="form-floating form-floating-outline mb-3 mt-3">
                                <input type="text" class="form-control" id="linktext" name="linktext" placeholder=" " aria-describedby="floatingInputLink">
                                <label for="linktext">Link Text</label>
                                <div id="floatingInputLink" class="form-text">
                                 {{-- Fill Url with correct link. --}}
                                </div>
                              </div>

                          
                             
                              </div>	
                              <div class="item-list-footer">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                  <button type="button" class="btn btn-info btn-sm waves-effect" id="add-custom-link">Add to Menu</button>
                                </div>
                             
                              </div>
                            </div>
                        </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Gamification Card -->

    <!-- Statistics Total Order -->
    <div class="col-lg-8 col-sm-12">
      <div class="card h-100">
        <div class="card-body">
            {{-- content --}}
            <h5>Menu Structure</h5>
            {{-- {{ $desiredMenu }} --}}
            @if($desiredMenu == '')
            <h4>Create New Menu</h4>
            <form method="post" action="{{url('create-menu')}}">
              {{csrf_field()}}
              <div class="row">
                <div class="col-sm-12">
                  <label>Name</label>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">							
                    <input type="text" name="title" class="form-control">
                  </div>
                </div>
                <div class="col-sm-6 text-right">
                  <button class="btn btn-sm btn-primary">Create Menu</button>
                </div>
              </div>
            </form>
            @else

            <div id="menu-content">
              <div id="result"></div>
            <div style="min-height: 240px;">
                <p>Select categories, pages or add custom links to menus.</p>
                <div class="card">
                  <div class="card-body">
                      <div class="header-title">
                          Menu
                          <span style="float:right;">
                              <a href="#newModal" class="btn btn-sm btn-default pull-right" data-bs-toggle="modal">
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
                              <div class="dd nestable-with-handle" id="nestable">
                              
                                {!! $menu !!}
                              </div>
    
                              <p id="success-indicator" style="display:none; margin-right: 10px;">
                                  <i class="fas fa-check-circle"></i> Menu order has been saved
                              </p>
    
                        
                              <textarea id="Menu-output" class="form-control"></textarea>
                          </div>
                          <div class="col-md-4">
                              <div class="card">
                                  <div class="card-body">
                                      <p>Drag items to move them in a different order <br> <span class="text-info">Supports (2) level deep</span></p>
                                  </div>
                              </div>
                          </div>
                      </div>
    
                      <!-- Create new item Modal -->
                      <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                              <div class="modal-content">
    
                                  <div class="modal-header">
                                      <h5 class="modal-title">Provide details of new menu item</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
    
                                  {{-- {{ Form::open(array('route'=>'topnew','class'=>'form-horizontal'))}} --}}
                                  {{ html()->form('PUT', '/topnew')->open() }}
                                      <div class="modal-body">
                                          <div class="form-group row">
                                              <label for="title" class="col-md-3 control-label">Title</label>
                                              <div class="col-md-9">
                                              {{-- {{ Form::text('title',null,array('class'=>'form-control'))}} --}}
                                              {{ html()->text('title') }}
                                              </div>
                                          </div>
                                          <div class="form-group row">
                                              <label for="slug" class="col-md-3 control-label">Slug</label>
                                              <div class="col-md-9">
                                              {{-- {{ Form::text('slug',null,array('class'=>'form-control'))}} --}}
                                              {{ html()->text('slug') }}
                                              </div>
                                          </div>
                                      </div>
                                      <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                      <button type="submit" class="btn btn-primary">Create</button>
                                      </div>
                                  {{-- {{ Form::close()}} --}}
                                  {{ html()->form()->close() }}
                              </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->
    
                      <!-- Delete item Modal -->
                      <div class="modal border-danger fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
    
                                  <div class="modal-header bg-danger text-white">
                                      <h5 class="modal-title">Delete Item</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                  </div>
                                  {{ html()->form('DELETE', '/admin/topmenudelete')->open() }}
                                {{--   {{ Form::open(array('url'=>'/admin/topmenudelete', 'method' => 'DELETE')) }} --}}
                                      <div class="modal-body">
                                          <p>Are you sure you want to delete this menu item?</p>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                          <input type="hidden" name="delete_id" id="postvalue" value="" />
                                          <input type="submit" class="btn btn-danger" value="Delete Item" />
                                      </div>
                                      {{ html()->form()->close() }}
                                  {{-- {{ Form::close() }} --}}
                              </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->
                      {{-- new --}}
                  </div>
              </div>
              
              </div>	
            @if($desiredMenu != '')
              
            @endif										
          </div>
          
            @endif	
        </div>
      </div>
    </div>
    <!--/ Statistics Total Order -->

  </div>
</div>

  
    
  

  
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ url('assets/vendor/libs/sortablejs/sortable.js')}}"></script>
<script src="{{ asset('') }}assets/vendor/libs/select2/select2.js"></script>
<script src="{{asset('assets/vendor/libs/Nestable2-master/jquery.nestable.js')}}"></script>
<script src="{{asset('assets/vendor/libs/Nestable2-master/dist/sortable-nestable.js')}}"></script>

<script>
  $(document).ready(function(){
  var moveEl={from:"",to:""};
  var hovering="";
  var PreventOnloadSave=0;
  var flag=0;
  var draggedID;

  var updateOutput = function(e){

      var list   = e.length ? e : $(e.target),
          output = list.data('output');

      var previousVal = output.val();
      var newVal = window.JSON.stringify(list.nestable('serialize'));
      //console.log ("Previous value: " + previousVal );
      if (window.JSON) {
          output.val( newVal );

      } else {
          output.val('JSON browser support required for this demo.');
      }
  };

  $('.dd').nestable({
      maxDepth: 2,
      group:1, 
  }).on('change', updateOutput);

  $('#nestable').nestable().on('dragEnd', function(event, item, source, destination, position) {
      var currentItem = $(item).attr('data-id');
      var itemParent = $(item).parent().parent().attr('data-id');

      var order = new Array();
      $("li[data-id='"+currentItem +"']").find('ol:first').children().each(function(index,elem) {
          order[index] = $(elem).attr('data-id');
      });
      if (order.length === 0){
          var rootOrder = new Array();
          $("#nestable > ol > li").each(function(index,elem) {
          rootOrder[index] = $(elem).attr('data-id');
          });
      }
      let serial = $('.dd').nestable('serialize');
      var datastring = JSON.stringify(serial);
      let asNestedSet = $('.dd').nestable('asNestedSet');

     
      var token = $('form').find( 'input[name=_token]' ).val();    
      $.post('{{url("menustop/reorder/")}}',
                  {
                      source : currentItem,
                      destination: itemParent,
                      order:JSON.stringify(order),
                      rootOrder:JSON.stringify(rootOrder),
                      // list :datastring,
                      _token: token
                  },
                  function(data) {
                  // console.log('data '+data); 
                  })
              .done(function() {
                  $( "#success-indicator" ).fadeIn(100).delay(1000).fadeOut();
              })
              .fail(function() {  })
              .always(function() {  });
  })

  updateOutput($('#nestable').data('output', $('#Menu-output')));

  $('#nestable-menu').on('click', function(e){
      var target = $(e.target),
          action = target.data('action');
      if (action === 'expand-all') {
          $('.dd').nestable('expandAll');
      }
      if (action === 'collapse-all') {
          $('.dd').nestable('collapseAll');
      }
  });

  $('#nestable3').nestable();
  //--------------------------------------------------
  $(".dd").on("mouseover",function(){
      // console.log("mouseover1");
      hovering = $(this).attr("id");
      console.log(hovering);
  });


  $(".dd").on("mousedown",function(){
      console.log("");
      console.log("--------------------------- Mousedown on an element");

      setTimeout(function(){
          if( $("body").find(".dd-dragel") ){
              
              draggedID = $("body").find(".dd-dragel").find(".dd-item").attr("data-id");
              console.log("draggging ID: "+draggedID);
              if( $("body").find(".dd-dragel").find(".dd-item").length>1){
                  console.log("Dragged element is red... No can do since maxDepth is 1.");
                  $("body").find(".dd-dragel").css("background-color","red");
              }
          }
      },100);

      console.log( "FROM: "+hovering );
      moveEl.from=hovering;
  });

  $(".dd").on("mouseup",function(){
      console.log( "TO: "+hovering );
      console.log("");
      moveEl.to=hovering;
  }); 
});

// --------------------- 
const selectPicker = $('.selectpicker'),
    select2 = $('.select2'),
    select2Icons = $('.select2-icons');

    $(document).ready(function() {
      select2.select2();
});

  selectAll = document.querySelector('.select-all')
  if (selectAll) {
      selectAll.addEventListener('click', e => {
        if (e.currentTarget.checked) {
          document.querySelectorAll('.input-filter').forEach(c => (c.checked = 1));
        } else {
          document.querySelectorAll('.input-filter').forEach(c => (c.checked = 0));
        }
      });
    }


    $('#add-categories').click(function(){
      var menuid = <?=$desiredMenu->id?>;
      var n = $('input[name="select-category[]"]:checked').length;
      var array = $('input[name="select-category[]"]:checked');
      var ids = [];
      for(i=0;i<n;i++){
        ids[i] =  array.eq(i).val();
      }
      if(ids.length == 0){
        return false;
      }
      $.ajax({
        type:"get",
        data: {menuid:menuid,ids:ids},
        url: "{{url('add-categories-to-menu')}}",				
        success:function(res){				
          location.reload();
        }
      })
    })
   
    $("#add-custom-link").click(function(){
      var menuid = <?=$desiredMenu->id?>;
      alert(menuid);
      var url = $('#url').val();
      var link = $('#linktext').val();
      if(url.length > 0 && link.length > 0){
        $.ajax({
          type:"get",
          data: {menuid:menuid,url:url,link:link},
          url: "{{url('add-custom-link')}}",				
          success:function(res){
            location.reload();
          }
        })
      }
    })

  const menuitems_id = document.getElementById('menuitems');
  
  Sortable.create(menuitems_id, {
    animation: 150,
    group: 'serialization',
  });

  $('#saveMenu').click(function(){
      var menuid = <?=$desiredMenu->id?>;
      var location = $('input[name="location"]:checked').val();
      var newText = $("#serialize_output").text();
      var data = JSON.parse($("#serialize_output").text());	
      $.ajax({
        type:"get",
        data: {menuid:menuid,data:data,location:location},
        url: "{{url('update-menu')}}",				
        success:function(res){
          window.location.reload();
        }
      })	
    })
    
    $('.dd').nestable({
    onDragStart: function (l, e) {
        // get type of dragged element
        var type = $(e).data('type');
        
        // based on type of dragged element add or remove no children class
        switch (type) {
            case 'type1':
                // element of type1 can be child of type2 and type3
                l.find("[data-type=type2]").removeClass('dd-nochildren');
                l.find("[data-type=type3]").removeClass('dd-nochildren');
                break;
            case 'type2':
                // element of type2 cannot be child of type2 or type3
                l.find("[data-type=type2]").addClass('dd-nochildren');
                l.find("[data-type=type3]").addClass('dd-nochildren');
                break;
            case 'type3':
                // element of type3 cannot be child of type2 but can be child of type3
                l.find("[data-type=type2]").addClass('dd-nochildren');
                l.find("[data-type=type3]").removeClass('dd-nochildren');
                break;
            default:
                console.error("Invalid type");
        }
    }
});


</script>

@endpush