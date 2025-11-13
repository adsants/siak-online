@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Informasi Ujian</div>
 
                <div class="card-body">
                <div class="bd-content ps-lg-4">
        
                    <div class="row g-3">

                        <div class="col-md-6">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Nama Ujian</span>
                            <strong class="d-block h5 mb-0">{{$row->ujianName}}</strong>
                            </a>
                        </div>

                        <div class="col-md-6 ">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Jenis Soal</span>
                            <strong class="d-block h5 mb-0">{{$row->jenis_soal}}</strong>
                            </a>
                        </div>

                        

                        
                        <div class="col-md-4 mt-4">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Tanngal Pengerjaan Ujian</span>
                            <strong class="d-block h5 mb-0">{{$row->tgl_ujian_indo}}</strong>
                            </a>
                        </div>
                        <div class="col-md-2 mt-4">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Jumlah Soal</span>
                            <strong class="d-block h5 mb-0">{{$row->jumlah_soal}}</strong>
                            </a>
                        </div>

                        <div class="col-md-2 mt-4">
                            <a class="d-block text-decoration-none" href="#">
                            <span class="text-secondary">Waktu Pengerjaan</span>
                            <strong class="d-block h5 mb-0">{{$row->waktu_pengerjaan}} Menit</strong>
                            </a>
                        </div>
                    </div>
                    <br>
                    
                    {!! $textInfo->info_awal_simulasi !!}

                    <?php
                    if($row->kategori == 2 ){
                    ?>    
                        <a href="{{ url('ujian-simulasi-kecermatan',[ $row->idUjianDetails,  $row->idUjianUser] ) }}">
                            <span class="btn btn-success">Contoh Simulasi Pengerjaan Ujian</span>
                        </a>
                    <?php
                    }
                    else{
                    ?>
                        <a href="{{ url('ujian-simulasi',[ $row->idUjianDetails,  $row->idUjianUser] ) }}">
                            <span class="btn btn-success">Contoh Simulasi Pengerjaan Ujian</span>
                        </a>
                    <?php
                    }
                    ?>
                    
                        

                </div>
            </div>
        </div>
    </div>
</div>
@endsection