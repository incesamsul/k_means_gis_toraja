@extends('layouts.v_template')

@section('content')
    @if (auth()->user()->role == 'Administrator')
        <!-- K-means Process Details -->
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
                                                    <th>Luas Lahan</th>
                                                    <th>Produksi</th>
                                                    <th>Produktivitas</th>
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
                                                    <th>Luas Lahan</th>
                                                    <th>Produksi</th>
                                                    <th>Produktivitas</th>
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
    {{-- //hasil klasifikasi --}}

    

    @if (auth()->user()->role == 'Administrator')
        @foreach ($clusters as $key => $row)
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Hasil klasifikasi</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Kelmopok : {{ $loop->iteration }}</strong></p>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Wilayah</th>
                                        <th>Luas Lahan</th>
                                        <th>Produksi</th>
                                        <th>Produktivitas</th>
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
                        <table class="table" id="table-data">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Luas Lahan</th>
                                    <th>Produksi</th>
                                    <th>Produktivitas</th>
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
                                        <td>{{ $row->jenis_hortikultura }}</td>
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

    @if (auth()->user()->role == 'user')
        <div class="row">
            <h4><strong>Data row</strong></h5>
                <p><small>List data row dibawah ini</small></p>
                @foreach ($tanaman as $row)
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <img src="{{ asset('storage/tanaman/' . $row->image) }}" class="card-img-top"
                                alt="{{ $row->name }}"
                                style="width: 100%; height: 300px; object-fit: cover; object-position: center;">
                            <div class="card-body pb-2">
                                <h5 class="card-title">{{ $row->name }}</h5>
                                <p class="card-text">A{{ $loop->iteration }}</p>
                                <div class="flex flex-row">
                                    <a href="{{ route('tanaman.show', $row->id) }}"
                                        class="btn bg-main text-white py-3  btn-sm">Detail</a>
                                    <a href="{{ URL::to('/pilih-row' . '/' . $row->id) }}"
                                        class="btn bg-warning text-white py-3  btn-sm">Pilih</a>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
        </div>
    @endif



@endsection

@section('script')
    <script>
        // table data
        var table = $('#table-data').DataTable({
            "lengthChange": false,
            "responsive": true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });

        $('#liKmeans').addClass('active');
    </script>
@endSection
