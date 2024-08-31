<div class="row">
    <div class="col-lg-4">
        <div class="card shadow-none">
            @if (auth()->user()->jenis_kelamin &&
                    auth()->user()->berat_badan &&
                    auth()->user()->tinggi_badan &&
                    auth()->user()->aktivitas)
                <div class="row mt-4" hidden>
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5><strong>Perhitungan Kalori</strong></h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Usia</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Berat Badan</th>
                                            <th>Tinggi Badan</th>
                                            <th>Kalori</th>
                                            <th>Aktivitas</th>
                                            <th>Hasil Akhir</th>
                                            <th>Hasil Kalori Diet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ auth()->user()->name ?? '' }}</td>
                                            <td>{{ auth()->user()->usia ?? '' }}</td>
                                            <td>{{ auth()->user()->jenis_kelamin ?? '' }}</td>
                                            <td>{{ auth()->user()->berat_badan ?? '' }}</td>
                                            <td>{{ auth()->user()->tinggi_badan ?? '' }}</td>
                                            @php
                                                // calculation  here
                                                $plMen = 655;
                                                $plWomen = 66.5;
                                                $menFormula =
                                                    $plMen +
                                                    9.6 * auth()->user()->berat_badan +
                                                    1.8 * auth()->user()->tinggi_badan -
                                                    4.7 * auth()->user()->usia;
                                                $womenFormula =
                                                    $plWomen +
                                                    13.7 * auth()->user()->berat_badan +
                                                    5 * auth()->user()->tinggi_badan -
                                                    6.8 * auth()->user()->usia;
                                                if (auth()->user()->jenis_kelamin == 'Laki-laki') {
                                                    $calories = $womenFormula;
                                                } else {
                                                    $calories = $menFormula;
                                                }

                                                $hasilAkhir = $calories * auth()->user()->aktivitas;
                                            @endphp

                                            <td>{{ $calories }}</td>
                                            <td>{{ auth()->user()->aktivitas ?? '' }}</td>
                                            <td>{{ $hasilAkhir }}</td>
                                            <td>{{ $hasilAkhir - 1000 }}</td>
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            @endif
            <div class="card-body">
                <div class="">
                    <h5><strong>Detail Profile {{ auth()->user()->role }}</strong></h5>
                    <div class="mb-3">
                        <label class="my-3">Email</label>
                        <div>{{ auth()->user()->email }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="my-3">Username</label>
                        <div>{{ auth()->user()->name }}</div>
                    </div>
                    {{-- <div class="mb-3">
                        <label class="my-3">Tanggal Dibuat</label>
                        <div>{{ auth()->user()->created_at }}</div>
                    </div> --}}
                    @if (auth()->user()->role == 'user')
                        <form action="{{ URL::to('/update_profile') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="berat_badan">Berat badan</label>
                                <input value="{{ auth()->user()->berat_badan }}" type="text" class="form-control"
                                    name="berat_badan" id="berat_badan">
                            </div>
                            <div class="form-group">
                                <label for="tinggi_badan">Tinggi badan</label>
                                <input value="{{ auth()->user()->tinggi_badan }}" type="text" class="form-control"
                                    name="tinggi_badan" id="tinggi_badan">
                            </div>
                            <div class="form-group">
                                <label for="usia">Usia</label>
                                <input value="{{ auth()->user()->usia }}" type="text" class="form-control"
                                    name="usia" id="usia">
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                    <option {{ auth()->user()->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option {{ auth()->user()->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="aktivitas">Aktivitas</label>
                                <select name="aktivitas" id="aktivitas" class="form-control">
                                    <option value="">Pilih Aktivitas</option>
                                    {{-- sangat ringan : bmr x 1,2
ringan : bmr x 1,375
normal : bmr x 1,55
berat : bmr 1,725
sangat berat : bmr x 1,9 --}}
                                    <option value="1.2" {{ auth()->user()->aktivitas == '1.2' ? 'selected' : '' }}>
                                        Sangat Ringan
                                    </option>
                                    <option value="1.375"
                                        {{ auth()->user()->aktivitas == '1.375' ? 'selected' : '' }}>
                                        Ringan
                                    </option>
                                    <option value="1.55" {{ auth()->user()->aktivitas == '1.55' ? 'selected' : '' }}>
                                        Normal
                                    </option>
                                    <option value="1.725"
                                        {{ auth()->user()->aktivitas == '1.725' ? 'selected' : '' }}>
                                        Berat
                                    </option>
                                    <option value="1.9" {{ auth()->user()->aktivitas == '1.9' ? 'selected' : '' }}>
                                        Sangat Berat
                                    </option>

                                </select>
                                {{-- <input value="{{ auth()->user()->aktivitas }}" type="text" class="form-control"
                                    name="aktivitas" id="aktivitas"> --}}
                            </div>
                            <div class="form-group">
                                <label for="kalori">Kalori</label>
                                @if (isset($hasilAkhir))
                                    <input value="{{ $hasilAkhir - 1000 }}" type="text" class="form-control"
                                        name="kalori" id="kalori" disabled>
                                @endif

                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
