@extends('layouts.v_template')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5>Tambah data</h5>
                    <form action="{{ route('wilayah.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama wilayah</label>
                            <input type="text" class="form-control" id="nama_wilayah" name="nama_wilayah"
                                placeholder="Nama wilayah" required>
                        </div>
                        <div class="form-group">
                            <label for="lokasi">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi"
                                placeholder="Lokasi" required>
                        </div>
                        <div class="form-group">
                            <label for="koordinat">Koordinat</label>
                            <textarea name="koordinat" id="koordinat" cols="30" rows="10" class="form-control"></textarea>
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
