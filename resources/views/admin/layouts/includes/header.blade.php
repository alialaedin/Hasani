<div class="app-header header pr-0">
  <div class="container-fluid">
    <div class="d-flex">
      <div class="d-flex order-lg-2 my-auto mr-auto">
        <div class="dropdown header-fullscreen">
          <a class="nav-link icon full-screen-link">
            <i class="feather feather-maximize fullscreen-button fullscreen header-icons"></i>
            <i class="feather feather-minimize fullscreen-button exit-fullscreen header-icons"></i>
          </a>
        </div>
        <div>
          <a class="nav-link icon" data-toggle="sidebar-right" data-target=".sidebar-right">
            <i class="feather feather-bell header-icon"></i>
          </a>
        </div>
        <div>
          <button
            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
            class="nav-link icon border-0"
            data-toggle="tooltip"
            data-original-title="خروج">
            <i class="feather feather-log-out header-icon"></i>
          </button>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
