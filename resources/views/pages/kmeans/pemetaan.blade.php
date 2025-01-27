@extends('layouts.v_template')

@section('content')
    @if (auth()->user()->role == 'Administrator')
        
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pemetaan</h5>
                        <p class="card-text">Peta ini menunjukkan 3 Cluster yang di tandai dengan masing-masing warna.</p>
                        <div class="d-flex justify-content-between">
                            <div class="cluster-legend">
                                <h6 class="mb-3">Cluster Legend:</h6>
                                @foreach ($clusters as $key => $cluster)
                                    @php
                                        // Calculate average luas_lahan for the cluster
                                        $avgLuasLahan = collect($cluster)->avg('luas_lahan');

                                        // Determine the caption based on average luas_lahan
                                        if ($avgLuasLahan >= 700) {
                                            $caption = 'High';
                                        } elseif ($avgLuasLahan >= 400) {
                                            $caption = 'Medium';
                                        } else {
                                            $caption = 'Low';
                                        }
                                    @endphp
                                    <div class="d-flex align-items-center mb-2 legend-item" style="cursor: pointer;" data-cluster="{{ $key + 1 }}">
                                        <div class="legend-color" style="width: 20px; height: 20px; background-color: #{{ $key == 0 ? 'C00000' : ($key == 1 ? '00B050' : '0066CC') }}; border-radius: 50%;"></div>
                                        <p class="mb-0 ms-2">Cluster {{ $key + 1 }} - {{ $caption }}</p>
                                    </div>
                                @endforeach
                                <div class="d-flex align-items-center mb-2 legend-item" style="cursor: pointer;" data-cluster="all">
                                    <div class="legend-color" style="width: 20px; height: 20px; background-color: #666666; border-radius: 50%;"></div>
                                    <p class="mb-0 ms-2">Show All</p>
                                </div>
                            </div>

                            <div class="horticultural-legend ms-5">
                                <h6 class="mb-3">Jenis Hortikultura:</h6>
                                @foreach ($horticulturalTypes as $type)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="legend-color" style="width: 20px; height: 20px; background-color: {{ $type['color'] }}; border-radius: 50%;"></div>
                                        <p class="mb-0 ms-2">{{ $type['name'] }}</p>
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
        // Create a map centered on Tana Toraja, Indonesia
        var map = L.map('map').setView([-3.0753, 119.7426], 11);

        // Store all polygons in an array for easy access
        let polygons = [];
        let activeCluster = 'all';

        // Add horticultural types data
        let horticulturalTypes = @json($horticulturalTypes);

        // Add a tile layer to the map (OpenStreetMap in this case)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let _coordinates = [];
        let _clusterLabels = []; 
        let _clusterCaptions = [];
        let _clusterLevel = [];
        let _clusterNumbers = []; 
        let _jenisHortikultura = [];

        @foreach ($clusters as $key => $cluster)
            @php
                $avgLuasLahan = collect($cluster)->avg('luas_lahan');
                if ($avgLuasLahan >= 700) {
                    $level = 'High';
                } elseif ($avgLuasLahan >= 400) {
                    $level = 'Medium';
                } else {
                    $level = 'Low';
                }
            @endphp

            @foreach ($cluster as $data)
                _coordinates.push(@json($data['wilayah']->koordinat));
                _clusterLabels.push('Cluster {{ $key + 1 }}');
                _clusterCaptions.push('{{ $data['wilayah']->nama_wilayah }} ({{ $data['wilayah']->lokasi }} ) <br> Luas Lahan : {{ $data['luas_lahan'] }} <br> Produksi : {{ $data['produksi'] }} <br> Produktivitas : {{ $data['produktivitas'] }} <br> Jenis Hortikultura : {{ $data['jenis_hortikultura'] }} <br> Persentase : {{ $data['persentase'] }}');
                _clusterLevel.push('{{ $level }}');
                _clusterNumbers.push({{ $key + 1 }}); 
                _jenisHortikultura.push('{{ $data['jenis_hortikultura'] }}');
            @endforeach
        @endforeach

        function parseCoordinateString(coordString) {
            const cleanedString = coordString
                .replace(/^\[|\]$/g, '')
                .replace(/…/g, '');
            
            const pairs = cleanedString.split('],[');
            
            return pairs.map(pair => {
                const coords = pair.split(',').map(Number);
                return coords;
            });
        }

        const parsedData = _coordinates.map((coords, index) => {
            try {
                return parseCoordinateString(coords);
            } catch (error) {
                console.error(`Error parsing coordinates at index ${index}:`, error);
                return [];
            }
        });

        // Function to get color based on cluster number
        function getClusterColor(clusterNum) {
            const colors = {
                1: '#C00000',
                2: '#00B050',
                3: '#0066CC'
            };
            return colors[clusterNum] || '#666666';
        }

        // Function to update polygon colors based on selected cluster
        function updatePolygonColors(selectedCluster) {
            polygons.forEach((polygon, index) => {
                const clusterNum = _clusterNumbers[index];
                if (selectedCluster === 'all') {
                    polygon.setStyle({
                        fillColor: getClusterColor(clusterNum),
                        color: getClusterColor(clusterNum),
                        fillOpacity: 0.5,
                        weight: 1
                    });
                } else {
                    if (clusterNum === parseInt(selectedCluster)) {
                        polygon.setStyle({
                            fillColor: getClusterColor(clusterNum),
                            color: getClusterColor(clusterNum),
                            fillOpacity: 0.5,
                            weight: 1
                        });
                    } else {
                        polygon.setStyle({
                            fillColor: '#666666',
                            color: '#666666',
                            fillOpacity: 0.2,
                            weight: 1
                        });
                    }
                }
            });
        }

        parsedData.forEach((polygonCoords, index) => {
            if (!polygonCoords || polygonCoords.length === 0) {
                return;
            }

            // Find the horticultural type color
            const jenisHortikultura = _jenisHortikultura[index];
            const horticulturalType = horticulturalTypes.find(type => type.name === jenisHortikultura);
            const borderColor = horticulturalType ? horticulturalType.color : '#000000';

            // Create the polygon with cluster fill color and horticultural type border
            const correctedPolygonCoords = polygonCoords.map(coordPair => {
                if (!Array.isArray(coordPair) || coordPair.length !== 2 || 
                    !isFinite(coordPair[0]) || !isFinite(coordPair[1])) {
                    return null;
                }
                return [coordPair[1], coordPair[0]];
            }).filter(coord => coord !== null);

            if (correctedPolygonCoords.length === 0) {
                return;
            }

            const clusterNum = _clusterNumbers[index];
            const color = getClusterColor(clusterNum);

            try {
                const polygon = L.polygon(correctedPolygonCoords, {
                    color: borderColor,
                    weight: 3,
                    fillColor: color,
                    fillOpacity: 0.5
                })
                .bindPopup(_clusterLevel[index] + ' - ' + _clusterLabels[index] + '<br>' + _clusterCaptions[index])
                .addTo(map);

                polygons.push(polygon);
            } catch (error) {
                console.error(`Error creating polygon at index ${index}:`, error);
            }
        });

        // Add click event listeners to legend items
        document.querySelectorAll('.legend-item').forEach(item => {
            item.addEventListener('click', function() {
                const selectedCluster = this.dataset.cluster;
                activeCluster = selectedCluster;
                updatePolygonColors(selectedCluster);
                
                // Update legend item appearances
                document.querySelectorAll('.legend-item').forEach(legendItem => {
                    if (legendItem.dataset.cluster === selectedCluster) {
                        legendItem.style.opacity = '1';
                    } else {
                        legendItem.style.opacity = '0.5';
                    }
                });
            });
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
