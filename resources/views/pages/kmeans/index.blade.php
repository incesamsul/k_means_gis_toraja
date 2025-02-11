@extends('layouts.v_template')

@section('content')
    @if (true)
        {{-- Charts Dashboard Section --}}
        {{-- Bar Chart (Full Width) --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Distribusi Cluster per Wilayah</strong></h4>
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="row mt-4">
            {{-- Pie Chart --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Proporsi Cluster (Pie)</strong></h4>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
            {{-- Doughnut Chart --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Proporsi Cluster (Doughnut)</strong></h4>
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            {{-- Line Chart --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Tren Nilai Rata-rata per Cluster</strong></h4>
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
            {{-- Polar Area Chart --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Distribusi Cluster (Polar Area)</strong></h4>
                        <canvas id="polarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            {{-- Radar Chart --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Perbandingan Metrik Cluster</strong></h4>
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>
            </div>
            {{-- Bubble Chart --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Bubble Chart Metrik</strong></h4>
                        <canvas id="bubbleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stacked Bar Chart --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Distribusi Stacked per Wilayah</strong></h4>
                        <canvas id="stackedBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- K-means Process Details -->
        @if (auth()->user()->role == 'Administrator')
        <div class="row mt-4">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Proses Perhitungan K-means</strong></h4>

                        @foreach ($iterations as $iteration)
                            <div class="mb-4">
                                <h5 class="text-primary">Iterasi {{ $iteration['iteration'] }}</h5>

                                <!-- Current Centroids -->
                                <div class="mb-3">
                                    <h6>Centroid Awal:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Cluster</th>
                                                    <th>Luas Lahan (ha)</th>
                                                    <th>Produksi (kw)</th>
                                                    <th>Produktivitas (kw/ha)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($iteration['centroids'] as $index => $centroid)
                                                    <tr>
                                                        <td>C{{ $index + 1 }}</td>
                                                        <td>{{ number_format($centroid['luas_lahan'], 2) }}</td>
                                                        <td>{{ number_format($centroid['produksi'], 2) }}</td>
                                                        <td>{{ number_format($centroid['produktivitas'], 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Cluster Assignment -->
                                <div class="mb-3">
                                    <h6>Pengelompokan Data:</h6>
                                    @foreach ($iteration['clusters'] as $index => $cluster)
                                        <p>Cluster {{ $index + 1 }}: {{ count($cluster) }} anggota</p>
                                    @endforeach
                                </div>

                                <!-- New Centroids -->
                                <div class="mb-3">
                                    <h6>Centroid Baru:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Cluster</th>
                                                    <th>Luas Lahan (ha)</th>
                                                    <th>Produksi (kw)</th>
                                                    <th>Produktivitas (kw/ha)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($iteration['new_centroids'] as $index => $centroid)
                                                    <tr>
                                                        <td>C{{ $index + 1 }}</td>
                                                        <td>{{ number_format($centroid['luas_lahan'], 2) }}</td>
                                                        <td>{{ number_format($centroid['produksi'], 2) }}</td>
                                                        <td>{{ number_format($centroid['produktivitas'], 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- {{-- Chart Section --}}
        <div class="row mt-4">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Visualisasi Cluster per Wilayah</strong></h4>
                        <canvas id="clusterChart"></canvas>
                    </div>
                </div>
            </div>
        </div> -->

        @foreach ($clusters as $key => $row)
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Hasil klasifikasi</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Kelmopok : {{ $loop->iteration }}</strong></p>
                            <table class="table table-striped table-data">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Wilayah</th>
                                        <th>Luas Lahan (ha)</th>
                                        <th>Produksi (kw)</th>
                                        <th>Produktivitas (kw/ha)</th>
                                        <th>Jenis Horikultura</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($row as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data['wilayah']->nama_wilayah }}</td>
                                            <td>{{ $data['luas_lahan'] }}</td>
                                            <td>{{ $data['produksi'] }}</td>
                                            <td>{{ $data['produktivitas'] }}</td>
                                            <td>{{ $data['jenis_hortikultura'] }}</td>
                                            <td>{{ $data['persentase'] }}</td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row mt-5">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5>tanaman</h5>
                        <table class="table table-data" id="table-data">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Luas Lahan (ha)</th>
                                    <th>Produksi (kw)</th>
                                    <th>Produktivitas (kw/ha)</th>
                                    <th>Jenis Horikultura</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tanaman as $row)
                                    <tr>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->luas_lahan }}</td>
                                        <td>{{ $row->produksi }}</td>
                                        <td>{{ $row->produktivitas }}</td>
                                        <td>{{ $row->jenis_horikultura }}</td>
                                        <td>{{ $row->persentase }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif





@endsection

@section('script')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

var table = $('.table-data').DataTable({
    "lengthChange": false,
    "responsive": true,
    dom: @if(auth()->user()->role == 'Administrator' || auth()->user()->role == 'kepala_desa')
        '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    @else
        '<"row"<"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    @endif,
    @if(auth()->user()->role == 'Administrator' || auth()->user()->role == 'kepala_desa')
    buttons: [
        {
            extend: 'copy',
            className: 'btn btn-primary btn-sm'
        },
        {
            extend: 'excel',
            className: 'btn btn-primary btn-sm'
        },
        {
            extend: 'pdf',
            className: 'btn btn-primary btn-sm'
        }
    ]
    @endif
});

        // Prepare data for the charts
        const clusters = @json($clusters);

        // Get unique wilayah names
        const wilayahs = [...new Set(
            clusters.flatMap(cluster =>
                cluster.map(item => item.wilayah.nama_wilayah)
            )
        )].sort();

        // Count items per wilayah per cluster
        const clusterData = wilayahs.map(wilayah => {
            return {
                wilayah: wilayah,
                cluster1: clusters[0]?.filter(item => item.wilayah.nama_wilayah === wilayah).length || 0,
                cluster2: clusters[1]?.filter(item => item.wilayah.nama_wilayah === wilayah).length || 0,
                cluster3: clusters[2]?.filter(item => item.wilayah.nama_wilayah === wilayah).length || 0,
            };
        });

        // Calculate cluster totals for pie chart
        const clusterTotals = clusters.map(cluster => cluster.length);

        // Calculate average metrics per cluster
        const clusterAverages = clusters.map(cluster => {
            const avgLuas = cluster.reduce((sum, item) => sum + item.luas_lahan, 0) / cluster.length;
            const avgProduksi = cluster.reduce((sum, item) => sum + item.produksi, 0) / cluster.length;
            const avgProduktivitas = cluster.reduce((sum, item) => sum + item.produktivitas, 0) / cluster.length;
            return { avgLuas, avgProduksi, avgProduktivitas };
        });

        // Bar Chart (Grouped)
        new Chart(document.getElementById('barChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: wilayahs,
                datasets: [
                    {
                        label: 'Cluster Tinggi',
                        data: clusterData.map(d => d.cluster1),
                        backgroundColor: '#00FF00',
                    },
                    {
                        label: 'Cluster Sedang',
                        data: clusterData.map(d => d.cluster2),
                        backgroundColor: '#FFFF00',
                    },
                    {
                        label: 'Cluster Rendah',
                        data: clusterData.map(d => d.cluster3),
                        backgroundColor: '#FF0000',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Wilayah' } },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Data' }
                    }
                }
            }
        });

        // Pie Chart
        new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Cluster Tinggi', 'Cluster Sedang', 'Cluster Rendah'],
                datasets: [{
                    data: clusterTotals,
                    backgroundColor: ['#FF9933', '#33CC33', '#3366CC'],
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Doughnut Chart
        new Chart(document.getElementById('doughnutChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Cluster Tinggi', 'Cluster Sedang', 'Cluster Rendah'],
                datasets: [{
                    data: clusterTotals,
                    backgroundColor: ['#FF9933', '#33CC33', '#3366CC'],
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Line Chart
        new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Luas Lahan', 'Produksi', 'Produktivitas'],
                datasets: [
                    {
                        label: 'Cluster Tinggi',
                        data: [
                            clusterAverages[0].avgLuas,
                            clusterAverages[0].avgProduksi,
                            clusterAverages[0].avgProduktivitas
                        ],
                        borderColor: '#FF9933',
                        fill: false
                    },
                    {
                        label: 'Cluster Sedang',
                        data: [
                            clusterAverages[1].avgLuas,
                            clusterAverages[1].avgProduksi,
                            clusterAverages[1].avgProduktivitas
                        ],
                        borderColor: '#33CC33',
                        fill: false
                    },
                    {
                        label: 'Cluster Rendah',
                        data: [
                            clusterAverages[2].avgLuas,
                            clusterAverages[2].avgProduksi,
                            clusterAverages[2].avgProduktivitas
                        ],
                        borderColor: '#3366CC',
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Nilai Rata-rata' }
                    }
                }
            }
        });

        // Polar Area Chart
        new Chart(document.getElementById('polarChart').getContext('2d'), {
            type: 'polarArea',
            data: {
                labels: ['Cluster Tinggi', 'Cluster Sedang', 'Cluster Rendah'],
                datasets: [{
                    data: clusterTotals,
                    backgroundColor: ['rgba(255, 153, 51, 0.7)', 'rgba(51, 204, 51, 0.7)', 'rgba(51, 102, 204, 0.7)'],
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Radar Chart
        new Chart(document.getElementById('radarChart').getContext('2d'), {
            type: 'radar',
            data: {
                labels: ['Luas Lahan', 'Produksi', 'Produktivitas'],
                datasets: [
                    {
                        label: 'Cluster Tinggi',
                        data: [
                            clusterAverages[0].avgLuas,
                            clusterAverages[0].avgProduksi,
                            clusterAverages[0].avgProduktivitas
                        ],
                        backgroundColor: 'rgba(255, 153, 51, 0.2)',
                        borderColor: '#FF9933',
                        pointBackgroundColor: '#FF9933'
                    },
                    {
                        label: 'Cluster Sedang',
                        data: [
                            clusterAverages[1].avgLuas,
                            clusterAverages[1].avgProduksi,
                            clusterAverages[1].avgProduktivitas
                        ],
                        backgroundColor: 'rgba(51, 204, 51, 0.2)',
                        borderColor: '#33CC33',
                        pointBackgroundColor: '#33CC33'
                    },
                    {
                        label: 'Cluster Rendah',
                        data: [
                            clusterAverages[2].avgLuas,
                            clusterAverages[2].avgProduksi,
                            clusterAverages[2].avgProduktivitas
                        ],
                        backgroundColor: 'rgba(51, 102, 204, 0.2)',
                        borderColor: '#3366CC',
                        pointBackgroundColor: '#3366CC'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { r: { beginAtZero: true } }
            }
        });

        // Bubble Chart
        new Chart(document.getElementById('bubbleChart').getContext('2d'), {
            type: 'bubble',
            data: {
                datasets: [
                    {
                        label: 'Cluster Tinggi',
                        data: [{
                            x: clusterAverages[0].avgLuas,
                            y: clusterAverages[0].avgProduksi,
                            r: clusterAverages[0].avgProduktivitas / 10
                        }],
                        backgroundColor: 'rgba(255, 153, 51, 0.7)'
                    },
                    {
                        label: 'Cluster Sedang',
                        data: [{
                            x: clusterAverages[1].avgLuas,
                            y: clusterAverages[1].avgProduksi,
                            r: clusterAverages[1].avgProduktivitas / 10
                        }],
                        backgroundColor: 'rgba(51, 204, 51, 0.7)'
                    },
                    {
                        label: 'Cluster Rendah',
                        data: [{
                            x: clusterAverages[2].avgLuas,
                            y: clusterAverages[2].avgProduksi,
                            r: clusterAverages[2].avgProduktivitas / 10
                        }],
                        backgroundColor: 'rgba(51, 102, 204, 0.7)'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: { display: true, text: 'Luas Lahan' }
                    },
                    y: {
                        title: { display: true, text: 'Produksi' }
                    }
                }
            }
        });

        // Stacked Bar Chart
        new Chart(document.getElementById('stackedBarChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: wilayahs,
                datasets: [
                    {
                        label: 'Cluster Tinggi',
                        data: clusterData.map(d => d.cluster1),
                        backgroundColor: '#FF9933',
                    },
                    {
                        label: 'Cluster Sedang',
                        data: clusterData.map(d => d.cluster2),
                        backgroundColor: '#33CC33',
                    },
                    {
                        label: 'Cluster Rendah',
                        data: clusterData.map(d => d.cluster3),
                        backgroundColor: '#3366CC',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: { display: true, text: 'Wilayah' }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Data' }
                    }
                }
            }
        });



        $('#liKmeans').addClass('active');
    </script>
@endSection
