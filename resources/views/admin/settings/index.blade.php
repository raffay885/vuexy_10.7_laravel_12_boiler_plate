@extends('admin.layouts.master')
@section('title', 'Settings')
@section('content')
    <section>
		<form data-href="{{ route('settings.store') }}" data-redirect="{{ route('settings.index') }}" class="ajax-form in_page_ajax_form_page_reload">
			@csrf
			<div class="row">
				<div class="col-md-6 mb-4">
					<div class="card h-100">
						<div class="card-header">
							<h5 class="mb-0">
								<i class="icon-base ti tabler-cloud me-2"></i>Syncro Configuration
							</h5>
							<p class="text-muted mb-0 mt-2 small">Configure your Syncro integration settings</p>
						</div>
						<div class="card-body">
							<div class="mb-4">
								<label class="form-label" for="syncro_subdomain">Subdomain<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="syncro_subdomain" name="syncro_subdomain" value="{{ $setting ? $setting->syncro_subdomain : '' }}" placeholder="your-company" required />
								<small class="text-muted">Your Syncro subdomain (e.g., your-company.syncromsp.com)</small>
							</div>
							<div class="mb-4">
								<label class="form-label" for="syncro_domain">Domain<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="syncro_domain" name="syncro_domain" value="{{ $setting ? $setting->syncro_domain : '' }}" placeholder="syncromsp.com" required />
								<small class="text-muted">Base domain for Syncro</small>
							</div>
							<div class="mb-0">
								<label class="form-label" for="syncro_api_key">API Key<span class="text-danger">*</span></label>
								<div class="input-group">
									<input type="text" class="form-control" id="syncro_api_key" name="syncro_api_key" value="{{ $setting ? $setting->syncro_api_key : '' }}" placeholder="Enter your Syncro API key" required />
								</div>
								<small class="text-muted">API key from Syncro admin panel</small>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 mb-4">
					<div class="card h-100">
						<div class="card-header">
							<h5 class="mb-0">
								<i class="icon-base ti tabler-shield-lock me-2"></i>ESET Configuration
							</h5>
							<p class="text-muted mb-0 mt-2 small">Configure your ESET security integration</p>
						</div>
						<div class="card-body">
							<div class="mb-4">
								<label class="form-label" for="eset_base_url">Base URL<span class="text-danger">*</span></label>
								<input type="url" class="form-control" id="eset_base_url" name="eset_base_url" value="{{ $setting ? $setting->eset_base_url : '' }}" placeholder="https://api.eset.com" required />
								<small class="text-muted">ESET API endpoint URL</small>
							</div>
							<div class="mb-4">
								<label class="form-label" for="eset_username">Username<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="eset_username" name="eset_username" value="{{ $setting ? $setting->eset_username : '' }}" placeholder="Enter ESET username" required />
								<small class="text-muted">ESET account username</small>
							</div>
							<div class="mb-0">
								<label class="form-label" for="eset_password">Password<span class="text-danger">*</span></label>
								<div class="input-group">
									<input type="text" class="form-control" id="eset_password" name="eset_password" value="{{ $setting ? $setting->eset_password : '' }}" placeholder="Enter ESET password" required />
								</div>
								<small class="text-muted">ESET account password</small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 d-flex justify-content-end">
					<button type="submit" class="btn btn-primary save-btn">Update Settings</button>
				</div>
			</div>
		</form>
	</section>
@endsection
