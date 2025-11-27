@extends('admin.layouts.master')
@section('title', 'System Logs')
@section('content')
    <section>
		<div class="card mb-4">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<label class="form-label" for="source">Source</label>
						<select name="source" id="source" class="form-select">
							<option value="syncro" {{ request()->source == 'syncro' ? 'selected' : '' }}>Syncro</option>
							<option value="eset" {{ request()->source == 'eset' ? 'selected' : '' }}>Eset</option>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label" for="method">Method</label>
						<select name="method" id="method" class="form-select">
							<option value="">All</option>
							<option value="GET">GET</option>
							<option value="POST">POST</option>
							<option value="PUT">PUT</option>
							<option value="DELETE">DELETE</option>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label" for="status">Status</label>
						<select name="status" id="status" class="form-select">
							<option value="">All</option>
							<option value="success">Success</option>
							<option value="error">Error</option>
							<option value="warning">Warning</option>
							<option value="info">Info</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="d-flex justify-content-end mt-2">
							<button class="btn btn-secondary me-2" id="reset" onclick="window.location.reload()">Reset</button>
							<button class="btn btn-primary" id="filter">Filter</button>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="dataTable" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th class="not_include"></th>
                            <th>Sr #</th>
                            <th>Payload</th>
                            <th>End Point</th>
                            <th>Method</th>
                            <th>HTTP Code</th>
                            <th>Status</th>
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
            const systemLogsTable = new GenericDataTable({
                tableId: '#dataTable',
                ajaxUrl: "{{ route('system-logs.index') }}",
                title: 'System Logs',
				showActions: false,
				showCreateButton: false,
				ajaxData: function(d) {
					d.source = $('#source').val();
					d.method = $('#method').val();
					d.status = $('#status').val();
					return d;
				},
                columns: [
                    { data: 'id' },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'payload' },
                    { data: 'end_point' },
                    { data: 'method' },
                    { 
						data: 'http_code',
						render: function(data, type, full, meta) {
							return `<span class="badge bg-label-${full?.http_code >= 200 && full?.http_code < 300 ? 'success' : 'danger'}">${full?.http_code}</span>`;
						}
					},
                    { 
						data: 'status',
						render: function(data, type, full, meta) {
							return `<span class="badge bg-label-${full?.status == 'success' ? 'success' : 'danger'}">${full?.status}</span>`;
						}
					},
                    { 
						data: 'created_at',
						render: function(data, type, full, meta) {
							return formatDate(full?.created_at);
						}
					},
                ],
                searchPlaceholder: 'Search system logs...',
            });
        });

		$(document).on('click', '#filter', function() {
			$('#dataTable').DataTable().ajax.reload();
		});

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
    </script>
@endsection