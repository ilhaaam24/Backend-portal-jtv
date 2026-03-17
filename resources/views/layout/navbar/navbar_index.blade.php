@extends('layouts.materialize')
@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

<style>
    .swal2-container {
    z-index: 10000;
    }

    .dd {
    position: relative;
    display: block;
    margin: 0;
    padding: 0;
    /* max-width:600px; */
    list-style: none;
    font-size: 13px;
    line-height: 20px;
    margin-left: 45px;
    }

    .dd-list {
        display: block;
        position: relative;
        margin: 10;
        padding: 0;
        list-style: none
    }

    .dd-list .dd-list {
        padding-left: 30px
    }

    .dd-empty,.dd-item,.dd-placeholder {
        display: block;
        position: relative;
        margin: 0;
        padding: 0;
        min-height: 20px;
        font-size: 13px;
        line-height: 20px
    }

    .dd-handle {
        display: block;
        height: 30px;
        margin: 5px 0;
        padding: 5px 10px;
        color: #333;
        text-decoration: none;
        font-weight: 700;
        border: 1px solid #ccc;
        background: #fafafa;
        border-radius: 3px;
        box-sizing: border-box
    }

    .dd-handle:hover {
        color: #2ea8e5;
        background: #fff
    }

    .dd-item>button {
        position: relative;
        cursor: pointer;
        float: left;
        width: 25px;
        height: 20px;
        margin: 5px 0;
        padding: 0;
        text-indent: 100%;
        white-space: nowrap;
        overflow: hidden;
        border: 0;
        background: 0 0;
        font-size: 12px;
        line-height: 1;
        text-align: center;
        font-weight: 700
    }

    .dd-item>button:before {
        display: block;
        position: absolute;
        width: 100%;
        text-align: center;
        text-indent: 0
    }

    .dd-item>button.dd-expand:before {
        content: '+'
    }

    .dd-item>button.dd-collapse:before {
        content: '-'
    }

    .dd-expand {
        display: none
    }

    .dd-collapsed .dd-collapse,.dd-collapsed .dd-list {
        display: none
    }

    .dd-collapsed .dd-expand {
        display: block
    }

    .dd-empty,.dd-placeholder {
        margin: 5px 0;
        padding: 0;
        min-height: 30px;
        background: #f2fbff;
        border: 1px dashed #b6bcbf;
        box-sizing: border-box;
        -moz-box-sizing: border-box
    }

    .dd-empty {
        border: 1px dashed #bbb;
        min-height: 100px;
        background-color: #e5e5e5;
        background-size: 60px 60px;
        background-position: 0 0,30px 30px
    }

    .dd-dragel {
        position: absolute;
        pointer-events: none;
        z-index: 9999
    }

    .dd-dragel>.dd-item .dd-handle {
        margin-top: 0
    }

    .dd-dragel .dd-handle {
        box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1)
    }

    .dd-nochildren .dd-placeholder {
        display: none
    }

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
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between">
        <h4 class="fw-bold py-1 mb-3">
            <span class="text-muted fw-light">Layout /</span>
            Navbar
        </h4>
        <h4 class="fw-bold py-1 mb-3">
           <a href="#newModal" class="btn btn-md btn-primary waves-effect pull-right" data-bs-toggle="modal">
            <i class="fas fa-plus"></i> &nbsp; Create Navbar item
            </a>
        </h4>
    </div>

  {{-- menu --}}
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card">
              <div class="card-body">
                  <div class="header-title">
                      <h5>Draggable Navbar</h5>
                    
                  </div>

                  {{-- new --}}
                  <div class="row mt-4 mb-4">
                    <menu id="nestable-menu">
                        <button type="button" class="btn btn-sm btn-info" data-action="expand-all">Expand All</button>
                        <button type="button" class="btn btn-sm btn-info" data-action="collapse-all">Collapse All</button>
                    </menu>
                      <div class="col-md-8">
                        <p id="success-indicator" style="display:none; margin-right: 10px;">
                            <i class="fas fa-check-circle"></i> Navbar order has been saved
                        </p>

                          <div class="dd nestable-with-handle" id="nestable">
                          
                              {{-- {!! $navigations !!} --}}
                          </div>

                        
                    
                          <textarea id="Menu-output" class="form-control" hidden></textarea>
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
                  <div class="modal fade" id="newModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><span id="title_form">Create</span> Navbar item</h5>
                            <button
                            type="button"
                            class="btn-close btn btn-light p-2 rounded-circle position-absolute"
                            data-bs-dismiss="modal"
                            aria-label="Close" style="right: 30px;z-index: 5;"></button>
                        </div>

                            <div class="modal-body" style="background:#f7f7f9;">
                    
                                <form id="form_navbar" action="" autocomplete="off">

                                    <div class="form-floating form-floating-outline mb-3 mt-3">
                                        <select class="select2 form-select" 
                                            id="id_parent" name="id_parent">
                                            <option value="">-- PARENT NAVBAR --</option>                                                  
                                            @foreach ($id_parent as $data)
                                            <option value="{{$data->id_navbar}}"
                                                {{-- @if ($status_form === "edit_form")
                                                    {{ $data->id_navbar == $item->id_menu_berita ? 'selected' : '' }}
                                                @endif --}}
                                                >
                                                {{$data->judul_navbar}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <label for="id_parent"> Parend Id</label>
                                    </div>

                                    <div class="form-floating form-floating-outline mb-3 mt-3">
                                        <input type="text" class="form-control" id="judul_navbar" name="judul_navbar" 
                                        placeholder=" " aria-describedby="floatingNavbarName">
                                        <label for="Judul Navbar">Judul Navbar</label>
                                        <div id="floatingNavbarName" class="form-text">
                                        </div>
                                    </div>


                                    <div class="form-floating form-floating-outline mb-3 mt-3" hidden>
                                        <input type="text" class="form-control" id="navbar_urut" name="navbar_urut" 
                                        placeholder=" " aria-describedby="floatingNavbarUrut">
                                        <label for="Navbar Urut">Navbar Urut</label>
                                        <div id="floatingNavbarUrut" class="form-text">
                                        </div>
                                    </div>

                                    <div class="form-floating form-floating-outline mb-3 mt-3" hidden>
                                        <input type="text" class="form-control" id="status_navbar" 
                                        name="status_navbar" placeholder=" " 
                                        aria-describedby="floatingNavbarStatus">
                                        <label for="Status">Status</label>
                                        <div id="floatingNavbarStatus" class="form-text">
                                        </div>
                                    </div>

                                    <div class="form-floating form-floating-outline mb-3 mt-3" hidden>
                                        <input type="text" class="form-control" id="id_navbar" name="id_navbar" 
                                        placeholder=" " aria-describedby="floatingIDNavbar">
                                        <label for="ID Navbar">ID Navbar</label>
                                        <div id="floatingIDNavbar" class="form-text">
                                        </div>
                                    </div>

                                </form>
                                            
                                        
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="add-navbar"><span id="aksi_submit">Create</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                  {{-- new --}}
              </div>
          </div>
      </div>
  </div>

</div>
  
@endsection

@push('js')
<script src="{{asset('assets/vendor/libs/Nestable2-master/jquery.nestable2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/Nestable2-master/dist/sortable-nestable.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.select2').select2({
            dropdownParent: $('#newModal')
        });

        //clear disabled serialize form function
        $.fn.serializeIncludeDisabled = function () {
            let disabled = this.find(":input:disabled").removeAttr("disabled");
            let serialized = this.serialize();
            disabled.attr("disabled", "disabled");
            return serialized;
        };
        nestableNavbar();
        //load nestable2
        function nestableNavbar(){
            $('#nestable').html('');
               $.ajax({  
               url:`{{ route("nestableNavbar") }}`,
               method:'get',  
               data: { 
                   _token: $('meta[name="csrf-token"]').attr('content')} ,  
               dataType : "JSON",  
                   success:function(data)  
                   {  
                   if (data) {  
                            setTimeout(function(){// wait for 5 secs(2)
                                $('#nestable').html(data.navbar); // then reload the page.(3)
                            }, 100); 
                          
                       } 
                          
                   }
               });  
           }

        $('#newModal').on('hidden.bs.modal', function () {
            clearForm();
        });

        function clearForm(){
            $("#form_navbar")[0].reset();
            $('#id_parent').trigger('change');
            $("#aksi_submit").text("Create");
            $("#title_form").text("Create");
        }

        $(document).on('change', '#id_parent', function () {
            var id = $(".select2 option:selected").val();
            var status_form = $('#status_navbar').val();
                if(status_form==''){
                    getLastNavbar(id);
                }
               
            });
        
            $(document).on('click', '.editNavbar', function () {
                $("#aksi_submit").text("Update");
                $("#title_form").text("Update");
                var id = $(this).data("id");
                $.ajax({  
                url:`{{ route("getEditNavbar") }}`,
                method:'POST',  
                data: { 
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id : id} ,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                    if (data.status == "success") {  
                            $('#id_navbar').val(data.hasil.id_navbar);
                            $('#judul_navbar').val(data.hasil.judul_navbar);
                            $('#navbar_urut').val(data.hasil.no_urut);
                            $('#status_navbar').val('edit');
                            $('#id_parent').val(data.hasil.id_parent);
                            $('#id_parent').trigger('change');
                        } 
                           
                    }
                });  
            });

            $(document).on('click', '.deleteNavbar', function () {
                var id = $(this).data("id");
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success'
                },
                buttonsStyling: false
                })
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.isConfirmed) {
                        deleteNavbar(id);
                    
                        }else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                            )
                        }
                    })
               
            });
            
        function getLastNavbar(id){
                $.ajax({  
                url:`{{ route("getLastNavbar") }}`,
                method:'POST',  
                data: { 
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id : id} ,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                    if (data.status == "success") {  
                            $('#navbar_urut').val(data.hasil);
                        } 
                    }
                });  
            }

            $(document).on('click', '#add-navbar', function () {
               var status_navbar =  $('#status_navbar').val();
               if(status_navbar=='edit'){
                    save_edit_nav();
               }else{
                    save_new_nav();
               }
               
            });

            //SAVE new Navigasi
            function save_new_nav(){
                var formdata = $('#form_navbar').serializeIncludeDisabled();
                $.ajax({  
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url:`{{ route("navbar.store") }}`,
                method:'POST',  
                data: formdata,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                    if (data.status == "success") {  
                        Swal.fire('Saved!',  data.message + ' !','success'); 
                     
                        $('#newModal').modal('hide');
                        nestableNavbar(); 
                        } 
                            else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                    }
                });  
            }

            function save_edit_nav(){
                var formdata = $('#form_navbar').serializeIncludeDisabled();
                $.ajax({  
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url:`{{ route("navbar.storeUpdate") }}`,
                method:'POST',  
                data: formdata,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                    if (data.status == "success") {  
                        Swal.fire('Saved!',  data.message + ' !','success'); 
                        $('#newModal').modal('hide');
                        nestableNavbar(); 
                        } 
                            else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                    }
                });  
            }

            function deleteNavbar(id){
               $.ajax({  
               url:`{{ route("deleteNavbar") }}`,
               method:'POST',  
               data: { 
                   _token: $('meta[name="csrf-token"]').attr('content'),
                   id : id} ,  
               dataType : "JSON",  
                   success:function(data)  
                   {  
                    if (data.status == "success") {  
                        Swal.fire('Deleted!', 'Your file has been deleted.','success'); 
                        nestableNavbar(); 
                        } 
                            else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                   }
               });  
           }


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

        console.log(order.length);
        if (order.length === 0){
            var rootOrder = new Array();
            $("li[data-id='"+currentItem +"']").parent().children().each(function(index,elem) {
                rootOrder[index] = $(elem).attr('data-id');
             });

            /* $("#nestable > ol > li").each(function(index,elem) {
            rootOrder[index] = $(elem).attr('data-id');
            }); */
        }

            console.log('order', JSON.stringify(order));
            console.log('rootOrder', JSON.stringify(rootOrder));
            console.log('id', currentItem);
            console.log('id_parent', itemParent);
   
        let serial = $('.dd').nestable('serialize');
        var datastring = JSON.stringify(serial);
        let asNestedSet = $('.dd').nestable('asNestedSet');

       
        var token = $('form').find( 'input[name=_token]' ).val();    
        $.post('{{url("reorder/navbar/")}}',
                    {
                        source : currentItem,
                        destination: itemParent,
                        order:JSON.stringify(order),
                        rootOrder:JSON.stringify(rootOrder),
                        // list :datastring,
                        _token: $('meta[name="csrf-token"]').attr('content')
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
        // console.log(hovering);
    });


    $(".dd").on("mousedown",function(){
       /*  console.log("");
        console.log("--------------------------- Mousedown on an element"); */

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
       /*  console.log( "TO: "+hovering );
        console.log(""); */
        moveEl.to=hovering;
    }); 
});

    
</script>

@endpush