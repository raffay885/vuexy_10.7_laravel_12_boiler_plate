@extends('admin.layouts.master')
@section('title', 'Edit Role')
@section('content')
    <section>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="mb-0">Edit Role</h5>
					</div>
					<div class="card-body">
						<form data-href="{{ route('roles.update', $role->id) }}" data-redirect="{{ route('roles.index') }}" class="ajax-form in_page_ajax_form_page_reload">
							@csrf
							@method('PUT')
							<div class="row">
								<div class="col-12">
									<div class="mb-6">
										<label class="form-label" for="role">Role<b class="text-danger">*</b></label>
										<input type="text" class="form-control" id="role" name="name" placeholder="Enter role name" required value="{{ $role->name }}" />
									</div>
								</div>
								<div class="col-12">
									<div class="row">
										@foreach ($permissions as $parent => $childs)
											<div class="col-4 d-flex flex-column justify-content-between">
												<div>
													<div class="col-12 mb-2">
														<div class="form-check">
															<input class="form-check-input" type="checkbox" id="{{ $parent }}" onchange="checkAll(this)">
															<label class="form-check-label fw-bold fs-5" for="{{ $parent }}">{{ $parent }}</label>
														</div>
													</div>
													@foreach ($childs as $permission)
														<div class="col-12">
															<div class="form-check ps-0">
																<input class="custom-control-input" type="checkbox" id="{{ $permission->name }}" name="permissions[]" value="{{ $permission->name }}" data-parent="{{ $parent }}" onchange="updateParentCheckbox(this)" @if(in_array($permission->id, $role->permissions->pluck('id')->toArray())) checked @endif>
																<label class="form-check-label" for="{{ $permission->name }}">{{ $permission->name }}</label>
															</div>
														</div>
													@endforeach
												</div>
											</div>
										@endforeach
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 text-end mt-3">
									<button type="submit" class="btn btn-primary save-btn">Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('page-js')
    <script>
		$(document).ready(function() {
			let childCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
			let processedParents = new Set();
			
			childCheckboxes.forEach(function(checkbox) {
				let parentId = checkbox.getAttribute('data-parent');
				if (!processedParents.has(parentId)) {
					processedParents.add(parentId);
					updateParentCheckbox(checkbox);
				}
			});
		});

        function checkAll(element) {
			var parent = $(element).attr('id');
			if ($(element).is(':checked')) {
				$('input[data-parent="' + parent + '"]').prop('checked', true);
			} else {
				$('input[data-parent="' + parent + '"]').prop('checked', false);
			}
		}

		function updateParentCheckbox(child) {
			let parentId = child.getAttribute("data-parent");
			let parentCheckbox = document.getElementById(parentId);
			let childCheckboxes = document.querySelectorAll(`input[data-parent='${parentId}']`);

			// Check if all child checkboxes are checked
			let allChecked = Array.from(childCheckboxes).every(checkbox => checkbox.checked);
			parentCheckbox.checked = allChecked;
		}
    </script>
@endsection
