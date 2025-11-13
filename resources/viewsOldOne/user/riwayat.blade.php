@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Riwayat Ujian</div>
 
                <div class="card-body">
                    
                    <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Jenis Soal</th>
                                <th>Jumlah Soal</th>
                                <th>Waktu Pengerjaan</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach($rows as $row)
                            <tr>
                                <td>{{ $row->ujian_name }}</td>
                                <td>{{ $row->jenis_soal }}</td>
                                <td>{{ $row->jumlah_soal }}</td>
                                <td>{{ $row->start_date }} - {{$row->finish_date}}</td>
                                <td>
                                <a class="btn btn-sm btn-success" href="{{ url('ujian-selesai', $row->id) }}">Detail Ujian</a>
                            
                                </td>
                            </tr>
                        @endforeach

                        @if(count($rows) == 0)
                            <tr>
                                <td colspan='5'>Tidak ada Ujian yang dapat diikuti</td>
                            </tr>
                        @endif

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection