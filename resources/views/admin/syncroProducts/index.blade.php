@extends('admin.layouts.master')
@section('title', 'Syncro Products')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th class="not_include"></th>
                            <th>Sr #</th>
                            <th>Syncro Product ID</th>
                            <th>Product Title</th>
                            <th>Billing Type</th>
                            <th class="not_include">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

		<!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading">Add Syncro Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form data-href="{{ route('syncro-products.store') }}" class="ajax-form in_page_ajax_form">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-2">
								<div class="col-md-12">
									<label class="form-label" for="syncro_product_id">Product ID<b class="text-danger">*</b></label>
									<input type="text" id="syncro_product_id" name="syncro_product_id" class="form-control" placeholder="Enter syncro product id" required />
								</div>
                                <div class="col-md-12">
                                    <label class="form-label" for="name">Product Title<b class="text-danger">*</b></label>
                                    <input type="text" id="syncro_product_title" name="syncro_product_title" class="form-control" placeholder="Enter product title" required />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="billing_type">Billing Type<b class="text-danger">*</b></label>
                                    <select name="billing_type" id="billing_type" class="form-select" required>
                                        <option value="annual">Annual</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary save-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

		{{-- Update Modal --}}
		<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading">Update Syncro Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="ajax-form in_page_ajax_form" id="updateForm">
                        @csrf
						@method('PUT')
                        <div class="modal-body">
                            <div class="row g-2">
								<div class="col-md-12">
									<label class="form-label" for="syncro_product_id">Product ID<b class="text-danger">*</b></label>
									<input type="text" id="edit_syncro_product_id" name="syncro_product_id" class="form-control" placeholder="Enter syncro product id" required />
								</div>
                                <div class="col-md-12">
                                    <label class="form-label" for="syncro_product_title">Product Title<b class="text-danger">*</b></label>
                                    <input type="text" id="edit_syncro_product_title" name="syncro_product_title" class="form-control" placeholder="Enter product title" required />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="billing_type">Billing Type<b class="text-danger">*</b></label>
                                    <select name="billing_type" id="edit_billing_type" class="form-select" required>
                                        <option value="annual">Annual</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary save-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page-js')
    <script>
        $(document).ready(function() {
            const syncroProductsTable = new GenericDataTable({
                tableId: '#dataTable',
                ajaxUrl: "{{ route('syncro-products.index') }}",
                title: 'Syncro Products',
                createModal: "#addModal",
                columns: [
                    { data: 'id' },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'syncro_product_id' },
                    { data: 'syncro_product_title' },
                    { 
                        data: 'billing_type',
                        render: function(data, type, full, meta) {
                            return `<span class="badge bg-label-info">${full?.billing_type}</span>`;
                        }
                    },
                    { data: 'action' }
                ],
                actionRenderer: function(data, type, full, meta) {
                    let updateUrl = "{{ route('syncro-products.update', ':id') }}".replace(':id', full.id);
                    let deleteUrl = "{{ route('syncro-products.destroy', ':id') }}".replace(':id', full.id);
                    
                    let btn = `
                        <li><a href="javascript:;" class="dropdown-item edit-record" data-href="${updateUrl}" data-syncro-product-id="${full.syncro_product_id}" data-syncro-product-title="${full.syncro_product_title}" data-billing-type="${full.billing_type}" data-bs-target="#updateModal" data-bs-toggle="modal">Edit</a></li>
                        <li><a href="javascript:;" class="dropdown-item text-danger deleteRecord" data-href="${deleteUrl}">Delete</a></li>
                    `;
                    
                    return `
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                ${btn}
                            </ul>
                        </div>
                    `;
                },
                searchPlaceholder: 'Search syncro products...',
            });
        });

		$(document).on('click' , '.edit-record' , function (){
			$('#edit_syncro_product_id').val($(this).attr('data-syncro-product-id'));
			$('#edit_syncro_product_title').val($(this).attr('data-syncro-product-title'));
			$('#edit_billing_type').val($(this).attr('data-billing-type')).trigger('change');
			$('#updateForm').attr('data-href', $(this).attr('data-href'));
			$('#updateModal').modal('show');
		});
    </script>
@endsection