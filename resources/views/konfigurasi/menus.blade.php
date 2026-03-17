@extends('layouts.materialize')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css"> 
{{--<link rel="stylesheet" href="{{asset('assets/css/nestable.css')}}"> --}}
{{-- <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/Nestable2-master/jquery.nestable.css" /> --}}
{{-- <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/Nestable2-master/jquery.nestable.scss" />  --}}
{{-- <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/Nestable2-master/dist/jquery.nestable.min.css" /> --}}
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
  </div>

</div>
  
@endsection

@push('js')

<script src="{{asset('assets/vendor/libs/Nestable2-master/jquery.nestable.js')}}"></script>
<script src="{{asset('assets/vendor/libs/Nestable2-master/dist/sortable-nestable.js')}}"></script>


{{-- topmenu --}}


<script>
    $(document).ready(function(){
      /*   $('.dd-expand').hide();
        $('.dd-collapse').on(); */
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

     

    /*         console.log('order', JSON.stringify(order));
            console.log('rootOrder', JSON.stringify(rootOrder));
            console.log('id', currentItem);
            console.log('parent_id', itemParent);
    */
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
                
        // console.log('dragEnd', event, item, source, destination, position);



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
                
                //console.log( $("body").find(".dd-dragel").html() );
                //console.log( $("body").find(".dd-dragel").find(".dd-item").length );
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

// --------------------- Show / Hide buttons for comments
   /*  $(document).ready(function(){
        $(".comment_toggle").on("mouseup",function(){
            $(this).parent().next("div").toggle();
            if( $(this).val() == "Hide" ){
                $(this).val("Show");
            }else{
                $(this).val("Hide")
            }
        });
    }); */
    
</script>

@endpush