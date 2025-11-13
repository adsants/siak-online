@extends('../../layouts.app')

@section('content')

@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif
<div class="card card-default">
    <div class="card-header">
        <form class="form-inline">
            <div class="form-group mr-1">
                <input class="form-control" type="text" name="q"  placeholder="Pencarian..." />
            </div>
            <div class="form-group mr-1">
                <button class="btn btn-success">Refresh</button>
            </div>
        </form>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Nama Pelatihan</th>
                    <th>Deskripsi</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Selesai</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <?php $no = 1 ?>
            @foreach($rows as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->deskripsi }}</td>
                <td>{{ $row->start_date }}</td>
                <td>{{ $row->end_date }}</td>
                <td>
                    <a class="btn btn-sm btn-success" href="{{ url('proses-pelatihan/presensi', $row->id ) }}">Presensi</a>                    
                </td>
                <td>
                    <a class="btn btn-sm btn-warning" href="{{ url('proses-pelatihan/ujian', $row->id ) }}">Ujian</a>
                </td>
                <td>
                    <a class="btn btn-sm btn-primary" href="{{ url('detail-pelatihan', $row->id ) }}">Detail</a>
                </td>
            </tr>
            @endforeach
        </table>
        <br>
        <br>
        <div class="d-flex justify-content-center">
        {{ $rows->links() }}
    </div>
    </div>
</div>
@endsection