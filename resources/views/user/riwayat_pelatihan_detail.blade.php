@extends('../../layouts.app')

@section('content')

@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                 Riwayat Absensi <b>{{$data_pelatihan->pelatihanName}}</b>
                </div>
                <div class="card-body">            
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead>
                                <th>Tanggal</th>
                                <th>Pengajar</th>
                                <th>Modul /  Materi</th>
                                <th>Status Hadir</th>
                            </thead>
                           {!! $tableAbsensi !!}
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                 Riwayat Ujian <b>{{$data_pelatihan->pelatihanName}}</b>
                </div>
                <div class="card-body">            
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                           {!! $tableUjian !!}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection