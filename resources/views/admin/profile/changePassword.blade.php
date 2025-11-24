@extends('admin.layouts.master')
@section('title', 'Change Password')
@section('content')
    <section>
		<div class="row">
			<div class="col-md-6 mx-auto">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Change Password</h5>
						<form data-href="{{ route('profile.changePassword.update') }}" data-redirect="{{ route('profile.changePassword.index') }}" class="mt-4 in_page_ajax_form_page_reload">
							@csrf
							<div class="mb-3">
								<label for="current_password" class="form-label">Current Password<b class="text-danger">*</b></label>
								<input type="password" class="form-control" id="current_password" name="current_password" required>
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">New Password<b class="text-danger">*</b></label>
								<input type="password" class="form-control" id="password" name="password" required>
							</div>
							<div class="mb-3">
								<label for="password_confirmation" class="form-label">Confirm New Password<b class="text-danger">*</b></label>
								<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
							</div>
							<div class="mt-4 d-flex justify-content-end">
								<button type="submit" class="btn btn-primary">Change Password</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
    </section>
@endsection