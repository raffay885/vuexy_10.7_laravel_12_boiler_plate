@extends('admin.layouts.master')
@section('title', 'Invoices')
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
							<th>Invoice ID</th>
							<th>Estimate ID</th>
							<th>Invoice Number</th>
							<th>Subtotal</th>
							<th>Tax</th>
							<th>Total</th>
							<th>Created At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@endsection
@section('page-js')
    <script>
        $(document).ready(function() {
            const invoicesTable = new GenericDataTable({
                tableId: '#dataTable',
                ajaxUrl: "{{ route('invoices.index') }}",
                title: 'Invoices',
                showCreateButton: false,
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
                    { data: 'syncro_invoice_id' },
                    {
						data: 'estimate',
						name: 'estimate',
						render: function(data, type, row) {
							return `${row?.estimate?.syncro_estimate_id}`;
						}
					},
                    { data: 'number' },
                    { 
						data: 'subtotal',
						name: 'subtotal',
						render: function(data, type, row) {
							return `$${row.subtotal.toFixed(2)}`;
						}
					},
                    { 
						data: 'tax',
						name: 'tax',
						render: function(data, type, row) {
							return `$${row.tax.toFixed(2)}`;
						}
					},
                    { 
						data: 'total',
						name: 'total',
						render: function(data, type, row) {
							return `$${row.total.toFixed(2)}`;
						}
					},
                    { 
						data: 'created_at',
						name: 'created_at',
						render: function(data, type, row) {
							return formatDate(row.created_at);
						}
					},
                ],
                searchPlaceholder: 'Search invoices...',
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