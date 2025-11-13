@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Hasil Ujian</div>
 
                <div class="card-body">
                <div class="bd-content ps-lg-4">
        
                    <div class="row g-3">
                        <div class="col-md-12 mb-4">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary font-weight-bold">Selamat, Anda telah menyelesaikan Ujian.</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Nama Ujian</span>
                            <strong class="d-block h5 mb-0">{{$row->ujianName}}</strong>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Jenis Soal</span>
                            <strong class="d-block h5 mb-0">{{$row->jenis_soal}}</strong>
                            </a>
                        </div>

                        <div class="col-md-2">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Jumlah Soal</span>
                            <strong class="d-block h5 mb-0">{{$row->jumlah_soal}}</strong>
                            </a>
                        </div>

                        <div class="col-md-2">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Waktu Pengerjaan</span>
                            <strong class="d-block h5 mb-0">{{$row->waktu_pengerjaan}} Menit</strong>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-3 mt-4">    
                        <div class="col-md-6">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Waktu Mengerjakan</span>
                            <strong class="d-block h6 mb-0">{{$row->start_date}} s/d  {{$row->finish_date}}</strong>
                            </a>
                        </div>

                        <div class="col-md-2">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Jumlah Benar</span>
                            <strong class="d-block h5 mb-0">{{$row->jawaban_benar}}</strong>
                            </a>
                        </div>

                        <div class="col-md-2">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Jumlah Salah</span>
                            <strong class="d-block h5 mb-0">{{$row->jawaban_salah}}</strong>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Nilai</span>
                            <strong class="d-block h3 mb-0">{{$row->nilai}}</strong>
                            </a>
                        </div>

                    </div>
                    <hr>
                    
                    {!! $hasilPengerjaan !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection