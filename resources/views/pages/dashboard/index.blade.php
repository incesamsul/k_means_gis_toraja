@extends('layouts.v_template')


@section('content')
    <div class="row">
        @if (auth()->user()->role == 'Administrator')
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Pengguna</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <span class="text-success text-sm font-weight-bolder">{{ $users }}</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-main text-white shadow text-center border-radius-md">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="{{ auth()->user()->role == 'Administrator' ? 'col-xl-3' : 'col-xl-4' }} col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Wilayah</p>
                                <h5 class="font-weight-bolder mb-0">

                                    <span class="text-success text-sm font-weight-bolder">{{ $wilayah }}</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-main text-white shadow text-center border-radius-md">
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="{{ auth()->user()->role == 'Administrator' ? 'col-xl-3' : 'col-xl-4' }} col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Tanaman</p>
                                <h5 class="font-weight-bolder mb-0">

                                    <span class="text-danger text-sm font-weight-bolder">{{ $tanaman }}</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-main text-white shadow text-center border-radius-md">
                                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="{{ auth()->user()->role == 'Administrator' ? 'col-xl-3' : 'col-xl-4' }} col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Cluster</p>
                                <h5 class="font-weight-bolder mb-0">

                                    <span class="text-success text-sm font-weight-bolder">5</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-main text-white shadow text-center border-radius-md">
                                <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class=" {{ auth()->user()->role == 'user' ? 'col-lg-6' : 'col-lg-12' }} mb-lg-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="d-flex flex-column h-100">
                                <p class="mb-1 pt-2 text-bold">Pemetaan tanaman Hi</p>
                                <h5 class="font-weight-bolder">K-means</h5>
                                <p class="mb-5">K-means clustering adalah algoritma pembelajaran mesin yang digunakan untuk mempartisi data menjadi beberapa cluster berdasarkan kemiripan fitur-fiturnya. Algoritma ini bekerja dengan cara menghitung jarak antara setiap titik data dengan centroid (titik tengah) cluster, dan kemudian mengelompokkan data berdasarkan jarak terdekat dengan centroid. </p>
                            </div>

                        </div>
                        <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
                            <div class=" border-radius-lg h-100">
                                <img src="{{ asset('soft-ui-dashboard-main/') }}/assets/img/shapes/waves-white.svg"
                                    class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
                                <div class="position-relative d-flex align-items-center justify-content-center h-100">
                                    <img class="w-100 position-relative z-index-2 pt-4"
                                        src="{{ asset('img/heroimg.png') }} ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


 @endsection

@section('script')
    <script>
        $('.nav-link').removeClass('active');
        $('#liDashboard').addClass('active');
    </script>
@endsection
