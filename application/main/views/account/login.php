<div class="row justify-content-md-center">
	<div class="col-md-4">
		<h3>Login</h3>
		<form id="loginForm" action="<?php echo site_url('account/login') ?>" autocomplete="off" >
			<div id="error_message_box" class="hide alert alert-danger text-danger" role="alert"></div>
			<div class="form-group mb-1">
				<label class="mb-0">Email Address</label>
				<input type="text" name="username" id="username" class="form-control" placeholder="Email Address">
			</div>
			<div class="form-group mb-4">
				<label class="mb-0">Password</label>
				<input type="password" name="password" id="password" class="form-control" placeholder="Password">
			</div>
			<div class="form-group mb-1">
				<button type="submit" class="btn btn-sm btn-danger btn-block">
					<i class="fa fa-sign-in"></i> <strong>Log in</strong>
				</button>
			</div>
		</form>
	</div>
</div>