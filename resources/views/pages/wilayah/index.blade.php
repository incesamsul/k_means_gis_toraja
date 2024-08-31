@extends('layouts.v_template')

@section('content')
    @if (auth()->user()->role == 'Administrator')
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <h5>wilayah</h5>
                        <a href="{{ route('wilayah.create') }}" class="btn btn-primary mb-3">Tambah data</a>
                        <table class="table" id="table-data">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Wilayah</th>
                                    <th>Lokasi </th>
                                    <th>Koordinat</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wilayah as $wilayah)
                                    <tr>
                                        <td>{{ $wilayah->id }}</td>
                                        <td>{{ $wilayah->nama_wilayah }}</td>
                                        <td>{{ $wilayah->lokasi }}</td>
                                        <td class="text-wrap">{{ $wilayah->koordinat }}</td>
                                        <td>
                                            <a href="{{ route('wilayah.edit', $wilayah->id) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('wilayah.destroy', $wilayah->id) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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

        $('#liWilayah').addClass('active');
    </script>
@endSection
