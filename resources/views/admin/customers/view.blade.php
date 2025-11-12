@extends('admin.layouts.master')
@section('title', 'Customer Details')
@section('page-css')
	<style>
		.dt-paging{
			display: flex !important;
			justify-content: end !important;
		}
	</style>
@endsection
@section('content')
	<section>
		<div class="row">
			<div class="col-12">
				<div class="card mb-6">
					<div class="card-header">
						<h5 class="card-title">Customer Details</h5>
					</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3 mb-3">
							<div class="text-muted small mb-1">Syncro ID</div>
							<div class="fw-semibold">{{ $customer->syncro_customer_id }}</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="text-muted small mb-1">First Name</div>
							<div class="fw-semibold">{{ $customer->first_name }}</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="text-muted small mb-1">Last Name</div>
							<div class="fw-semibold">{{ $customer->last_name }}</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="text-muted small mb-1">Email</div>
							<div class="fw-semibold">{{ $customer->email }}</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="nav-align-top">
					<ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-sm-0 gap-2">
						<li class="nav-item">
							<a class="nav-link {{ request()->query('tab') == 'syncroDetails' ? 'active' : '' }}" href="{{ route('customers.details', $customer->id) }}?tab=syncroDetails">
								<i class="icon-base ti tabler-user-check icon-sm me-1_5"></i> 
								Syncro Details
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ request()->query('tab') == 'customerAssets' ? 'active' : '' }}" href="{{ route('customers.details', $customer->id) }}?tab=customerAssets">
								<i class="icon-base ti tabler-user-check icon-sm me-1_5"></i> 
								Customer Assets
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			@if(request()->query('tab') == 'syncroDetails')
				@if(!empty($customerDetails))
					<div class="col-xl-6 col-lg-6 col-md-12 mb-6">
						<div class="card h-100">
							<div class="card-body">
								<p class="card-text text-uppercase text-body-secondary small mb-0">About</p>
								<ul class="list-unstyled my-3 py-1">
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-user icon-lg"></i>
										<span class="fw-medium mx-2">Full Name:</span> 
										<span>{{ $customerDetails['fullname'] ?? 'N/A' }}</span>
									</li>
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-briefcase icon-lg"></i>
										<span class="fw-medium mx-2">Business Name:</span>
										<span>{{ $customerDetails['business_name'] ?? 'N/A' }}</span>
									</li>
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-check icon-lg"></i>
										<span class="fw-medium mx-2">Status:</span>
										<span class="badge {{ ($customerDetails['disabled'] ?? false) ? 'bg-label-danger' : 'bg-label-success' }}">
											{{ ($customerDetails['disabled'] ?? false) ? 'Disabled' : 'Active' }}
										</span>
									</li>
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-id icon-lg"></i>
										<span class="fw-medium mx-2">Syncro ID:</span>
										<span>{{ $customerDetails['id'] ?? 'N/A' }}</span>
									</li>
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-map-pin icon-lg"></i>
										<span class="fw-medium mx-2">Address:</span>
										<span>{{ $customerDetails['address'] ?? 'N/A' }}</span>
									</li>
									@if(!empty($customerDetails['address_2']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-map-pin icon-lg"></i>
											<span class="fw-medium mx-2">Address 2:</span>
											<span>{{ $customerDetails['address_2'] }}</span>
										</li>
									@endif
									@if(!empty($customerDetails['city']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-location icon-lg"></i>
											<span class="fw-medium mx-2">City:</span>
											<span>{{ $customerDetails['city'] }}</span>
										</li>
									@endif
									@if(!empty($customerDetails['state']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-location icon-lg"></i>
											<span class="fw-medium mx-2">State:</span>
											<span>{{ $customerDetails['state'] }}</span>
										</li>
									@endif
									@if(!empty($customerDetails['zip']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-location icon-lg"></i>
											<span class="fw-medium mx-2">Zip:</span>
											<span>{{ $customerDetails['zip'] }}</span>
										</li>
									@endif
									@if(!empty($customerDetails['notes']))
										<li class="d-flex align-items-center mb-2">
											<i class="icon-base ti tabler-notes icon-lg"></i>
											<span class="fw-medium mx-2">Notes:</span>
											<span>{{ $customerDetails['notes'] }}</span>
										</li>
									@endif
								</ul>
								<p class="card-text text-uppercase text-body-secondary small mb-0 mt-4">Contact Information</p>
								<ul class="list-unstyled my-3 py-1">
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-phone icon-lg"></i>
										<span class="fw-medium mx-2">Phone:</span>
										<span>{{ $customerDetails['phone'] ?? 'N/A' }}</span>
									</li>
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-device-mobile icon-lg"></i>
										<span class="fw-medium mx-2">Mobile:</span>
										<span>{{ $customerDetails['mobile'] ?? 'N/A' }}</span>
									</li>
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-mail icon-lg"></i>
										<span class="fw-medium mx-2">Email:</span>
										<span>{{ $customerDetails['email'] ?? 'N/A' }}</span>
									</li>
									@if(!empty($customerDetails['notification_email']))
										<li class="d-flex align-items-center mb-2">
											<i class="icon-base ti tabler-bell icon-lg"></i>
											<span class="fw-medium mx-2">Notification Email:</span>
											<span>{{ $customerDetails['notification_email'] }}</span>
										</li>
									@endif
								</ul>
							</div>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-12 mb-6">
						<div class="card h-100">
							<div class="card-body">
								<p class="card-text text-uppercase text-body-secondary small mb-0">Preferences</p>
								<ul class="list-unstyled my-3 py-1">
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-message-circle-off icon-lg"></i>
										<span class="fw-medium mx-2">SMS Opted:</span>
										<span class="badge {{ ($customerDetails['get_sms'] ?? false) ? 'bg-label-success' : 'bg-label-secondary' }}">
											{{ ($customerDetails['get_sms'] ?? false) ? 'Yes' : 'No' }}
										</span>
									</li>
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-mail-off icon-lg"></i>
										<span class="fw-medium mx-2">Email Opted Out:</span>
										<span class="badge {{ ($customerDetails['no_email'] ?? false) ? 'bg-label-warning' : 'bg-label-success' }}">
											{{ ($customerDetails['no_email'] ?? false) ? 'Yes' : 'No' }}
										</span>
									</li>
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-user-x icon-lg"></i>
										<span class="fw-medium mx-2">Opt Out:</span>
										<span class="badge {{ ($customerDetails['opt_out'] ?? false) ? 'bg-label-danger' : 'bg-label-success' }}">
											{{ ($customerDetails['opt_out'] ?? false) ? 'Yes' : 'No' }}
										</span>
									</li>
								</ul>
								<p class="card-text text-uppercase text-body-secondary small mb-0 mt-4">Location & Coordinates</p>
								<ul class="list-unstyled my-3 py-1">
									@if(!empty($customerDetails['location_name']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-building icon-lg"></i>
											<span class="fw-medium mx-2">Location Name:</span>
											<span>{{ $customerDetails['location_name'] }}</span>
										</li>
									@endif
									@if(!empty($customerDetails['latitude']) && !empty($customerDetails['longitude']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-map-2 icon-lg"></i>
											<span class="fw-medium mx-2">Coordinates:</span>
											<span>{{ $customerDetails['latitude'] }}, {{ $customerDetails['longitude'] }}</span>
										</li>
									@endif
								</ul>
								<p class="card-text text-uppercase text-body-secondary small mb-0 mt-4">Billing & Invoice</p>
								<ul class="list-unstyled my-3 py-1">
									@if(!empty($customerDetails['invoice_term_id']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-file-invoice icon-lg"></i>
											<span class="fw-medium mx-2">Invoice Term ID:</span>
											<span>{{ $customerDetails['invoice_term_id'] }}</span>
										</li>
									@endif
									@if(!empty($customerDetails['tax_rate_id']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-receipt-tax icon-lg"></i>
											<span class="fw-medium mx-2">Tax Rate ID:</span>
											<span>{{ $customerDetails['tax_rate_id'] }}</span>
										</li>
									@endif
									@if(!empty($customerDetails['invoice_cc_emails']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-mail-forward icon-lg"></i>
											<span class="fw-medium mx-2">Invoice CC Emails:</span>
											<span>{{ $customerDetails['invoice_cc_emails'] }}</span>
										</li>
									@endif
								</ul>
								<p class="card-text text-uppercase text-body-secondary small mb-0 mt-4">Additional Information</p>
								<ul class="list-unstyled my-3 py-1">
									@if(!empty($customerDetails['online_profile_url']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-link icon-lg"></i>
											<span class="fw-medium mx-2">Online Profile:</span>
											<a href="{{ $customerDetails['online_profile_url'] }}" target="_blank" class="text-primary">View Portal</a>
										</li>
									@endif
									@if(!empty($customerDetails['referred_by']))
										<li class="d-flex align-items-center mb-4">
											<i class="icon-base ti tabler-user-share icon-lg"></i>
											<span class="fw-medium mx-2">Referred By:</span>
											<span>{{ $customerDetails['referred_by'] }}</span>
										</li>
									@endif
									<li class="d-flex align-items-center mb-4">
										<i class="icon-base ti tabler-calendar-plus icon-lg"></i>
										<span class="fw-medium mx-2">Created:</span>
										<span>{{ isset($customerDetails['created_at']) ? date('M d, Y h:i A', strtotime($customerDetails['created_at'])) : 'N/A' }}</span>
									</li>
									<li class="d-flex align-items-center mb-2">
										<i class="icon-base ti tabler-calendar-event icon-lg"></i>
										<span class="fw-medium mx-2">Updated:</span>
										<span>{{ isset($customerDetails['updated_at']) ? date('M d, Y h:i A', strtotime($customerDetails['updated_at'])) : 'N/A' }}</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				@else
					<div class="col-md-12">
						<h5 class="text-center mt-5">No customer details found ...</h5>
					</div>
				@endif
			@elseif(request()->query('tab') == 'customerAssets')
				<div class="col-12">
					<div class="card">
						<div class="card-datatable table-responsive pt-0">
							<table class="table" id="dataTable">
								<thead>
									<tr>
										<th class="not_include"></th>
										<th>Sr #</th>
										<th>Asset Name</th>
										<th>Asset Type</th>
										<th>Asset Serial</th>
										<th>Created At</th>
										<th>Updated At</th>
										<th class="not_include">Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($customerAssets as $key => $customerAsset)
										<tr>
											<td class="not_include">
												<div class="form-check">
													<input class="form-check-input asset-checkbox" name="asset_id[]" value="{{ $customerAsset['id'] }}" type="checkbox" id="flexCheckDefault">
													<label class="form-check-label" for="flexCheckDefault"></label>
												</div>
											</td>
											<td>{{ $key + 1 }}</td>
											<td>{{ $customerAsset['name'] }}</td>
											<td>{{ $customerAsset['asset_type'] }}</td>
											<td>{{ $customerAsset['asset_serial'] }}</td>
											<td>{{ date('M d, Y h:i A', strtotime($customerAsset['created_at'])) }}</td>
											<td>{{ date('M d, Y h:i A', strtotime($customerAsset['updated_at'])) }}</td>
											<td>
												<div class="d-inline-block">
													<a href="javascript:;" class="btn btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
														<i class="icon-base ti tabler-dots-vertical"></i>
													</a>
													<ul class="dropdown-menu dropdown-menu-end m-0">
														<li><a href="javascript:;" data-href="{{ route('customer-assets.details', $customerAsset['id']) }}" class="dropdown-item view-details">View Details</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			@endif
		</div>
	</section>

	{{-- Create Estimate Modal --}}
	<div class="modal fade" id="createEstimateModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Create Estimate</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form data-href="{{ route('estimates.store') }}" class="ajax-form in_page_ajax_form_page_reload">
					@csrf
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 mb-3">
								<label for="estimate-number" class="form-label">Estimate Number<b class="text-danger">*</b></label>
								<input type="text" class="form-control" id="estimate-number" name="number" placeholder="Enter estimate number" required>
							</div>
							<div class="col-md-12 mb-3">
								<label for="estimate-date" class="form-label">Estimate Date<b class="text-danger">*</b></label>
								<input type="date" class="form-control" id="estimate-date" name="date" required>
							</div>
							<input type="hidden" name="customer_id" value="{{ $customer->id }}">
							<input type="hidden" name="asset_ids[]" id="asset_ids">
							<div class="col-md-12 mb-3">
								<label for="estimate-notes" class="form-label">Notes<b class="text-danger">*</b></label>
								<textarea class="form-control" id="estimate-notes" name="note" rows="4" placeholder="Enter any additional notes" required></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Create Estimate</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Asset Details Modal --}}
	<div class="modal fade" id="assetDetailsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modelHeading">Asset Details</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<p class="text-uppercase text-body-secondary small mb-3">Basic Information</p>
						<ul class="list-unstyled mb-4">
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-id icon-lg"></i>
								<span class="fw-medium mx-2">Asset ID:</span>
								<span id="asset-id">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-device-desktop icon-lg"></i>
								<span class="fw-medium mx-2">Name:</span>
								<span id="asset-name">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-category icon-lg"></i>
								<span class="fw-medium mx-2">Type:</span>
								<span id="asset-type">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-barcode icon-lg"></i>
								<span class="fw-medium mx-2">Serial Number:</span>
								<span id="asset-serial">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-map-pin icon-lg"></i>
								<span class="fw-medium mx-2">Address:</span>
								<span id="asset-address">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-user icon-lg"></i>
								<span class="fw-medium mx-2">Contact ID:</span>
								<span id="asset-contact-id">-</span>
							</li>
						</ul>
						<p class="text-uppercase text-body-secondary small mb-3">Network & Monitoring</p>
						<ul class="list-unstyled mb-0">
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-network icon-lg"></i>
								<span class="fw-medium mx-2">SNMP Enabled:</span>
								<span id="snmp-enabled">-</span>
							</li>
						</ul>
					</div>
					<div class="col-md-6">
						<p class="text-uppercase text-body-secondary small mb-3">Warranty & Support</p>
						<ul class="list-unstyled mb-4">
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-calendar icon-lg"></i>
								<span class="fw-medium mx-2">Warranty Start:</span>
								<span id="warranty-start">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-calendar icon-lg"></i>
								<span class="fw-medium mx-2">Warranty End:</span>
								<span id="warranty-end">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-message-circle icon-lg"></i>
								<span class="fw-medium mx-2">Live Chat:</span>
								<span id="has-live-chat">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-link icon-lg"></i>
								<span class="fw-medium mx-2">External RMM Link:</span>
								<span id="external-rmm-link">-</span>
							</li>
						</ul>
						<p class="text-uppercase text-body-secondary small mb-3">Timestamps</p>
						<ul class="list-unstyled mb-0">
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-calendar-plus icon-lg"></i>
								<span class="fw-medium mx-2">Created At:</span>
								<span id="asset-created-at">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-calendar-event icon-lg"></i>
								<span class="fw-medium mx-2">Updated At:</span>
								<span id="asset-updated-at">-</span>
							</li>
							<li class="d-flex align-items-center mb-3">
								<i class="icon-base ti tabler-calendar-stats icon-lg"></i>
								<span class="fw-medium mx-2">Since Updated At:</span>
								<span id="asset-since-updated">-</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('page-js')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
				dom: '<"card-header flex-column flex-md-row border-bottom"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row mx-2"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
				buttons: [
					{
						extend: 'collection',
						className: 'btn btn-label-primary dropdown-toggle me-2',
						text: '<i class="icon-base ti tabler-upload me-1"></i>Export',
						init: function (api, node, config) {
							$(node).removeClass('btn-secondary btn-outline-secondary');
							$(node).addClass('btn-label-primary');
						},
						buttons: [
							{
								extend: 'print',
								text: '<i class="icon-base ti tabler-printer me-1"></i>Print',
								className: 'dropdown-item',
								exportOptions: {
									columns: ':not(.not_include)'
								}
							},
							{
								extend: 'csv',
								text: '<i class="icon-base ti tabler-file-text me-1"></i>CSV',
								className: 'dropdown-item',
								exportOptions: {
									columns: ':not(.not_include)'
								}
							},
							{
								extend: 'excel',
								text: '<i class="icon-base ti tabler-file-spreadsheet me-1"></i>Excel',
								className: 'dropdown-item',
								exportOptions: {
									columns: ':not(.not_include)'
								}
							},
							{
								extend: 'pdf',
								text: '<i class="icon-base ti tabler-file-description me-1"></i>PDF',
								className: 'dropdown-item',
								exportOptions: {
									columns: ':not(.not_include)'
								}
							},
							{
								extend: 'copy',
								text: '<i class="icon-base ti tabler-copy me-1"></i>Copy',
								className: 'dropdown-item',
								exportOptions: {
									columns: ':not(.not_include)'
								}
							}
						]
					},
					{
						text: '<i class="icon-base ti tabler-file-invoice me-1"></i>Create Estimate',
						className: 'btn btn-primary waves-effect waves-light create-estimate',
						init: function (api, node, config) {
							$(node).removeClass('btn-secondary btn-outline-secondary');
							$(node).addClass('btn-primary');
						},
					}
				],
				language: {
					searchPlaceholder: 'Search Assets...'
				}
			});
        });

		$(document).on('click', '.view-details', function() {
			blockUI();
			const url = $(this).data('href');
			$.ajax({
				url: url,
				type: 'GET',
				success: function(response) {
					unblockUI();
					$('#asset-id').text(displayValue(response.id));
					$('#asset-name').text(displayValue(response.name));
					$('#asset-type').text(displayValue(response.asset_type));
					$('#asset-serial').text(displayValue(response.asset_serial));
					$('#asset-address').text(displayValue(response.address));
					$('#asset-contact-id').text(displayValue(response.contact_id));

					// Populate Warranty & Support
					$('#warranty-start').text(displayValue(response.warranty_start_date));
					$('#warranty-end').text(displayValue(response.warranty_end_date));
					$('#has-live-chat').html(response.has_live_chat ? 
						'<span class="badge bg-label-success">Yes</span>' : 
						'<span class="badge bg-label-secondary">No</span>'
					);
					
					$('#external-rmm-link').html(
						response.external_rmm_link ? 
						'<a href="' + response.external_rmm_link + '" target="_blank" class="text-primary">View Link</a>' : 
						'N/A'
					);

					if (response.snmp_enabled !== null) {
						$('#snmp-enabled').html(response.snmp_enabled ? 
							'<span class="badge bg-label-success">Enabled</span>' : 
							'<span class="badge bg-label-secondary">Disabled</span>');
					} else {
						$('#snmp-enabled').text('N/A');
					}

					$('#asset-created-at').text(formatDate(response.created_at));
					$('#asset-updated-at').text(formatDate(response.updated_at));
					$('#asset-since-updated').text(formatDate(response.since_updated_at));

					// Show the modal
					$('#assetDetailsModal').modal('show');
				},
				error: function(xhr, status, error) {
					unblockUI();
					var response = xhr.responseJSON;
					showToast('error', response?.message || 'Failed to load asset details. Please try again.');
				}
			});
		});

		$(document).on('click', '.create-estimate', function() {
			const assetIds = getCheckedAssetIds();
			if (assetIds.length === 0) {
				showToast('error', 'Please select at least one asset to create an estimate.');
				return;
			}

			$('#asset_ids').val(assetIds);
			$('#createEstimateModal').modal('show');
		});

		function getCheckedAssetIds() {
			return $('.asset-checkbox:checked').map(function() {
				return $(this).val();
			}).get();
		}

		function formatDate(dateString) {
			if (!dateString){
				return 'N/A'
			}

			const date = new Date(dateString);
			return date.toLocaleDateString('en-US', { 
				year: 'numeric', 
				month: 'short', 
				day: 'numeric',
				hour: '2-digit',
				minute: '2-digit'
			});
		}

		function displayValue(value) {
			if (!value){
				return 'N/A';
			}

			return value;
		}
    </script>
@endsection