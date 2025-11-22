@extends('admin.layouts.master')
@section('title', 'Dashboard')
@section('page-css')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection
@section('content')
  	<div class="row g-6">
		<!-- Monthly Revenue Card -->
		<div class="col-lg-3 col-sm-6">
			<a href="{{ route('invoices.index') }}" class="text-decoration-none">
				<div class="card card-border-shadow-success h-100">
					<div class="card-body">
						<div class="d-flex align-items-center mb-2">
							<div class="avatar me-4">
								<span class="avatar-initial rounded bg-label-success">
									<i class="icon-base ti tabler-currency-dollar icon-28px"></i>
								</span>
							</div>
							<h4 class="mb-0 text-dark">${{ number_format($monthlyRevenue, 2) }}</h4>
						</div>
						<p class="mb-1 text-dark">Monthly Revenue</p>
						<p class="mb-0">
							<span class="text-heading fw-medium me-2 {{ $revenueGrowth >= 0 ? 'text-success' : 'text-danger' }}">
								{{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}%
							</span>
							<small class="text-body-secondary">vs last month</small>
						</p>
					</div>
				</div>
			</a>
		</div>

		<!-- Total Invoices Card -->
		<div class="col-lg-3 col-sm-6">
			<a href="{{ route('invoices.index') }}" class="text-decoration-none">
				<div class="card card-border-shadow-primary h-100">
					<div class="card-body">
						<div class="d-flex align-items-center mb-2">
							<div class="avatar me-4">
								<span class="avatar-initial rounded bg-label-primary">
									<i class="icon-base ti tabler-receipt icon-28px"></i>
								</span>
							</div>
							<h4 class="mb-0">{{ $totalInvoices }}</h4>
						</div>
						<p class="mb-1">Total Invoices</p>
						<p class="mb-0">
							<span class="text-heading fw-medium me-2">+{{ $newInvoicesThisMonth }}</span>
							<small class="text-body-secondary">this month</small>
						</p>
					</div>
				</div>
			</a>
		</div>

		<!-- Total Estimates Card -->
		<div class="col-lg-3 col-sm-6">
			<a href="{{ route('estimates.index') }}" class="text-decoration-none">
				<div class="card card-border-shadow-primary h-100">
					<div class="card-body">
						<div class="d-flex align-items-center mb-2">
							<div class="avatar me-4">
								<span class="avatar-initial rounded bg-label-primary">
									<i class="icon-base ti tabler-file-invoice icon-28px"></i>
								</span>
							</div>
							<h4 class="mb-0">{{ $totalEstimates }}</h4>
						</div>
						<p class="mb-1">Total Estimates</p>
						<p class="mb-0">
							<span class="text-heading fw-medium me-2">+{{ $newEstimatesThisMonth }}</span>
							<small class="text-body-secondary">this month</small>
						</p>
					</div>
				</div>
			</a>
		</div>

		<!-- Total Customers Card -->
		<div class="col-lg-3 col-sm-6">
			<a href="{{ route('customers.index') }}" class="text-decoration-none">
				<div class="card card-border-shadow-info h-100">
					<div class="card-body">
						<div class="d-flex align-items-center mb-2">
							<div class="avatar me-4">
								<span class="avatar-initial rounded bg-label-info">
									<i class="icon-base ti tabler-users icon-28px"></i>
								</span>
							</div>
							<h4 class="mb-0">{{ $totalCustomers }}</h4>
						</div>
						<p class="mb-1">Total Customers</p>
						<p class="mb-0">
							<span class="text-heading fw-medium me-2">+{{ $newCustomersThisMonth }}</span>
							<small class="text-body-secondary">this month</small>
						</p>
					</div>
				</div>
			</a>
		</div>

		<!-- Revenue Chart -->
		<div class="col-xxl-12 col-lg-12">
			<div class="card h-100">
			<div class="card-header d-flex align-items-center justify-content-between">
				<div class="card-title mb-0">
				<h5 class="mb-1">Monthly Revenue</h5>
				<p class="card-subtitle">Revenue trends over the last 12 months</p>
				</div>
				<div class="d-flex align-items-center">
					<span class="badge bg-label-success me-2">
						<i class="ti tabler-trending-up me-1"></i>
						Total: ${{ number_format(array_sum($monthlyRevenueData), 2) }}
					</span>
				</div>
			</div>
			<div class="card-body">
				<div id="monthlyRevenueChart"></div>
			</div>
			</div>
		</div>

		<!-- Estimates Status -->
		<div class="col-xxl-6 col-lg-6">
			<div class="card h-100">
			<div class="card-header d-flex align-items-center justify-content-between">
				<div class="card-title mb-0">
					<h5 class="m-0 me-2">Estimates Status</h5>
				</div>
				<div class="dropdown">
					<button class="btn btn-text-secondary rounded-pill p-2 me-n1" type="button" id="estimatesStatus" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="icon-base ti tabler-dots-vertical icon-md text-body-secondary"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-end" aria-labelledby="estimatesStatus">
						<a class="dropdown-item" href="{{ route('estimates.index') }}">View All</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div id="estimatesStatusChart"></div>
				<div class="mt-4">
				<div class="d-flex justify-content-between mb-3">
					<div class="d-flex align-items-center">
						<div class="badge rounded bg-label-primary me-2 p-1"><span class="d-block" style="width:8px;height:8px;"></span></div>
						<span>Fresh</span>
					</div>
					<span class="fw-medium">{{ $estimateStatusData['Fresh'] }}</span>
				</div>
				<div class="d-flex justify-content-between mb-3">
					<div class="d-flex align-items-center">
						<div class="badge rounded bg-label-success me-2 p-1"><span class="d-block" style="width:8px;height:8px;"></span></div>
						<span>Approved</span>
					</div>
					<span class="fw-medium">{{ $estimateStatusData['Approved'] }}</span>
				</div>
				<div class="d-flex justify-content-between mb-3">
					<div class="d-flex align-items-center">
						<div class="badge rounded bg-label-danger me-2 p-1"><span class="d-block" style="width:8px;height:8px;"></span></div>
						<span>Declined</span>
					</div>
					<span class="fw-medium">{{ $estimateStatusData['Declined'] }}</span>
				</div>
				<div class="d-flex justify-content-between">
					<div class="d-flex align-items-center">
						<div class="badge rounded bg-label-secondary me-2 p-1"><span class="d-block" style="width:8px;height:8px;"></span></div>
						<span>Draft</span>
					</div>
					<span class="fw-medium">{{ $estimateStatusData['Draft'] }}</span>
				</div>
				</div>
			</div>
			</div>
		</div>

		<!-- Top Customers by Revenue -->
		<div class="col-xxl-6 col-lg-6">
			<div class="card h-100">
			<div class="card-header d-flex justify-content-between">
				<div class="card-title mb-0">
					<h5 class="mb-1">Top Customers</h5>
					<p class="card-subtitle">By revenue</p>
				</div>
				<div class="dropdown">
					<button class="btn btn-text-secondary rounded-pill p-2 me-n1" type="button" id="topCustomers" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="icon-base ti tabler-dots-vertical icon-md"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-end" aria-labelledby="topCustomers">
						<a class="dropdown-item" href="{{ route('customers.index') }}">View All</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<ul class="p-0 m-0">
					@forelse($topCustomers as $index => $topCustomer)
						<li class="d-flex mb-4 align-items-center">
							<div class="avatar flex-shrink-0 me-3">
								<span class="avatar-initial rounded 
									@if($index == 0) bg-label-warning
									@elseif($index == 1) bg-label-info
									@else bg-label-secondary
									@endif">
									#{{ $index + 1 }}
								</span>
							</div>
							<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
								<div class="me-2">
									<h6 class="mb-0">{{ $topCustomer->customer->first_name }} {{ $topCustomer->customer->last_name }}</h6>
									<small class="text-body-secondary">{{ $topCustomer->customer->email }}</small>
								</div>
								<div class="user-progress">
									<h6 class="text-success mb-0">${{ number_format($topCustomer->total_revenue, 0) }}</h6>
								</div>
							</div>
						</li>
					@empty
						<li class="text-center text-body-secondary py-4">No revenue data available</li>
					@endforelse
				</ul>
			</div>
			</div>
		</div>

		<!-- Recent Customers -->
		<div class="col-xxl-12">
			<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between">
				<div class="card-title mb-0">
					<h5 class="m-0 me-2">Recent Customers</h5>
				</div>
				<div class="d-flex">
					<a href="{{ route('customers.index') }}" class="btn btn-primary btn-sm">
						<i class="ti tabler-eye me-1"></i>
						View All
					</a>
				</div>
			</div>
			<div class="card-datatable table-responsive">
				<table class="table border-top">
				<thead>
					<tr>
						<th>Customer</th>
						<th>Email</th>
						<th>Syncro ID</th>
						<th>View</th>
					</tr>
				</thead>
				<tbody>
					@forelse($recentCustomers as $customer)
						<tr>
							<td>
								<div class="d-flex align-items-center">
								<div class="avatar avatar-sm me-3">
									<span class="avatar-initial rounded-circle bg-label-primary">
									{{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
									</span>
								</div>
								<div>
									<h6 class="mb-0">{{ $customer->first_name }} {{ $customer->last_name }}</h6>
								</div>
								</div>
							</td>
							<td>{{ $customer->email }}</td>
							<td><span class="badge bg-label-info">{{ $customer->syncro_customer_id ?? 'N/A' }}</span></td>
							<td>
								<a href="{{ route('customers.details', $customer->id) }}?tab=syncroDetails" class="btn btn-icon btn-text-info rounded-pill" title="View Details">
									<i class="ti tabler-eye"></i>
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" class="text-center py-4">No customers found</td>
						</tr>
					@endforelse
				</tbody>
				</table>
			</div>
			</div>
		</div>
	</div>
@endsection
@section('page-js')
	<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
	<script>
		'use strict';

		(function() {
			// Monthly Revenue Chart
			const monthlyRevenueChartEl = document.querySelector('#monthlyRevenueChart');
			
			if (monthlyRevenueChartEl) {
				const monthlyRevenueData = @json($monthlyRevenueData);
				const monthLabels = @json($monthLabels);
				const monthlyRevenueChartConfig = {
					chart: {
						height: 300,
						type: 'area',
						toolbar: {
							show: false
						},
						zoom: {
							enabled: false
						}
					},
					series: [{
						name: 'Revenue',
						data: monthlyRevenueData
					}],
					dataLabels: {
						enabled: false
					},
					stroke: {
						curve: 'smooth',
						width: 3
					},
					colors: ['#696cff'],
					fill: {
						type: 'gradient',
						gradient: {
							opacityFrom: 0.6,
							opacityTo: 0.1
						}
					},
					markers: {
						size: 5,
						colors: ['#696cff'],
						strokeColors: '#fff',
						strokeWidth: 2,
						hover: {
							size: 7
						}
					},
					grid: {
						borderColor: '#f1f1f1',
						strokeDashArray: 5,
						xaxis: {
							lines: {
								show: true
							}
						},
						yaxis: {
							lines: {
								show: true
							}
						}
					},
					xaxis: {
						categories: monthLabels,
						labels: {
							style: {
								colors: '#8e8e93',
								fontSize: '13px'
							}
						}
					},
					yaxis: {
						labels: {
							style: {
								colors: '#8e8e93',
								fontSize: '13px'
							},
							formatter: function(value) {
								return '$' + value.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
							}
						}
					},
					tooltip: {
						theme: 'light',
						y: {
							formatter: function(value) {
								return '$' + value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
							}
						}
					},
					responsive: [{
						breakpoint: 1400,
						options: {
							chart: {
								height: 350
							}
						}
					}]
				};
				
				const monthlyRevenueChart = new ApexCharts(monthlyRevenueChartEl, monthlyRevenueChartConfig);
				monthlyRevenueChart.render();
			}

			// Estimates Status Chart
			const estimatesStatusChartEl = document.querySelector('#estimatesStatusChart');
			if (estimatesStatusChartEl) {
				const estimateStatusData = @json($estimateStatusData);
				
				const estimatesStatusChartConfig = {
					chart: {
						height: 200,
						type: 'donut'
					},
					labels: Object.keys(estimateStatusData),
					series: Object.values(estimateStatusData),
					colors: ['#696cff', '#71dd37', '#ff3e1d', '#8592a3'],
					stroke: {
						width: 0
					},
					dataLabels: {
						enabled: false
					},
					legend: {
						show: false
					},
					grid: {
						padding: {
							top: 0,
							bottom: 0,
							right: 15
						}
					},
					plotOptions: {
						pie: {
							donut: {
								size: '70%',
								labels: {
									show: true,
									value: {
										fontSize: '1.5rem',
										fontWeight: 600,
										color: '#566a7f',
										offsetY: -15,
										formatter: function(val) {
											return val;
										}
									},
									name: {
										offsetY: 20,
										fontWeight: 600
									},
									total: {
										show: true,
										fontSize: '.7rem',
										label: 'Total',
										color: '#a1acb8',
										formatter: function(w) {
											return w.globals.seriesTotals.reduce((a, b) => {
												return a + b;
											}, 0);
										}
									}
								}
							}
						}
					}
				};
				
				const estimatesStatusChart = new ApexCharts(estimatesStatusChartEl, estimatesStatusChartConfig);
				estimatesStatusChart.render();
			}
		})();
	</script>
@endsection
