<div class="navbar">
    <div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="/"><?=$this->ConfigHelper->read('site_title'); ?></a>
			<!--Think I may have to add dropdown to bootstrap-->
			<!--<div class="btn-group pull-right">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i> Username
						<span class="caret"></span>
					</a>
				<ul class="dropdown-menu">
					<li><a href="#">Profile</a></li>
					<li class="divider"></li>
					<li><a href="#">Sign Out</a></li>
				</ul>
			</div>-->
			<div class="nav-collapse">
				<ul class="nav">
					<li class="">
						<a href="/posts/add/">Add Post</a>
					</li>
					<li class="">
						<a href="/users/add/">Add User</a>
					</li>
					<li class="">
						<a href="/users/">Users</a>
					</li>
					<li class="">
						<a href="/login">Login</a>
					</li>
					<li class="">
						<a href="/logout">Logout</a>
					</li>
					<li class="">
						<a href="/docs">Docs</a>
					</li>
				</ul>
			</div>
		</div>
    </div>
</div>
