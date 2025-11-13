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
                Riwayat Absensi 
            </div>
                <div class="card-body">            
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Pelatihan</th>
                            <th>Deskripsi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php $no = 1 ?>
                    @foreach($rows as $row)
                    <tr>
                        <td>{{ $row->pelatihanName }}</td>       
                        <td>{{ $row->deskripsi }}</td>       
                        <td>
                            <a class="btn btn-sm btn-success" href="{{ url('riwayat-pelatihan/detail', $row->pelatihan_id ) }}">Detail</a>
                        </td>
                    </tr>
                    @endforeach

                    @if(count($rows) == 0)
                    <tr>
                        <td colspan="3">Belum terdapat pelatihan.</td>
                    </tr>
                    @endif
                </table>
                <br>
                <br>
                <div class="d-flex justify-content-center">
            </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection