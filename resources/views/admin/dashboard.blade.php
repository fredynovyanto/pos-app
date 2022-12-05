@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">category</i>
                </div>
                <p class="card-category">Kategori</p>
                <h3 class="card-title">{{ $category }}</h3>
                </div>
                <div class="card-footer">
                <div class="stats">
                    <a href="{{ route('categories.index')}}" class="d-flex justify-content-center"><i class="material-icons">category</i>Lihat kategori</a>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">inventory</i>
                </div>
                <p class="card-category">Produk</p>
                <h3 class="card-title">{{ $product }}</h3>
                </div>
                <div class="card-footer">
                <div class="stats ">
                    <a href="{{ route('products.index')}}" class="d-flex justify-content-center"><i class="material-icons">inventory</i>Lihat produk</a>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">local_shipping</i>
                </div>
                <p class="card-category">Supplier</p>
                <h3 class="card-title">{{ $supplier }}</h3>
                </div>
                <div class="card-footer">
                <div class="stats ">
                    <a href="{{ route('suppliers.index')}}" class="d-flex justify-content-center"><i class="material-icons">local_shipping</i>Lihat supplier</a>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
                <i class="material-icons">people_alt</i>
            </div>
            <p class="card-category">User</p>
            <h3 class="card-title">{{ $user }}</h3>
            </div>
            <div class="card-footer">
                <div class="stats ">
                    <a href="{{ route('users.index')}}" class="d-flex justify-content-center"><i class="material-icons">people_alt</i>Lihat user</a>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Grafik Pendapatan {{ tanggal_indonesia($tanggal_awal, false) }} s/d {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="chart">
                                <!-- Sales Chart Canvas -->
                                <canvas id="salesChart" style="height: 280px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@push('scripts')
<!-- ChartJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.1.1/Chart.js"></script>
<script>
$(function() {
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var salesChart = new Chart(salesChartCanvas);
    var salesChartData = {
        labels: {{ json_encode($data_tanggal) }},
        datasets: [
            {
                label: 'Pendapatan',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: {{ json_encode($data_pendapatan) }}
            }
        ]
    };
    var salesChartOptions = {
        pointDot : false,
        responsive : true
    };
    salesChart.Line(salesChartData, salesChartOptions);
});
</script>
@endpush