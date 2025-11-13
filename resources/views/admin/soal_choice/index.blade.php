@extends('../../layouts.app')

@section('content')


@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif
<div class="card card-default">
    
    <div class="card-header">
        Data Soal
    </div>
    <div class="card-header">
        
        <form >
        <div class="form-group">
            <label class="control-label col-sm-12" >Jenis Soal :</label>
            <div class="row">
                <div class="col-sm-12">
                <select class="form-control " name="id_jenis_soal" onchange="pilihJenisSoal(this)">
                <option value="" selected>Pilih Jenis Soal</option>
                @foreach($jenisSoal as $row)
                <option value="{{$row->id}}" 
                @if($idJenisSoal == $row->id)         
                    {{"selected"}} 
                @else
                    {{""}}
                @endif
                >{{$row->jenis_soal}}</option>
                @endforeach

            </select>
                </div>
                
            </div>
        </div>
        
        @if($idJenisSoal != '')  
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <input class="form-control" type="text" name="q" value="{{ $q}}" placeholder="Pencarian... Enter" /> 
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-primary" href="{{ url('soal') }}">Refresh</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-primary" href="{{ url('soal/create?id_jenis_soal='.$idJenisSoal) }}">Tambah</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-warning" href="{{ url('soal/import/'.$idJenisSoal) }}">Import</a>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-success" href="{{ url('/soal/export-to-json/'.$idJenisSoal) }}">Export</a>
                </div>
                
            </div>
        </div>
        </form>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Soal</th>
                    <th>Jawaban</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            {!! $htmlTable !!}
        </table>
        <br>
        <br>
        <div class="d-flex justify-content-center">
        {{ $rows->links() }}
    </div>
    </div>
                @endif
</div>

<script>
function pilihJenisSoal(sel){
    var value = sel.value;  
    window.location.href = "{{URL::to('soal/')}}"+"/?id_jenis_soal="+value;

}
</script>
@endsection