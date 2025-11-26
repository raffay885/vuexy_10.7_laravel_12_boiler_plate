@extends('admin.layouts.master')
@section('title', 'Invoices')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="dataTable" style="white-space: nowrap;">
                    <thead>
                        <tr>
							<th class="not_include"></th>
							<th>Sr #</th>
							<th>Customer</th>
							<th>Invoice ID</th>
							<th>Estimate ID</th>
							<th>Invoice Number</th>
							<th>Invoice Date</th>
							<th>License Key</th>
							<th>Subtotal</th>
							<th>Tax</th>
							<th>Total</th>
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
							let customerDetailsUrl = "{{ route('customers.details', ':id') }}?tab=syncroDetails".replace(':id', row?.customer?.id);
							return `
								<a href="${customerDetailsUrl}">
									${row?.customer?.first_name} ${row?.customer?.last_name}
									<br>
									<small class="text-muted">Syncro ID: ${row?.customer?.syncro_customer_id}</small>
								</a>
							`;
						}
					},
                    { data: 'syncro_invoice_id' },
                    {
						data: 'syncro_estimate_id',
						name: 'syncro_estimate_id',
						render: function(data, type, row) {
							return `${row?.estimate?.syncro_estimate_id}`;
						}
					},
                    { data: 'syncro_invoice_number' },
                    { 
						data: 'syncro_invoice_date',
						name: 'syncro_invoice_date',
						render: function(data, type, row) {
							return formatDate(row.syncro_invoice_date);
						}
					},
                    { 
						data: 'eset_license_key',
						name: 'eset_license_key',
						render: function(data, type, row) {
							return row.eset_license_key || '-';
						}
					},
                    { 
						data: 'syncro_invoice_subtotal',
						name: 'syncro_invoice_subtotal',
						render: function(data, type, row) {
							return `$${row.syncro_invoice_subtotal.toFixed(2)}`;
						}
					},
                    { 
						data: 'syncro_invoice_tax',
						name: 'syncro_invoice_tax',
						render: function(data, type, row) {
							return `$${row.syncro_invoice_tax.toFixed(2)}`;
						}
					},
                    { 
						data: 'syncro_invoice_total',
						name: 'syncro_invoice_total',
						render: function(data, type, row) {
							return `$${row.syncro_invoice_total.toFixed(2)}`;
						}
					}
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