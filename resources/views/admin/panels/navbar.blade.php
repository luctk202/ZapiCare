<nav
        class="header-navbar navbar navbar-expand-lg align-items-center {{ $configData['navbarClass'] }} navbar-light navbar-shadow {{ $configData['navbarColor'] }} {{ $configData['layoutWidth'] === 'boxed' && $configData['verticalMenuNavbarType'] === 'navbar-floating'? 'container-xxl': '' }}">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i
                                class="ficon"
                                data-feather="menu"></i></a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                                                                                         data-feather="{{ $configData['theme'] === 'dark' ? 'sun' : 'moon' }}"></i></a>
            </li>
            
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user"
                   href="javascript:void(0);"
                   data-bs-toggle="dropdown" aria-haspopup="true">
                    <div class="user-nav d-sm-flex d-none">
          <span class="user-name fw-bolder">
            @if (auth('admin')->check())
                  {{ auth('admin')->user()->name }}
              @endif
          </span>
                        <span class="user-status">
            {{ auth('admin')->user()->email }}
          </span>
                    </div>
                    <div class="avatar bg-light-primary">
                        <div class="avatar-content">{{ substr(auth('admin')->user()->name, 0, 1) }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    {{--<h6 class="dropdown-header">Manage Profile</h6>
                    <div class="dropdown-divider"></div>--}}
                    {{--<a class="dropdown-item"
                       href="{{ Route::has('profile.show') ? route('profile.show') : 'javascript:void(0)' }}">
                        <i class="me-50" data-feather="user"></i> Profile
                    </a>

                    <a class="dropdown-item" href="#">
                        <i class="me-50" data-feather="settings"></i> Settings
                    </a>--}}
                    
                    @if (auth('admin')->check())
                        <a class="dropdown-item" href="{{ route('admin.auth.change-password') }}">
                            <i class="me-50" data-feather="key"></i> Đổi mật khẩu
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.auth.logout') }}">
                            <i class="me-50" data-feather="power"></i> Logout
                        </a>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- END: Header-->
