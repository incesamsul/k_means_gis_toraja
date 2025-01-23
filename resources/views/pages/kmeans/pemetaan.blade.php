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
        // Create a map centered on Tana Toraja, Indonesia
        var map = L.map('map').setView([-3.0753, 119.7426], 11);

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
            console.log(@json($data))
                _coordinates.push(@json($data['wilayah']->koordinat));
                _clusterLabels.push('Cluster {{ $key + 1 }}'); // Example label for cluster
                _clusterCaptions.push('{{ $data['wilayah']->nama_wilayah }} ({{ $data['wilayah']->lokasi }} ) <br> Luas Lahan : {{ $data['luas_lahan'] }} <br> Produksi : {{ $data['produksi'] }} <br> Produktivitas : {{ $data['produktivitas'] }} <br> Jenis Hortikultura : {{ $data['jenis_hortikultura'] }} <br> Persentase : {{ $data['persentase'] }}');
                
                _clusterLevel.push('{{ $level }}');
            @endforeach
        @endforeach

        function parseCoordinateString(coordString) {
            console.log('Raw coordinates:', coordString);
            
            const cleanedString = coordString
                .replace(/^\[|\]$/g, '')
                .replace(/…/g, '');
                
            console.log('Cleaned string:', cleanedString);
            
            const pairs = cleanedString.split('],[');
            console.log('Coordinate pairs:', pairs);
            
            const result = pairs.map(pair => {
                const coords = pair.split(',').map(Number);
                console.log('Parsed pair:', coords);
                return coords;
            });
            
            return result;
        }

        const parsedData = _coordinates.map((coords, index) => {
            console.log(`Parsing coordinates for index ${index}`);
            try {
                return parseCoordinateString(coords);
            } catch (error) {
                console.error(`Error parsing coordinates at index ${index}:`, error);
                return [];
            }
        });

        parsedData.forEach((polygonCoords, index) => {
            if (!polygonCoords || polygonCoords.length === 0) {
                console.warn(`Skipping invalid polygon at index ${index}`);
                return;
            }

            // Swap [longitude, latitude] to [latitude, longitude] and validate coordinates
            const correctedPolygonCoords = polygonCoords.map(coordPair => {
                if (!Array.isArray(coordPair) || coordPair.length !== 2 || 
                    !isFinite(coordPair[0]) || !isFinite(coordPair[1])) {
                    console.error(`Invalid coordinate pair at index ${index}:`, coordPair);
                    return null;
                }
                return [coordPair[1], coordPair[0]];
            }).filter(coord => coord !== null);

            if (correctedPolygonCoords.length === 0) {
                console.warn(`No valid coordinates for polygon at index ${index}`);
                return;
            }

            console.log('Debug -', index, ':', {
                clusterLabel: _clusterLabels[index],
                clusterLevel: _clusterLevel[index],
                coordinates: correctedPolygonCoords
            });

            let color;
            if (_clusterLevel[index] === 'High') {
                color = '#00B050';  // Green for High
                console.log('Setting GREEN for', index);
            } else if (_clusterLevel[index] === 'Medium') {
                color = '#C00000';  // Red for Medium
                console.log('Setting RED for', index);
            } else {
                color = '#C000C5';  // Purple for Low
                console.log('Setting PURPLE for', index);
            }

            try {
                L.polygon(correctedPolygonCoords, {
                    color: color,
                    weight: 0,
                    fillColor: color,
                    fillOpacity: 0.5
                })
                .bindPopup(_clusterLevel[index] + ' - ' + _clusterLabels[index] + '<br>' + _clusterCaptions[index])
                .addTo(map);
            } catch (error) {
                console.error(`Error creating polygon at index ${index}:`, error);
            }
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
