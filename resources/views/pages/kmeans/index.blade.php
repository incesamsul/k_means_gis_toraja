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
                                    <div class="alert alert-info text-white">
                                        <h6>Perhitungan Segmen Data:</h6>
                                        <p>Total Data (n) = {{ count($data) }}<br>
                                        Jumlah Cluster (k) = {{ $k }}<br>
                                        Ukuran Segmen = floor(n/k) = floor({{ count($data) }}/{{ $k }}) = {{ floor(count($data)/$k) }}</p>

                                        @foreach ($iteration['centroids'] as $idx => $centroid)
                                        <div class="mb-2">
                                            <strong>Cluster {{ $idx + 1 }}:</strong><br>
                                            <small>
                                                {{ $centroid['segment_info']['formula'] }}<br>
                                                Rata-rata Luas Lahan = (Σ Luas Lahan dalam segmen) / {{ $centroid['segment_info']['end'] - $centroid['segment_info']['start'] + 1 }} = {{ number_format($centroid['luas_lahan'], 2) }}<br>
                                                Rata-rata Produksi = (Σ Produksi dalam segmen) / {{ $centroid['segment_info']['end'] - $centroid['segment_info']['start'] + 1 }} = {{ number_format($centroid['produksi'], 2) }}<br>
                                                Rata-rata Produktivitas = (Σ Produktivitas dalam segmen) / {{ $centroid['segment_info']['end'] - $centroid['segment_info']['start'] + 1 }} = {{ number_format($centroid['produktivitas'], 2) }}
                                            </small>
                                        </div>
                                        @endforeach
                                    </div>
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

                                <!-- Distance Calculations -->
                                <div class="card shadow-sm mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0">Perhitungan Jarak Euclidean</h6>
                                    </div>

                                    <div class="card-body p-3">
                                        <!-- Formula Explanation -->
                                        <div class="alert alert-info py-2 px-3 mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h6 class="mb-0">Formula:</h6>
                                                <span class="badge badge-light">d = √[(x₁-x₂)² + (y₁-y₂)² + (z₁-z₂)²]</span>
                                            </div>
                                            <div class="row g-0">
                                                <div class="col-auto"><small class="text-muted">Dimana:</small></div>
                                                <div class="col ps-2">
                                                    <div class="row g-0">
                                                        <div class="col-auto pe-3"><small>x = Luas Lahan (ha)</small></div>
                                                        <div class="col-auto pe-3"><small>y = Produksi (kw)</small></div>
                                                        <div class="col-auto"><small>z = Produktivitas (kw/ha)</small></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Data Point</th>
                                                        <th>Cluster Assignment</th>
                                                    @foreach ($iteration['centroids'] as $index => $centroid)
                                                        <th>Distance to C{{ $index + 1 }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tanaman as $index => $point)
                                                    <tr>
                                                        <td>{{ $point->wilayah->nama_wilayah }}</td>
                                                        @php
                                                            $distances = [];
                                                            foreach ($iteration['centroids'] as $centroidIndex => $centroid) {
                                                                // Calculate individual components
                                                                $luasLahanDiff = pow($point->luas_lahan - $centroid['luas_lahan'], 2);
                                                                $produksiDiff = pow($point->produksi - $centroid['produksi'], 2);
                                                                $produktivitasDiff = pow($point->produktivitas - $centroid['produktivitas'], 2);

                                                                // Calculate final distance
                                                                $dist = sqrt($luasLahanDiff + $produksiDiff + $produktivitasDiff);

                                                                // Store calculation details
                                                                $calculationDetails = [
                                                                    'point' => [
                                                                        'luas_lahan' => $point->luas_lahan,
                                                                        'produksi' => $point->produksi,
                                                                        'produktivitas' => $point->produktivitas
                                                                    ],
                                                                    'centroid' => $centroid,
                                                                    'diffs' => [
                                                                        'luas_lahan' => $luasLahanDiff,
                                                                        'produksi' => $produksiDiff,
                                                                        'produktivitas' => $produktivitasDiff
                                                                    ],
                                                                    'final' => $dist
                                                                ];
                                                                $distances[$centroidIndex] = ['value' => $dist, 'details' => $calculationDetails];
                                                            }
                                                            $minIndex = array_search(min(array_column($distances, 'value')), array_column($distances, 'value'));
                                                        @endphp
                                                        <td>C{{ $minIndex + 1 }}</td>
                                                        @foreach ($distances as $index => $dist)
                                                            <td>
                                                            <strong>{{ number_format($dist['value'], 2) }}</strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                √[<br>
                                                                ({{ number_format($dist['details']['point']['luas_lahan'], 2) }} - {{ number_format($dist['details']['centroid']['luas_lahan'], 2) }})² = {{ number_format($dist['details']['diffs']['luas_lahan'], 2) }}<br>
                                                                + ({{ number_format($dist['details']['point']['produksi'], 2) }} - {{ number_format($dist['details']['centroid']['produksi'], 2) }})² = {{ number_format($dist['details']['diffs']['produksi'], 2) }}<br>
                                                                + ({{ number_format($dist['details']['point']['produktivitas'], 2) }} - {{ number_format($dist['details']['centroid']['produktivitas'], 2) }})² = {{ number_format($dist['details']['diffs']['produktivitas'], 2) }}<br>
                                                                ]<br>
                                                            </small>
                                                        </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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



        $('#liKmeans').addClass('active');
    </script>
@endSection
