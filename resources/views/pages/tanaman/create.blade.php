@extends('layouts.v_template')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5>Tambah data</h5>
                    <form action="{{ route('tanaman.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- <th>Wilayah</th>
                        <th>Luas Lahan</th>
                        <th>Produksi</th>
                        <th>Produktivitas</th>
                        <th>Jenis Horikultura</th>
                        <th>Persentase</th>
                        <th>Actions</th>
                         --}}

                        <div class="form-group">
                            <label for="name">Wilayah</label>
                            <select name="wilayah_id" id="wilayah_id" class="form-control">
                                @foreach ($wilayah as $row)
                                    <option value="{{ $row->id }}">{{ $row->nama_wilayah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Luas Lahan</label>
                            <input type="text" class="form-control" id="luas_lahan" name="luas_lahan"
                                placeholder="Luas Lahan" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Produksi</label>
                            <input type="text" class="form-control" id="produksi" name="produksi"
                                placeholder="Produksi" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Produktivitas</label>
                            <input type="text" class="form-control" id="produktivitas" name="produktivitas"
                                placeholder="Produktivitas" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Jenis Horikultura</label>
                            <input type="text" class="form-control" id="jenis_horikultura" name="jenis_horikultura"
                                placeholder="Jenis Horikultura" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Persentase</label>
                            <input type="text" class="form-control" id="persentase" name="persentase"
                                placeholder="Persentase" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#liAlternatif').addClass('active');
    </script>
@endSection
