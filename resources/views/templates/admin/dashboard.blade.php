@extends('layouts.app.app')
@include('partials.side-nav')

@section('content')

<div class="container my-4">

    <h4 class="mb-4">Dashboard</h4>

    <div class="row">

      <a href="#" class="col-xl-4 col-md-6 mb-4"  style="text-decoration: none;">
        <div class="card h-100 py-2 border-0 shadow-sm" style="background-color: white; position: static;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Users
                        </div>

                        <div class="h6 mb-1 font-weight-bold text-gray-800">
                            {{ $totalUsers }}
                        </div>
                    </div>
                    <div class="col-auto text-danger icon-dashboard">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <a href="#" class="col-xl-4 col-md-6 mb-4"
    style="text-decoration: none;">
    <div class="card h-100 py-2 border-0 shadow-sm" style="background-color: white; position: static;">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                        Products
                    </div>
                    <div class="h6 mb-1 font-weight-bold text-gray-800">
                        {{ $totalProducts }}
                    </div>
                </div>
                <div class="col-auto text-info icon-dashboard">
                    <i class="fas fa-envelope fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>

<a href="#" target="_blank" class="col-xl-4 col-md-6 mb-4"
style="text-decoration: none;">
<div class="card h-100 py-2 border-0 shadow-sm" style="background-color: white; position: static;">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                    Customers
                </div>
                <div class="h6 mb-1 font-weight-bold text-gray-800">
                    {{ $totalCustomers }}
                </div>
            </div>
            <div class="col-auto text-success icon-dashboard">
                <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>
</div>
</a>

<a href="#" target="_blank" class="col-xl-4 col-md-6 mb-4"
style="text-decoration: none;">
<div class="card h-100 py-2 border-0 shadow-sm" style="background-color: white; position: static;">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                    Invoices
                </div>
                <div class="h6 mb-1 font-weight-bold text-gray-800">
                    {{ $totalInvoices }}
                </div>
            </div>
            <div class="col-auto text-warning icon-dashboard">
                <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>
</div>
</a>

<a href="#" target="_blank" class="col-xl-4 col-md-6 mb-4"
style="text-decoration: none;">
<div class="card h-100 py-2 border-0 shadow-sm" style="background-color: white; position: static;">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                    Total Payments
                </div>
                <div class="h6 mb-1 font-weight-bold text-gray-800">
                    {{ number_format($totalPayments,2) }} Tsh
                </div>
            </div>
            <div class="col-auto text-primary icon-dashboard">
                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>
</div>
</a>

<a href="#" target="_blank" class="col-xl-4 col-md-6 mb-4"
style="text-decoration: none;">
<div class="card h-100 py-2 border-0 shadow-sm" style="background-color: white; position: static;">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                    Total Profit
                </div>
                <div class="h6 mb-1 font-weight-bold text-gray-800">
                    {{ number_format($totalProfit,2) }} Tsh
                </div>
            </div>
            <div class="col-auto text-secondary icon-dashboard">
                <i class="fas fa-money-check-alt fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>
</div>
</a>
</div>


<div class="row mb-4">
    <div class="col-md-6">
        <div class="card p-3 border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6>Monthly Sales</h6>
            </div>
            <div class="card-body">
                <canvas id="monthlySalesChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6>Top 5 Sold Products</h6>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card p-3 border-0 shadow-sm">
    <div class="card-header bg-white">
        <h6>Top Selling Products</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Quantity Sold</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $key => $prod)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $prod->name }}</td>
                    <td>{{ $prod->total_sold }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const monthlyData = [
        @for($i=1; $i<=12; $i++)
        {{ $monthlySales[$i] ?? 0 }},
        @endfor
    ];

    new Chart(document.getElementById('monthlySalesChart'), {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Monthly Sales (Tsh)',
                data: monthlyData,
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    const topProductsLabels = [
        @foreach($topProducts as $prod)
        '{{ $prod->name }}',
        @endforeach
    ];
    const topProductsData = [
        @foreach($topProducts as $prod)
        {{ $prod->total_sold }},
        @endforeach
    ];

    new Chart(document.getElementById('topProductsChart'), {
        type: 'doughnut',
        data: {
            labels: topProductsLabels,
            datasets: [{
                data: topProductsData,
                backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6c757d']
            }]
        },
        options: { responsive: true }
    });
</script>
@stop
