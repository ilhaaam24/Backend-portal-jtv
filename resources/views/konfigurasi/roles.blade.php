@extends('layouts.materialize')
@push('css')
<style>
  .bg-parent{
    background-color: #496359c2 !important;
  }
  .span-badge{
    text-transform: uppercase;
    display: flex; 
    align-items: center;
  }
  .form-check-input:checked {
  background-color: #2fba53!important;
  border-color: #2fba40!important;
}
  .swal2-container {
        z-index: 10000;
        }
  .roleborder{
    border: 2px solid #978d8d2e;
    width: auto;
    padding: 15px;
    border-radius: 0.5rem !important;
  }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-medium mb-1">Roles List</h4>
    <p class="mb-4">
      A role provided access to predefined menus and features so that depending on assigned role an
      administrator can have access to what user needs.
    </p>
    <!-- Role cards -->
    <div class="row g-4">
    {{-- new --}}
    @foreach ($roles as $item)
        <div class="col-xl-4 col-lg-6 col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between mb-2">
                <h6 class="fw-normal">Total {{ $item->users_count }} User</h6>
                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                  @foreach ($item->users->slice(0, 3) as $u)
                    @php 
                      $img = $u->pengguna->gambar_pengguna; 
                      $new_img = asset("assets/foto-profil/$img");

                      if (Storage::exists("foto-profil/$img") && $img != '') {
                        $new_img_usr = asset("assets/foto-profil/$img");
                      }else{
                        $new_img_usr = asset(config('jp.path_url_no_img'));
                      }
                    @endphp

                    @if ($item->users_count > 2)
                      <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                        title="{{$u->name}}" class="avatar pull-up">
                        <img class="rounded-circle" src="{{ $new_img_usr }}" alt="Avatar" style="border:2px solid #eae0ebb8;"/>
                      </li>
                    @else
                      <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                        data-bs-placement="top" title="{{$u->name}}" class="avatar pull-up">
                        <img class="rounded-circle" src="{{ $new_img_usr }}" alt="Avatar"  style="border: 2px solid #eae0ebb8;"/>
                      </li>
                    @endif
                  @endforeach
                  
                  @if ($item->users_count > 2)
                  <li class="avatar">
                    <span class="avatar-initial rounded-circle pull-up bg-lighter text-body"
                      data-bs-toggle="tooltip" data-bs-placement="bottom" title="more">+</span>
                  </li>
                  @endif
                </ul>
              </div>

              <div class="d-flex justify-content-between align-items-end">
                <div class="role-heading">
                  <h4 class="mb-1 text-body">{{ $item->name }}</h4>
                </div>
                <button data-id="{{ $item->id }}" class="role-edit-modal btn btn-info btn-sm">
                  <i class="mdi mdi-pencil mdi-12px"></i>edit
                </button>
              </div>
            </div>
          </div>
        </div>
      @endforeach
      {{-- end --}}

      <h4 class="fw-medium mb-1 mt-5">Total users with their roles</h4>
      <p class="mb-0 mt-1">Find all of your company’s administrator accounts and their associate roles.</p>
      <div class="col-12">
        <!-- Role Table -->
        <div class="card">
          <div class="card-body">
          @livewire('pengguna-table')
          </div>
        </div>
        <!--/ Role Table -->
      </div>
    </div>
    <!--/ Role cards -->

    <!-- Add Role Modal -->
    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
          <button type="button" data-bs-dismiss="modal"
          class="btn-close btn btn-light p-3 rounded-circle position-absolute"
          aria-label="Close" style="right: 30px;z-index: 5;"></button>
          <div class="modal-body p-0">
            <!-- Add role form -->
            <form id="addRoleForm" class="row g-3" onsubmit="return false">
              <div class="col-12 col-md-12">
                <div class="card mb-3">
                  <div class="card-header d-flex justify-content-between">
                    <h5>Role Menu</h5>
                  </div>
                  <div class="card-body" id='form_role_menu'>
                    <div class="form-floating form-floating-outline mb-3">
                      <select  id="id_role" name="id_role" class="select2modal form-select">
                          <option value="0">-- Select Role --</option>
                          @foreach ($roles as $data_role)
                          <option value="{{$data_role->id}}"
                            {{ Auth::user()->role->id == $data_role->id ? 'selected': '';}} >
                              {{$data_role->name}}
                          </option>
                          @endforeach
                      </select>
                      <label for="Role">Role</label>
                  </div>

                    @foreach($navigation as $menu)
                    <div class="py-13 mb-2 roleborder">
                    @if($menu->submenus->count() > 0) 
                    
                    <div class="form-check me-3 me-lg-5" style="max-width:500px;">
                      <div class="parent">
                          <input class="form-check-input navigation_role" type="checkbox" id="nav_id_{{ $menu->id}}" 
                          @foreach (getMenusRole() as $val)
                          {{  $val->navigation_id == $menu->id ? 'checked' : '' }}
                          data-id-nav="{{ $menu->id}}"
                          @endforeach
                          name="rolemenu[]" value="{{ $menu->id}}"/>
                          <span class="badge bg-parent text-nowrap fw-semibold mb-2 span-badge justify-content-between"> {{ strtolower($menu->name) }} 
                            <i class="mdi mdi-format-list-bulleted" data-bs-toggle="collapse" data-bs-target="#submenu_{{ $menu->id}}"></i></span>
                         
                          {{-- <div class="text-nowrap fw-semibold mb-2" style="text-transform: uppercase" data-i18n="{{ $menu->name}}">{{ strtolower($menu->name) }}</div> --}}
                    </div>
                    <ul class="mb-2 collapse show submenu_data" id="submenu_{{ $menu->id}}">
                      @foreach ($menu->submenus as $submenu)
                        <li>
                          <div class="submenu_item">
                            <input class="form-check-input" type="checkbox" id="nav_id_{{ $submenu->id}}" 
                            data-id-nav="{{ $submenu->id}}" name="rolemenu[]"  value="{{ $submenu->id}}"
                            @foreach (getMenusRole() as $val1)
                            @if ($val1->name == $menu->name)
                            data-id-role="{{ $val1->role_id }}"
                                @foreach ($val1->subMenus as $value)
                                {{ $value->name== $submenu->name ? $value->name.' checked ':'';}}
                                @endforeach 
                            @endif
                            @endforeach
                            />
                            @if($submenu->permissions->count() > 0)
                            <span class="badge bg-info text-nowrap fw-semibold mb-2 span-badge justify-content-between"> {{ strtolower($submenu->name) }} <i class="mdi mdi-format-list-bulleted collapse_permission" data-bs-toggle="collapse" data-bs-target="#permission_{{ $submenu->id}}"></i></span>
                            @else
                            <span class="badge bg-info text-nowrap fw-semibold mb-2 span-badge justify-content-between"> {{ strtolower($submenu->name) }}</span>
                            @endif
                          </div>
                          <div class="collapse show" id="permission_{{ $submenu->id}}">
                            <ul class="mb-2 permission_data">
                          @foreach ($submenu->permissions as $permissions)
                         
                            <li class="permission_item">
                              <input class="form-check-input" type="checkbox" id="permissions_id_{{ $permissions->id}}" 
                              data-id-permissions="{{ $permissions->id}}" name="permissions_id[]"  
                              value="{{ $permissions->id}}">{{  $permissions->name }}
                            </li>
                            @endforeach
                          </ul>
                        </div>
                        </li>
                     
                      @endforeach
                    </ul>
                    </div>
                   
                    @else
                    <div class="form-check me-3 me-lg-5">
                        <input class="form-check-input" type="checkbox" id="nav_id_{{ $menu->id}}" 
                        value="{{ $menu->id}}" name="rolemenu[]"/>
                        <div class="text-nowrap fw-semibold mb-2" style="text-transform: uppercase" data-i18n="{{ $menu->name }}"><span class="badge bg-success">{{ $menu->name }}</span></div>
                    </div>                     
                    @endif
                  </div>
                  @endforeach
              </div>
              </div>

              <div class="col-12 text-center">
                <button type="button" class="btn btn-primary me-sm-3 me-1" id="submit_role_menu">Submit</button>
                <button type="reset" class="btn btn-outline-secondary"
                  data-bs-dismiss="modal" aria-label="Close"> Cancel
                </button>
              </div>
            </form>
            <!--/ Add role form -->
          </div>
        </div>
      </div>
    </div>
    <!--/ Add Role Modal -->
  </div>

@endsection
@push('js')
  <script>
    $(document).ready(function () {
      $('.select2').select2();
      $(".select2modal").select2({ dropdownParent: $("#addRoleModal") });
    });

    $('.parent input').on('change',function() {
        var _parent=$(this);
        var nextli=$(this).parent().next().children().children();

        if(_parent.prop('checked')){
            console.log('parent checked');
            nextli.each(function(){
              $(this).children().prop('checked',true).trigger('change');
            });
          }
          else{
            console.log('parent un checked');
            nextli.each(function(){
              $(this).children().prop('checked',false).trigger('change');
            });
          }
        });

    $('.submenu_item input').on('change',function() {
      var _parent=$(this);
      var nextli=$(this).parent().next().children().children();
      cek_parent(_parent, nextli);
    });

      function cek_parent(_parent, nextli){
      if(_parent.prop('checked')){
            console.log('submenu checked');
            nextli.each(function(){
              $(this).children().prop('checked',true);
              // $(this).children().style.display ="block" ;
            });
            
          }
          else{
            console.log('submenu un checked');
            nextli.each(function(){
              $(this).children().prop('checked',false);
              // $(this).children().style.display ="none" ;
            });
          
          }
      }

     /*  $(".collapse_permission").on('click',function(){
        var ths=$(this);
        var parentinput=ths.closest('div').next().children();
        console.log(parentinput);
        // parentinput.display('checked',true);
      });
       */
      $(".permission_data input").on('click',function(){
        var ths=$(this);
        var parentinput=ths.closest('div').prev().children();
        var sibblingsli=ths.closest('ul').find('li');
        
        if(ths.prop('checked')){
          console.log('child checked');
          parentinput.prop('checked',true);
        }
        else{
          console.log('child unchecked');
            var status=true;
          sibblingsli.each(function(){
            console.log('sibb');
            if($(this).children().prop('checked')) status=false;
          });
            if(status) parentinput.prop('checked',false);
        }
      });

    //Select Role and Then Display Role Menu
    $(document).on('change', '#id_role', function(){
      let val = $(this).val();
        $.ajax({
          url: "{{url('getEditRole')}}",
          type: "POST",
          data: { role_id: val,
                  _token: '{{csrf_token()}}' },
          dataType: 'json',
          success: function (res) {
            if(res.status == "success") {  
              $(':checkbox').each(function() {
                this.checked = false;
              });

              $('#form_role_menu').find(':checkbox[name="rolemenu[]"]').each(function () {
                $(this).prop("checked", ($.inArray(+$(this).val(), res.list_nav_id)) != -1);
              }); 

              $('#form_role_menu').find(':checkbox[name="permissions_id[]"]').each(function () {
                $(this).prop("checked", ($.inArray(+$(this).val(), res.get_permissions)) != -1);
              }); 
            }else if(res.status == "error"){
                  $(':checkbox').each(function() {
                    this.checked = false;
              });
            }
          }
        });


    });

    // Get Submit data Role Menu Checked
    $(document).on('click', '#submit_role_menu', function(){
      var id_role = $('#id_role').val();
      $data = $(':checkbox[name="rolemenu[]"]:checked').serialize();
      $data = $(':checkbox[name="permissions_id[]"]:checked').serialize();

      var rolemenu = new Array();
      $(':checkbox[name="rolemenu[]"]:checked').each(function(){
        rolemenu.push($(this).val());
      });

      var rolepermission = new Array();
      $(':checkbox[name="permissions_id[]"]:checked').each(function(){
        rolepermission.push($(this).val());
      });

      // var dataString =  rolemenu;
      $.ajax({
          type: "POST",
          url: "{{url('SubmitRoleMenu')}}",
          data: {
                  nav_id: rolemenu,
                  permission_id: rolepermission,
                  role_id: id_role,
                  _token: '{{csrf_token()}}'
              },
          cache: false,
          success: function (res) {
                if (res.status == "success") {  
                  Swal.fire('Success!',  res.message + ' !','success');  
                  $(':checkbox').each(function() {
                    this.checked = false;
                  });

                  $('#form_role_menu').find(':checkbox[name="rolemenu[]"]').each(function () {
                    $(this).prop("checked", ($.inArray(+$(this).val(), res.list_nav_id)) != -1);
                  }); 

                  $('#form_role_menu').find(':checkbox[name="permissions_id[]"]').each(function () {
                    $(this).prop("checked", ($.inArray(+$(this).val(), res.list_permission_id)) != -1);
                  }); 
                }else if(res.status == "error"){
                  Swal.fire('Warning!',  res.message + ' !','warning');  
                }
              }
        });
    });

    /* ---------------------------------------------- */
    $(document).on('click', '.role-edit-modal', function () {
      $('#addRoleModal ').modal('show');  
      let id_roles = $(this).data("id");
      $('#id_role').val(id_roles).trigger('change');
      $('#id_role').prop('disabled', true);
    });

    $('#addRoleModal').on('hidden.bs.modal', function () {
      $('#id_role').prop('disabled', false);
    });
</script>
@endpush