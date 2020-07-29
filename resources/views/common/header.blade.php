<!-- Header -->
<div class="header">
    <!-- Logo -->
    <div class="header-left">
        <a href="/" class="logo">
            <h3>Админ панель</h3>
        </a>
    </div>
    <!-- /Logo -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>
    <!-- Header Menu -->
    <ul class="nav user-menu">
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
						<span class="user-img"><img src="{{ asset('storage/upload/images/'. auth()->user()->photo) }}" alt="">
							<span class="status online"></span>
						</span>
                <span>{{ auth()->user()->name }}</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->
</div>
<!-- /Header -->
