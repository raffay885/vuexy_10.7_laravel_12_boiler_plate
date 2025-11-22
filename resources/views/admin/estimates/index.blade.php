@extends('admin.layouts.master')
@section('title', 'Estimates')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
							<th class="not_include"></th>
							<th>Sr #</th>
							<th>Customer</th>
							<th>Estimate Number</th>
							<th>Estimate ID</th>
							<th>Product ID</th>
							<th>Subtotal</th>
							<th>Tax</th>
							<th>Total</th>
							<th>Status</th>
							<th>Approved At</th>
							<th>Created At</th>
							<th>Updated At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

	{{-- Create Estimate Modal --}}
	<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Create Estimate</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form data-href="{{ route('estimates.store') }}" class="ajax-form in_page_ajax_form">
					@csrf
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 mb-3">
								<label for="syncro_product_id" class="form-label">Products<b class="text-danger">*</b></label>
								<select name="syncro_product_id" id="syncro_product_id" class="select2 form-select" data-placeholder="Select Product" required>
									@if(isset($products) && isset($products['products']) && !empty($products['products']))
										<option value="">Select Product</option>
										@foreach($products['products'] as $product)
											<option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
										@endforeach
									@else
										<option value="">No products found</option>
									@endif
								</select>
							</div>
							<div class="col-md-12 mb-3">
								<label for="customer_id" class="form-label">Customer<b class="text-danger">*</b></label>
								<select name="customer_id" id="customer_id" class="select2 form-select" data-placeholder="Select Customer" required>
									<option value="">Select Customer</option>
									@foreach($customers as $customer)
										<option value="{{ $customer->id }}">{{ $customer->first_name . ' ' . $customer->last_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-12 mb-3">
								<label for="quantity" class="form-label">Quantity<b class="text-danger">*</b></label>
								<input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
							</div>
							<div class="col-md-12 mb-4">
								<label for="estimate-notes" class="form-label">Notes<b class="text-danger">*</b></label>
								<textarea class="form-control" id="estimate-notes" name="note" rows="4" placeholder="Enter any additional notes" required></textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 d-flex justify-content-end">
								<button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
								<button type="submit" class="btn btn-primary">Create Estimate</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@section('page-js')
    <script>
        $(document).ready(function() {
            const estimatesTable = new GenericDataTable({
                tableId: '#dataTable',
                ajaxUrl: "{{ route('estimates.index') }}",
                title: 'Estimates',
                createModal: "#addModal",
                showCreateButton: true,
                showActions: false,
                columns: [
                    { data: 'id' },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
						data: 'customerName',
						name: 'customerName',
						render: function(data, type, row) {
							return `${row?.customer?.first_name} ${row?.customer?.last_name}`;
						}
					},
                    { data: 'number' },
                    { data: 'syncro_estimate_id' },
                    { data: 'syncro_product_id' },
                    { 
						data: 'estimate_subtotal',
						name: 'estimate_subtotal',
						render: function(data, type, row) {
							return `$${row.estimate_subtotal.toFixed(2)}`;
						}
					},
                    { 
						data: 'estimate_tax',
						name: 'estimate_tax',
						render: function(data, type, row) {
							return `$${row.estimate_tax.toFixed(2)}`;
						}
					},
                    { 
						data: 'estimate_total',
						name: 'estimate_total',
						render: function(data, type, row) {
							return `$${row.estimate_total.toFixed(2)}`;
						}
					},
                    {
						data: 'status',
						name: 'status',
						render: function(data, type, row) {
							const statusClasses = {
								'Draft': 'bg-label-secondary',
								'Fresh': 'bg-label-primary',
								'Approved': 'bg-label-success',
								'Declined': 'bg-label-danger',
								'Invoice Made': 'bg-label-info'
							};

							const badgeClass = statusClasses[row.status] || 'bg-label-secondary';
							return `<span class="badge ${badgeClass}">${row.status}</span>`;
						}
					},
                    { 
						data: 'approved_at',
						name: 'approved_at',
						render: function(data, type, row) {
							return formatDate(row.approved_at);
						}
					},
                    { 
						data: 'created_at',
						name: 'created_at',
						render: function(data, type, row) {
							return formatDate(row.created_at);
						}
					},
                    { 
						data: 'updated_at',
						name: 'updated_at',
						render: function(data, type, row) {
							return formatDate(row.updated_at);
						}
					},
                ],
                searchPlaceholder: 'Search estimates...',
            });
        });

		$(document).on('shown.bs.modal', function (event) {
			const modal = $(event.target);
			modal.find('.select2').select2({
				dropdownParent: modal
			});
		});

		function formatDate(dateString) {
			if (!dateString) {
				return '-';
			}
			const date = new Date(dateString);
			return date.toLocaleDateString('en-US', { 
				year: 'numeric', 
				month: '2-digit', 
				day: '2-digit' 
			});
		}
    </script>
@endsection