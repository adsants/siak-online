@extends('../../layouts.app')

@section('content')
<div class="card card-default">
    <div class="card-header">
        Ubah Data User
    </div>
    <div class="card-body p-4">

        <div class="row">
            <div class="col-md-6">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{ url('jenis-soal/update', $row->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Jenis Soal <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="jenis_soal" value="{{  $row->jenis_soal }}" />
                    </div>
                    <div class="form-group">
                        <label>Kategori Jenis Soal <span class="text-danger">*</span></label>
                        <select class="form-control" required  name="kategori">
                            <option value="">Silahkan Pilih</option>
                            <option @if ($row->kategori == 1) selected  @else  selecteds @endif 
                            value="1">Non Kecermatan</option>
                            <option @if ($row->kategori == 2) selected  @else  selecteds @endif value="2">Kecermatan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('jenis-soal') }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection