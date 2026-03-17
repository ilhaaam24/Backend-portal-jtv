<nav
class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
id="layout-navbar">
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
  <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
    <i class="mdi mdi-menu mdi-24px"></i>
  </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
 
  <ul class="navbar-nav flex-row align-items-center ms-auto">
   

    <li class="nav-item me-1 me-xl-0">
      <a class="nav-link btn btn-text-secondary rounded-pill btn-icon hide-arrow waves-effect waves-light" 
        href="{{ config('jp.path_url_fe')}}" target="_blank">
        <i class="mdi mdi-monitor-screenshot mdi-24px"></i>
      </a>
    </li>


    <!-- User -->
    <?php 
  
    $img = Auth::user()->pengguna->gambar_pengguna;
    $filepath = public_path('assets/foto-profil/').$img;
    $path_img = config('jp.path_url_be').config('jp.path_img_profile').$img;
    $local_img = url('assets/foto-profil/'.$img);   

   
    if (file_exists($filepath) && $img!='') {
        $filepath_img=  $local_img;
    }else{
            $filepath_img= asset(config('jp.path_url_no_img'));
    }

    ?>
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="{{  $filepath_img }}" alt class="w-px-40 h-auto rounded-circle" />
        </div>
      </a>
    
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="pages-account-settings-account.html">
            <div class="d-flex">
              <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
               
                  <img src="{{ $filepath_img }}" alt class="w-px-40 h-auto rounded-circle" />
                </div>
              </div>
              <div class="flex-grow-1">
                <span class="fw-semibold d-block">
                
                  {{ Auth::user()->pengguna->nama_pengguna }}</span>
                <small class="text-muted">{{ Auth::user()->getRoleNames()[0] }}</small>
              </div>
            </div>
          </a>
        </li>
      
       
        <li>
          <div class="dropdown-divider"></div>
        </li>

       
        <li>
          <a class="dropdown-item" href="{{ route('logout') }}">
            <i class="mdi mdi-logout me-2"></i>
            <span class="align-middle">Log Out</span>
          </a>
        </li>
      </ul>
    </li>
    <!--/ User -->
  </ul>
</div>

<!-- Search Small Screens -->

</nav>