@extends('layouts.v_template')

@section('content')
    @if (auth()->user()->role == 'Administrator')
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pemetaan</h5>
                        <p class="card-text">Peta ini menunjukkan 3 Cluster yang di tandai dengan masing-masing warna.</p>
                        <div class="d-flex ">
                            <div class="">
                                @foreach ($clusters as $key => $row)
    @php
        // Assuming 'luas_lahan' is the primary attribute for determining high, medium, and low
        $luasLahan = $row[0]['luas_lahan'];

        // Determine the caption based on 'luas_lahan'
        if ($luasLahan >= 700) {
            $caption = 'High';
        } elseif ($luasLahan >= 400) {
            $caption = 'Medium';
        } else {
            $caption = 'Low';
        }
    @endphp
    <div class="d-flex align-items-center mb-2">
        <div style="width: 20px; height: 20px; background-color: #{{ $key == 1 ? 'C00000' : ($key == 2 ? '00B050' : ($key == 3 ? '0066CC' : ($key == 4 ? 'FFC000' : 'C000C5'))) }}; border-radius: 50%;"></div>
        <p class="mb-0 ms-2">Cluster {{ $key + 1 }}  - {{ $caption }}</p>
    </div>
@endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="map" style="height: 700px;"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Create a map centered on Makassar, Indonesia
        var map = L.map('map').setView([-5.147665, 119.432731], 13);

        // Add a tile layer to the map (OpenStreetMap in this case)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let _coordinates = [];
        let _clusterLabels = []; // To hold the labels for clusters
        let _clusterCaptions = [];
        let _clusterLevel = [];

        @foreach ($clusters as $key => $cluster)
            @foreach ($cluster as $data)
            console.log(@json($data))
                _coordinates.push(@json($data['wilayah']->koordinat));
                _clusterLabels.push('Cluster {{ $key + 1 }}'); // Example label for cluster
                _clusterCaptions.push('{{ $data['wilayah']->nama_wilayah }} ({{ $data['wilayah']->lokasi }} ) <br> Luas Lahan : {{ $data['luas_lahan'] }} <br> Produksi : {{ $data['produksi'] }} <br> Produktivitas : {{ $data['produktivitas'] }} <br> Jenis Hortikultura : {{ $data['jenis_hortikultura'] }} <br> Persentase : {{ $data['persentase'] }}');
                if('{{  $data['luas_lahan'] >= 700 }}') {
                    _clusterLevel.push('High');
                } else if('{{  $data['luas_lahan'] >= 400 }}') {
                    _clusterLevel.push('Medium');
                } else {
                    _clusterLevel.push('Low');
                }
            @endforeach
        @endforeach

        function parseCoordinateString(coordString) {
            const cleanedString = coordString
                .replace(/^\[|\]$/g, '')
                .replace(/…/g, '');

            return cleanedString.split('],[').map(pair => {
                return pair.split(',').map(Number);
            });
        }

        const parsedData = _coordinates.map(parseCoordinateString);
        const colors = [
            '#C00000', // Red
            '#00B050', // Green
            '#0066CC', // Blue
            '#FFC000', // Orange
            '#C000C5', // Purple
        ];

        parsedData.forEach((polygonCoords, index) => {
            // Swap [longitude, latitude] to [latitude, longitude]
            const correctedPolygonCoords = polygonCoords.map(coordPair => [coordPair[1], coordPair[0]]);

            let color = '#00B050';

            if(_clusterLabels[index] == 'Cluster 1') {
                color = '#C000C5';
            } else if(_clusterLabels[index] == 'Cluster 2') {
                color = '#C00000';
            } else if(_clusterLabels[index] == 'Cluster 3') {
                color = '#00B050';
            } else {
                color = '#FFC000';
            }
            L.polygon(correctedPolygonCoords, {
                    color: color,
                    weight: 0,
                    fillColor: color,
                    fillOpacity: 0.5
                })
                .bindPopup(_clusterLevel[index] + '-' + _clusterLabels[index] + '<br>' + _clusterCaptions[index])
                .addTo(map);
        });
    </script>


    <script>
        var table = $('#table-data').DataTable({
            "lengthChange": false,
            "responsive": true,
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf']
        });

        $('#liPemetaan').addClass('active');
    </script>
@endSection
