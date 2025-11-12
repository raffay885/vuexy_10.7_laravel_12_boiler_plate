@extends('admin.layouts.master')
@section('title', 'Customer Assets')
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
                            <th>Asset Name</th>
                            <th>Asset Type ID</th>
                            <th>Asset Serial</th>
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
                        <h5 class="modal-title" id="modelHeading">Add Customer Asset</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('customer-assets.store') }}" class="ajax-form in_page_ajax_form">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label" for="first_name">Customer<b class="text-danger">*</b></label>
                                    <select name="customer_id" id="customer_id" class="select2 form-select" data-placeholder="Select Customer" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="name">Asset Name<b class="text-danger">*</b></label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter asset name" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="asset_type_id">Asset Type<b class="text-danger">*</b></label>
                                    <select name="asset_type_id" id="asset_type_id" class="select2 form-select" data-placeholder="Select Asset Type" required>
                                        <option value="">Select Asset Type</option>
                                        @if($assetTypes && isset($assetTypes['asset_types']))
                                            @foreach($assetTypes['asset_types'] as $assetType)
                                                <option value="{{ $assetType['id'] }}">{{ $assetType['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="asset_serial">Asset Serial<b class="text-danger">*</b></label>
                                    <input type="text" id="asset_serial" name="asset_serial" class="form-control" placeholder="Enter asset serial" required />
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
                        <h5 class="modal-title" id="modelHeading">Update Customer Asset</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="ajax-form in_page_ajax_form" id="updateForm">
                        @csrf
						@method('PUT')
                       <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label" for="first_name">Customer<b class="text-danger">*</b></label>
                                    <select name="customer_id" id="edit_customer_id" class="select2 form-select" data-placeholder="Select Customer" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="name">Asset Name<b class="text-danger">*</b></label>
                                    <input type="text" id="edit_name" name="name" class="form-control" placeholder="Enter asset name" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="asset_type_id">Asset Type<b class="text-danger">*</b></label>
                                    <select name="asset_type_id" id="edit_asset_type_id" class="select2 form-select" data-placeholder="Select Asset Type" required>
                                        <option value="">Select Asset Type</option>
                                        @if($assetTypes && isset($assetTypes['asset_types']))
                                            @foreach($assetTypes['asset_types'] as $assetType)
                                                <option value="{{ $assetType['id'] }}">{{ $assetType['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="asset_serial">Asset Serial<b class="text-danger">*</b></label>
                                    <input type="text" id="edit_asset_serial" name="asset_serial" class="form-control" placeholder="Enter asset serial" required />
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
            const customersTable = new GenericDataTable({
                tableId: '#dataTable',
                ajaxUrl: "{{ route('customer-assets.index') }}",
                title: 'Customer Assets',
                createModal: "#addModal",
                columns: [
                    { data: 'id' },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'customer',
                        name: 'customer',
                        render: function(data, type, full, meta) {
                            return `${full?.customer?.first_name} ${full?.customer?.last_name}`;
                        }
                    },
                    { data: 'name' },
                    { data: 'asset_type_id' },
                    { data: 'asset_serial' },
                    { data: 'action' }
                ],
                actionRenderer: function(data, type, full, meta) {
                    let updateUrl = "{{ route('customer-assets.update', ':id') }}".replace(':id', full.id);
                    let deleteUrl = "{{ route('customer-assets.destroy', ':id') }}".replace(':id', full.id);
                    
                    let btn = `
                        <li><a href="javascript:;" class="dropdown-item edit-record" data-href="${updateUrl}" data-bs-target="#updateModal" data-bs-toggle="modal" data-customer-id="${full.customer_id}" data-name="${full.name}" data-asset-type-id="${full.asset_type_id}" data-asset-serial="${full.asset_serial}">Edit</a></li>
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
                searchPlaceholder: 'Search customer assets...',
            });
        });

		$(document).on('click' , '.edit-record' , function (){
			$('#edit_customer_id').val($(this).attr('data-customer-id')).trigger('change');
			$('#edit_name').val($(this).attr('data-name'));
			$('#edit_asset_type_id').val($(this).attr('data-asset-type-id'));
			$('#edit_asset_serial').val($(this).attr('data-asset-serial'));

			$('#updateForm').attr('data-href', $(this).attr('data-href'));
			$('#updateModal').modal('show');
		});
    </script>
@endsection