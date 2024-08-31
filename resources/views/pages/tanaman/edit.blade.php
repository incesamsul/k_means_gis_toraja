@extends('layouts.v_template')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5>Edit tanaman: {{ $tanaman->name }}</h5>
                    <form action="{{ route('tanaman.update', $tanaman->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Wilayah</label>
                            <select name="wilayah_id" id="wilayah_id" class="form-control">
                                @foreach ($wilayah as $row)
                                    <option value="{{ $row->id }}"
                                        {{ $row->id == $tanaman->wilayah_id ? 'selected' : '' }}>
                                        {{ $row->nama_wilayah }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Luas Lahan</label>
                            <input value="{{ $tanaman->luas_lahan }}" type="text" class="form-control" id="luas_lahan" name="luas_lahan"
                                placeholder="Luas Lahan" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Produksi</label>
                            <input value="{{ $tanaman->produksi }}" type="text" class="form-control" id="produksi" name="produksi"
                                placeholder="Produksi" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Produktivitas</label>
                            <input value="{{ $tanaman->produktivitas }}" type="text" class="form-control" id="produktivitas" name="produktivitas"
                                placeholder="Produktivitas" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Jenis Horikultura</label>
                            <input value="{{ $tanaman->jenis_horikultura }}" type="text" class="form-control" id="jenis_horikultura" name="jenis_horikultura"
                                placeholder="Jenis Horikultura" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Persentase</label>
                            <input value="{{ $tanaman->persentase }}" type="text" class="form-control" id="persentase" name="persentase"
                                placeholder="Persentase" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
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
