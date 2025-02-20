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

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Rata Rata produksi per Wilayah</strong></h4>
                        <canvas id="averageProduksiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Rata Rata produktivitas</strong></h4>
                        <canvas id="averageProduktivitasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4><strong>Rata Rata Luas lahan</strong></h4>
                        <canvas id="averageLuasLahanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>


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
       >

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
                        backgroundColor: '#98FB98', // Pale Green
                    },
                    {
                        label: 'Cluster Sedang',
                        data: clusterData.map(d => d.cluster2),
                        backgroundColor: '#FFE4B5', // Moccasin
                    },
                    {
                        label: 'Cluster Rendah',
                        data: clusterData.map(d => d.cluster3),
                        backgroundColor: '#FFB6C1', // Light Pink
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

         // Bar Chart for Average Production
         new Chart(document.getElementById('averageProduksiChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: wilayahs,
                datasets: [
                    {
                        label: 'Cluster Tinggi',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[0]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.produksi, 0) / items.length : 0;
                        }),
                        backgroundColor: '#90EE90', // Light Green
                    },
                    {
                        label: 'Cluster Sedang',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[1]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.produksi, 0) / items.length : 0;
                        }),
                        backgroundColor: '#F0E68C', // Khaki
                    },
                    {
                        label: 'Cluster Rendah',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[2]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.produksi, 0) / items.length : 0;
                        }),
                        backgroundColor: '#FFA07A', // Light Salmon
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Wilayah' } },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Rata-rata Produksi (kw)' }
                    }
                }
            }
        });

          // Bar Chart for Average Production
          new Chart(document.getElementById('averageProduktivitasChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: wilayahs,
                datasets: [
                    {
                        label: 'Cluster Tinggi',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[0]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.produktivitas, 0) / items.length : 0;
                        }),
                        backgroundColor: '#90EE90', // Light Green
                    },
                    {
                        label: 'Cluster Sedang',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[1]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.produktivitas, 0) / items.length : 0;
                        }),
                        backgroundColor: '#EEE8AA', // Pale Goldenrod
                    },
                    {
                        label: 'Cluster Rendah',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[2]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.produktivitas, 0) / items.length : 0;
                        }),
                        backgroundColor: '#E9967A', // Dark Salmon
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Wilayah' } },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Rata-rata Produktivitas (kw/ha)' }
                    }
                }
            }
        });

        // Bar Chart for Average Production
        new Chart(document.getElementById('averageLuasLahanChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: wilayahs,
                datasets: [
                    {
                        label: 'Cluster Tinggi',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[0]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.luas_lahan, 0) / items.length : 0;
                        }),
                        backgroundColor: '#8FBC8F', // Dark Sea Green
                    },
                    {
                        label: 'Cluster Sedang',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[1]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.produktivitas, 0) / items.length : 0;
                        }),
                        backgroundColor: '#EEE8AA', // Pale Goldenrod
                    },
                    {
                        label: 'Cluster Rendah',
                        data: wilayahs.map(wilayah => {
                            const items = clusters[2]?.filter(item => item.wilayah.nama_wilayah === wilayah) || [];
                            return items.length ? items.reduce((sum, item) => sum + item.produktivitas, 0) / items.length : 0;
                        }),
                        backgroundColor: '#E9967A', // Dark Salmon
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Wilayah' } },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Rata-rata Luas Lahan (ha)' }
                    }
                }
            }
        });



        $('#liKmeans').addClass('active');
    </script>
@endSection
