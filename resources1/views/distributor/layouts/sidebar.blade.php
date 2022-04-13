<!-- Sidebar Menu -->
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


    <li class="nav-item">
      <a href="{{ url('distributor/dashboard') }}" class="nav-link {{ (url()->full() == url('distributor/dashboard'))? 'active':''}}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p class="text">Dashboard</p>
      </a>
    </li>

<li class="nav-item">
      <a href="{{ url('distributor/passbook') }}" class="nav-link {{ (url()->full() == url('distributor/passbook'))? 'active':''}}">
        <i class="fas fa-solid fa-book nav-icon"></i>
        <p>Passbook</p>
      </a>
    </li>

  <li class="nav-item">
      <a href="{{ url('distributor/outlets') }}" class="nav-link {{ (url()->full() == url('distributor/outlets'))? 'active':''}}">
        <i class=" nav-icon fas fa-store"></i>
        <p>Outlets</p>
      </a>
    </li>


    <li class="nav-item">
      <a href="{{ url('distributor/topup-list') }}" class="nav-link {{ (url()->full() == url('distributor/topup-list'))? 'active':''}}">
        <i class="fas fa-wallet nav-icon"></i>
        <p class="text">Topup Request</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url('distributor/a-transaction') }}" class="nav-link {{ (url()->full() == url('distributor/a-transaction'))? 'active':''}}">
        <!-- <i class="fas fa-solid fa-book nav-icon text-warning"></i> -->
        <i class="fas fa-money-bill-wave nav-icon"></i>
        <p>Transaction</p>
      </a>
    </li>




    <!-- <li class="nav-header">MULTI LEVEL EXAMPLE</li>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="fas fa-circle nav-icon"></i>
        <p>Level 1</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="nav-icon fas fa-circle"></i>
        <p>
          Level 1
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Level 2</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>
              Level 2
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Level 3</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Level 3</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Level 3</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Level 2</p>
          </a>
        </li>
      </ul>
    </li> -->

  </ul>
</nav>
<!-- /.sidebar-menu -->