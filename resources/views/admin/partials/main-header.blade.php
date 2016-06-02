    <header class="main-header">
        <a href="/admin" class="logo"><b>CMS</b>able</a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- img src="/cmsable/AdminLTE/img/user2-160x160.jpg" class="user-image" alt="User Image"/ -->
                  <span class="hidden-xs">{{ Auth::user()->email }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="{{ $currentUserAvatar or '/cmsable/img/user-128px.png' }}" class="img-circle" alt="User Image" />
                    <p>
                      {{ Scaffold::shortName(Auth::user()) }}
                      @if(Auth::user()->last_login) <small>Letzter Login {{ Auth::user()->last_login->format('d.m.Y H:i') }}</small> @endif
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <!-- li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li -->
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="{{ URL::route('users.show', [Auth::user()->getAuthId()]) }}" class="btn btn-default btn-flat">Profil</a>
                    </div>
                    <div class="pull-right">
                      <a href="/session/destroy" class="btn btn-default btn-flat">Logout</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>