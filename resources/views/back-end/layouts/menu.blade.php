@if(Auth::user()->type == 1)

    <?php
           $permissions = App\Admins::where('id', Auth::user()->id)->value('permissions');
              $split = explode(',', $permissions);
              $dashboard=0;
              $users=0;
              $onlineusers = 0;
              $networks = 0;
              $groups = 0;
              $branches = 0;
              $administration = 0;
              $packages = 0;
              $cards = 0;
              $settings = 0;
              $landingpage = 0;
              $campaign = 0;
              foreach($split as $permission){
                    if($permission == 'dashboard'){ $dashboard = 1; }
                    if($permission == 'users'){ $users = 1; }
                    if($permission == 'onlineusers'){ $onlineusers = 1; }
                    if($permission == 'networks'){ $networks = 1; }
                    if($permission == 'groups'){ $groups = 1; }
                    if($permission == 'branches'){ $branches = 1; }
                    if($permission == 'administration'){ $administration = 1; }
                    if($permission == 'packages'){ $packages = 1; }
                    if($permission == 'cards'){ $cards = 1; }
                    if($permission == 'settings'){ $settings = 1; }
                    if($permission == 'landingpage'){ $landingpage = 1; }
                    if($permission == 'campaign'){ $campaign = 1; }
              }
            ?>
    <!-- <li class="navigation-header"><a class="sidebar-mobile-main-toggle"><i class="icon-menu display-block"></i></a> </li> -->
    <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><center><i class="icon-menu display-block"></i></center></a></li>

    @if($dashboard == "1")
    <li class="{{ Request::path() ==  'admin' ? 'active' : ''  }}"><a href="{{ url('/admin') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
    @endif
    @if($users == "1")
    <li class="{{ Request::path() ==  'search' ? 'active' : ''  }}"><a href="{{ url('/search') }}"><i class="icon-users"></i> <span>Users</span></a></li>
    @endif
    @if($onlineusers == "1")
    <li class="{{ Request::path() ==  'activeusers' ? 'active' : ''  }}"><a href="{{ url('/activeusers') }}"><i class="icon-user"></i> <span>Online Users</span></a></li>
    @endif
    @if($networks == "1")
    <!-- <li class="{{ Request::path() ==  'network' ? 'active' : ''  }}"><a href="{{ url('/network') }}"><i class="icon-server"></i> <span>Networks</span></a></li> -->
    @endif
    @if($groups == "1")
    <li class="{{ Request::path() ==  'group' ? 'active' : ''  }}"><a href="{{ url('/group') }}"><i class="icon-grid"></i> <span>Groups</span></a></li>
    @endif
    @if($branches == "1")
    <li class="{{ Request::path() ==  'branches' ? 'active' : ''  }}"><a href="{{ url('/branches') }}"><i class="icon-tree6"></i> <span>Branches / Devices</span></a></li>
    @endif
    @if($administration == "1")
    <li class="{{ Request::path() ==  'admins' ? 'active' : ''  }}"><a href="{{ url('/admins') }}"><i class="icon-users"></i> <span>Admins</span></a></li>
    @endif
    @if($packages == "1" && App\Settings::where('type', 'commercial_enable')->value('state') == 1)
    <li class="{{ Request::path() ==  'packages' ? 'active' : ''  }}"><a href="{{ url('/packages') }}"><i class="icon-cash4"></i> <span>Packages</span></a></li>
    @endif
    @if($cards == "1" && App\Settings::where('type', 'commercial_enable')->value('state') == 1)
    <li class="{{ Request::path() ==  'cards' ? 'active' : ''  }}"><a href="{{ url('/cards') }}"><i class="icon-puzzle2"></i> <span>Cards</span></a></li>
    @endif
    @if($campaign == "1" && App\Settings::where('type', 'marketing_enable')->value('state') == 1)
      <li class="{{ Request::path() ==  'campaign' ? 'active' : ''  }}"><a href="{{ url('campaign') }}"><i class="icon-megaphone"></i> <span>Campaign</span></a></li>
    @endif
    @if($landingpage == "1")
    <li class="{{ Request::path() ==  'landings' ? 'active' : ''  }}"><a href="{{ url('/landings') }}"><i class="icon-insert-template"></i><span> Landing Page</span> </a></li>
    @endif
    @if( App\Branches::where('users_log_history_state', '1')->value('users_log_history_state') == 1 )
    <li class="{{ Request::path() ==  'visitedsites' ? 'active' : ''  }}"><a href="{{ url('visitedsites') }}"><i class="icon-shield2"></i> <span>Cyber Defense Operations Center</span></a></li>
    @endif
    @if($settings == "1")
        <li class="{{ Request::path() ==  'settings' ? 'active' : ''  }}"><a href="{{ url('/settings') }}"><i class="icon-gear"></i> <span>Settings</span></a></li>
    @endif
@else
<li class="{{ Request::path() ==  'admin' ? 'active' : ''  }}"><a href="{{ url('/admin') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
<li class="{{ Request::path() ==  'search' ? 'active' : ''  }}"><a href="{{ url('/search') }}"><i class="icon-users"></i> <span>Users</span></a></li>
@endif