@extends('layouts.v_template')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12 grid-margin">
                <div class="card">

                    <div class="card-body ">
                        <div class="card-title">
                            Pengguna
                            <button type="button" class="btn bg-main text-white float-end" data-bs-toggle="modal"
                                id="addUserBtn" data-bs-target="#modalPengguna">
                                Tambah
                            </button>
                        </div>

                        <table class="table table-user " id="table-data">
                            <thead>
                                <tr>
                                    <th width="5%" class="sorting" data-sorting_type="asc" data-column_name="id"
                                        style="cursor: pointer">ID <span id="id_icon"></span></th>
                                    <td>Nama</td>
                                    <td>Email</td>
                                    <td>Tipe Pengguna</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengguna as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->email }}</td>
                                        <td>{{ $p->role }}</td>
                                        <td class="">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn bg-main text-white dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-pen"></i> Action
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a data-bs-toggle="modal" data-bs-target="#modalPengguna"
                                                            class="dropdown-item edit"
                                                            data-pengguna='@json($p)' href="#"><i
                                                                class="mdi mdi-pen"></i> Edit</a></li>
                                                    <li><a class="dropdown-item hapus"
                                                            data-id_pengguna="{{ $p->id }}" href="#"><i
                                                                class="mdi mdi-delete"></i> Hapus</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    {{-- MODAL TAMBAH PENGGUNA --}}
    <div class="modal fade" id="modalPengguna" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="main-body">
                    <form id="formPengguna" action="{{ URL::to('/admin/create_pengguna') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="hidden" class="form-control" name="id" id="id">
                            <input type="text" class="form-control" name="nama" id="nama">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">email</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="tipe-pengguna" class="form-label">Role</label>
                            <select class="form-select" name="tipe_pengguna" id="tipe-pengguna">
                                <option selected>Pilih Role</option>
                                <option>Administrator</option>
                                <option>user</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn bg-gradient-primary text-white" id="modalBtn">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {



            // table data
            var table = $('#table-data').DataTable({
                "lengthChange": false,
                "responsive": true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf'
                ]
            });


            // TOMBOL EDIT USER
            $('.table-user tbody').on('click', 'tr td a.edit', function() {
                let dataPengguna = $(this).data('pengguna');
                $('#nama').val(dataPengguna.name);
                $('#email').val(dataPengguna.email);
                $('#tipe-pengguna').val(dataPengguna.role);
                $('#id').val(dataPengguna.id);
                $('#ModalLabel').html('Ubah Pengguna');
                $('#modalBtn').html('Ubah');
                $('.modal-footer').show();
                $('#main-body').show();
                $('#formPengguna').attr('action', '/admin/update_pengguna');
            })

            // TOMBOL TAMBAH USER
            $('#addUserBtn').on('click', function() {
                $('#ModalLabel').html('Tambah Pengguna');
                $('#modalBtn').html('Tambah');
                $('.modal-footer').show();
                $('#main-body').show();
                $('#formPengguna').attr('action', '/admin/create_pengguna');
            });

            // TOMBOL HAPUS USER
            $('.table-user tbody').on('click', 'tr td a.hapus', function() {
                let idPengguna = $(this).data('id_pengguna');
                Swal.fire({
                    title: 'Apakah yakin?',
                    text: "Data tidak bisa kembali lagi!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '/admin/delete_pengguna',
                            method: 'post',
                            dataType: 'json',
                            data: {
                                user_id: idPengguna
                            },
                            success: function(data) {
                                if (data == 1) {
                                    Swal.fire('Berhasil', 'Data telah terhapus',
                                        'success').then((result) => {
                                        location.reload();
                                    });
                                }
                            }
                        })
                    }
                })
            });





        });

        $('#liPengguna').addClass('active');
        $('#liManajemenPengguna').addClass('active');
    </script>
@endsection
