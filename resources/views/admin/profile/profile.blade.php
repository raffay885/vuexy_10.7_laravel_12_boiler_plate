@extends('admin.layouts.master')
@section('title', 'Profile')
@section('content')
    <section>
        <div class="row">
            <div class="col-md-6 mx-auto">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Profile</h5>
						<form data-href="{{ route('profile.update') }}" data-redirect="{{ route('profile.index') }}" class="mt-4 in_page_ajax_form_page_reload">
							@csrf
							<div class="mb-3">
								<label for="first_name" class="form-label">First Name<b class="text-danger">*</b></label>
								<input type="text" class="form-control" id="first_name" name="first_name" value="{{ auth()->user()->first_name }}" required>
							</div>
							<div class="mb-3">
								<label for="last_name" class="form-label">Last Name<b class="text-danger">*</b></label>
								<input type="text" class="form-control" id="last_name" name="last_name" value="{{ auth()->user()->last_name }}" required>
							</div>
							<div class="mb-3">
								<label for="email" class="form-label">Email<b class="text-danger">*</b></label>
								<input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
							</div>
							<div class="mt-4 d-flex justify-content-end">
								<button type="submit" class="btn btn-primary">Update Profile</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
    </section>
@endsection