      <?php
        $full_name = getFullName(Auth::user()->user_id);
        $img_name = getProfileImage(Auth::user()->user_id);
        $imgSrc = '/images/profile_img/default_profile_img.png';

        if($img_name != "" && file_exists(public_path().'/uploads/profile_images/'.$img_name)) $imgSrc = '/uploads/profile_images/'.$img_name;
      ?>
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="user-wrapper">
                <div class="profile-image">
                  <img src="{{ URL::to('/') }}<?= $imgSrc; ?>" alt="profile image">
                </div>
                <div class="text-wrapper">
                  <p class="profile-name">{{ $full_name }}</p>
                  <div>
                    <?php
                      $badge = "Guest Board";
                      $imgSrc = '';
                      if (\Auth::user()->user_on_board == 1){
                        $badge = "Golden Board";
                        $imgSrc = 'dollar-coin.png';
                      }elseif (\Auth::user()->user_on_board == 2){
                        $badge = "Diamond Board";
                        $imgSrc = 'diamond.png';
                      }
                    ?>
                    <small class="designation text-muted">{{ $badge }}</small>
                    <!-- <span class="status-indicator online"></span> -->
                    @if($imgSrc != "")
                      <img src="{{ URL::to('/') }}<?= '/images/'.$imgSrc; ?>" width="15px" alt="badge">
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </li>
          @if(Auth::user()->user_status == 1)
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/dashboard') }}">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            @if(Auth::user()->user_role == 1)
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-account-key"></i>
                <span class="menu-title">User Management</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/active-users') }}">Active Users List</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/pending-users') }}">Pending Users List</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/withdrawal-requests') }}">
                <i class="menu-icon mdi mdi-transfer"></i>
                <span class="menu-title">All Withdrawal Requests</span>
              </a>
            </li>
            @endif
            @if(Auth::user()->user_role == 2)
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/joining') }}">
                <i class="menu-icon mdi mdi-share-variant"></i>
                <span class="menu-title">Joining</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/refered-users') }}">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">Refered Users</span>
              </a>
            </li>
            @endif
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/earning-statement') }}">
                <i class="menu-icon mdi mdi-currency-usd"></i>
                <span class="menu-title">Earning Statement</span>
              </a>
            </li>
            @if(Auth::user()->user_role == 2)
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/balance-withdrawal') }}">
                <i class="menu-icon mdi mdi-cash-multiple"></i>
                <span class="menu-title">Balance Withdrawal</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/withdrawal-request') }}">
                <i class="menu-icon mdi mdi-view-list"></i>
                <span class="menu-title">Withdrawal Request</span>
              </a>
            </li>
            @endif
          @else
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/confirm-payment') }}">
                <i class="menu-icon mdi mdi-account-check"></i>
                <span class="menu-title">Confirm Payment</span>
              </a>
            </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="menu-icon mdi mdi-logout"></i>
              <span class="menu-title">{{ __('Logout') }}</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="menu-icon mdi mdi-restart"></i>
              <span class="menu-title">Earning</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/blank-page.html"> Refer Earn </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/login.html"> Login </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/register.html"> Register </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/error-404.html"> 404 </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/error-500.html"> 500 </a>
                </li>
              </ul>
            </div>
          </li> -->
        </ul>
      </nav>
      <!-- partial -->