@extends('../../layouts.app')

@section('content')

@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif
<div class="card card-default">
    
    <div class="card-header">
        Data Ujian
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tgl </th>
                    <th>Peserta</th>
                </tr>
            </thead>
            <?php $no = 1 ?>
            @foreach($rows as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->tgl_ujian }}</td>
               
                <td>
                    <a class="btn btn-sm btn-success" href="{{ url('hasil-ujian/peserta', $row->id ) }}">Data Peserta</a>
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