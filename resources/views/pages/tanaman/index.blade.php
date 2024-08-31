@extends('layouts.v_template')

@section('content')
    @if (auth()->user()->role == 'Administrator')
    <div class="row mt-5">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5>tanaman</h5>
                    <a href="{{ route('tanaman.create') }}" class="btn btn-primary mb-3">Tambah data</a>
                    <table class="table" id="table-data">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Wilayah</th>
                                <th>Luas Lahan</th>
                                <th>Produksi</th>
                                <th>Produktivitas</th>
                                <th>Jenis Horikultura</th>
                                <th>Persentase</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tanaman as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->wilayah->nama_wilayah ?? '' }}</td>
                                    <td>{{ $row->luas_lahan }}</td>
                                    <td>{{ $row->produksi }}</td>
                                    <td>{{ $row->produktivitas }}</td>
                                    <td>{{ $row->jenis_horikultura }}</td>
                                    <td>{{ $row->persentase }}</td>
                                    <td>
                                        <a href="{{ route('tanaman.edit', $row->id) }}"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('tanaman.destroy', $row->id) }}" method="POST"
                                            style="display: inline;">
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

        $('#liTanaman').addClass('active');
    </script>
@endSection
