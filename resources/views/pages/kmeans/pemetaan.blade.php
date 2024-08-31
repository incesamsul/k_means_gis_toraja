@extends('layouts.v_template')

@section('content')
    @if (auth()->user()->role == 'Administrator')
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pemetaan</h5>
                        <p class="card-text">Peta ini menunjukkan 5 Cluster yang di tandai dengan masing-masing warna.</p>
                        <div class="d-flex ">
                            <div class="">
                                @for ($i = 1; $i <= 5; $i++)
                                <div class="d-flex align-items-center mb-2">
                                    <div style="width: 20px; height: 20px; background-color: #{{ $i == 1 ? 'C00000' : ($i == 2 ? '00B050' : ($i == 3 ? '0066CC' : ($i == 4 ? 'FFC000' : 'C000C5'))) }}; border-radius: 50%;"></div>
                                    <p class="mb-0 ms-2">Cluster {{ $i }}</p>
                                </div>
                                @endfor
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

        @foreach ($clusters as $key => $cluster)
            @foreach ($cluster as $data)
                _coordinates.push(@json($data['wilayah']->koordinat));
                _clusterLabels.push('Cluster {{ $key + 1 }}'); // Example label for cluster
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

            const color = colors[index];
            L.polygon(correctedPolygonCoords, {
                    color: color,
                    weight: 0,
                    fillColor: color,
                    fillOpacity: 0.5
                })
                .bindPopup(_clusterLabels[index])
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
