@extends('layouts.v_template')

@section('content')
    @if (true)

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
                                        // Determine the caption based on cluster number instead of avgLuasLahan
                                        if ($key + 1 == 3) {
                                            $caption = 'High';
                                            $color = '98FB98';
                                        } elseif ($key + 1 == 2) {
                                            $caption = 'Medium';
                                            $color = 'FFE4B5';
                                        } else {
                                            $caption = 'Low';
                                            $color = 'FFB6C1';
                                        }
                                        // Count the number of data points in this cluster
                                        $count = count($cluster);
                                    @endphp
                                    <div class="d-flex align-items-center mb-2 legend-item" style="cursor: pointer;" data-cluster="{{ $key + 1 }}">
                                        <div class="legend-color" style="width: 24px; height: 16px; background-color: #{{ $color }}; border-radius: 2px;"></div>
                                        <p class="mb-0 ms-2">Cluster {{ $key + 1 }} - {{ $caption }} ({{ $count }} data)</p>
                                    </div>
                                @endforeach
                                <!-- Add Normal view option -->
                                <div class="d-flex align-items-center mb-2 legend-item" style="cursor: pointer;" data-cluster="normal">
                                    <div class="legend-color" style="width: 24px; height: 16px; background-color: #808080; border-radius: 2px;"></div>
                                    <p class="mb-0 ms-2">Normal</p>
                                </div>
                                <div class="d-flex align-items-center mb-2 legend-item" style="cursor: pointer;" data-cluster="all">
                                    <div class="legend-color" style="width: 24px; height: 16px; background-color: #666666; border-radius: 2px;"></div>
                                    <p class="mb-0 ms-2">Show All</p>
                                </div>
                            </div>

                            <div class="horticultural-legend ms-5">
                                <h6 class="mb-3">Jenis Hortikultura:</h6>
                                @foreach ($horticulturalTypes as $type)
                                    <div class="d-flex align-items-center mb-2 hort-legend-item" style="cursor: pointer;" data-jenis="{{ $type['name'] }}">
                                        <div class="legend-color" style="width: 24px; height: 16px; background-color: {{ $type['color'] }}; border-radius: 2px;"></div>
                                        <p class="mb-0 ms-2">{{ $type['name'] }}</p>
                                    </div>
                                @endforeach
                                <div class="d-flex align-items-center mb-2 hort-legend-item" style="cursor: pointer;" data-jenis="all">
                                    <div class="legend-color" style="width: 24px; height: 16px; background-color: #666666; border-radius: 2px;"></div>
                                    <p class="mb-0 ms-2">Show All Types</p>
                                </div>
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
        // Initialize map
        var map = L.map('map').setView([-3.0753, 119.7426], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Initialize variables
        var polygons = [];
        var activeCluster = 'all';
        var activeHortType = 'all';
        var horticulturalTypes = @json($horticulturalTypes);
        var polygonData = {};

        // Process data and create polygons
        @foreach ($clusters as $clusterIndex => $cluster)
            @php
                // Assign level based on cluster number
                $level = $clusterIndex + 1 == 3 ? 'High' : ($clusterIndex + 1 == 2 ? 'Medium' : 'Low');
            @endphp

            @foreach ($cluster as $data)
                @php
                    $wilayahId = $data['wilayah']->id;
                @endphp

                if (!polygonData['{{ $wilayahId }}']) {
                    polygonData['{{ $wilayahId }}'] = {
                        id: '{{ $wilayahId }}',
                        nama: '{{ $data['wilayah']->nama_wilayah }}',
                        lokasi: '{{ $data['wilayah']->lokasi }}',
                        koordinat: @json($data['wilayah']->koordinat),
                        dataPoints: []
                    };
                }

                polygonData['{{ $wilayahId }}'].dataPoints.push({
                    cluster: {{ $clusterIndex + 1 }},
                    level: '{{ $level }}',
                    luas_lahan: {{ $data['luas_lahan'] }},
                    produksi: {{ $data['produksi'] }},
                    produktivitas: {{ $data['produktivitas'] }},
                    jenis_hortikultura: '{{ $data['jenis_hortikultura'] }}',
                    persentase: '{{ $data['persentase'] }}'
                });
            @endforeach
        @endforeach

        // Helper function to parse coordinates
        function parseCoordinates(coordString) {
            try {
                return coordString
                    .replace(/^\[|\]$/g, '')
                    .replace(/…/g, '')
                    .split('],[')
                    .map(pair => pair.split(',').map(Number))
                    .map(pair => [pair[1], pair[0]])
                    .filter(pair => pair.every(coord => !isNaN(coord) && isFinite(coord)));
            } catch (error) {
                console.error('Error parsing coordinates:', error);
                return [];
            }
        }

        // Create polygons on the map
        Object.values(polygonData).forEach(data => {
            const coords = parseCoordinates(data.koordinat);
            if (coords.length === 0) return;

            const polygon = L.polygon(coords, {
                color: '#666666',
                weight: 2,
                fillColor: '#666666',
                fillOpacity: 0.5
            }).addTo(map);

            polygons.push({
                element: polygon,
                data: data
            });
        });

        // Get color for cluster
        function getClusterColor(cluster) {
            switch(cluster) {
                case 1: return '#FFB6C1'; // Low - Light Pink
                case 2: return '#FFE4B5'; // Medium - Moccasin
                case 3: return '#98FB98'; // High - Pale Green
                default: return '#666666';
            }
        }

        // Create popup content
        function createPopupContent(data) {
            let content = `<strong>${data.nama}</strong> (${data.lokasi})<br><br>`;

            const filteredPoints = data.dataPoints.filter(point => {
                const clusterMatch = activeCluster === 'all' || point.cluster === parseInt(activeCluster);
                const hortMatch = activeHortType === 'all' || point.jenis_hortikultura === activeHortType;
                return clusterMatch && hortMatch;
            });

            if (filteredPoints.length === 0) {
                return 'No matching data for current filters';
            }

            filteredPoints.forEach(point => {
                content += `<strong>Cluster ${point.cluster} (${point.level})</strong><br>`;
                content += `Luas Lahan: ${point.luas_lahan}<br>`;
                content += `Produksi: ${point.produksi}<br>`;
                content += `Produktivitas: ${point.produktivitas}<br>`;
                content += `Jenis Hortikultura: ${point.jenis_hortikultura}<br>`;
                content += `Persentase: ${point.persentase}<br><br>`;
            });

            return content;
        }

        // Update polygon visibility and styles
        function updatePolygons() {
            polygons.forEach(({ element, data }) => {
                // Handle normal view
                if (activeCluster === 'normal') {
                    element.setStyle({
                        color: '#808080',
                        weight: 2,
                        fillColor: '#808080',
                        fillOpacity: 0.5
                    });
                    element.unbindPopup();
                    element.bindPopup(createPopupContent(data));
                    return;
                }

                // Existing filtering logic
                const matchingPoints = data.dataPoints.filter(point => {
                    const clusterMatch = activeCluster === 'all' || point.cluster === parseInt(activeCluster);
                    const hortMatch = activeHortType === 'all' || point.jenis_hortikultura === activeHortType;
                    return clusterMatch && hortMatch;
                });

                if (matchingPoints.length > 0) {
                    const firstPoint = matchingPoints[0];
                    const hortType = horticulturalTypes.find(t => t.name === firstPoint.jenis_hortikultura);

                    element.setStyle({
                        color: hortType ? hortType.color : '#000000',
                        weight: 3,
                        fillColor: getClusterColor(firstPoint.cluster),
                        fillOpacity: 0.5
                    });
                } else {
                    element.setStyle({
                        color: '#666666',
                        weight: 1,
                        fillColor: '#666666',
                        fillOpacity: 0.2
                    });
                }

                element.unbindPopup();
                element.bindPopup(createPopupContent(data));
            });
        }

        // Event listeners
        document.querySelectorAll('.legend-item').forEach(item => {
            item.addEventListener('click', function() {
                activeCluster = this.dataset.cluster;
                updatePolygons();
            });
        });

        document.querySelectorAll('.hort-legend-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.hort-legend-item').forEach(i => {
                    i.style.backgroundColor = 'transparent';
                });
                this.style.backgroundColor = 'rgba(0,0,0,0.1)';

                activeHortType = this.dataset.jenis;
                updatePolygons();
            });
        });

        // Initial update
        updatePolygons();
    </script>
@endsection
