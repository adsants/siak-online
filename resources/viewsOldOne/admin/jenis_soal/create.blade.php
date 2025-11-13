@extends('../../layouts.app')

@section('content')

<div class="card card-default">
    <div class="card-header">
        Tambah Data User
    </div>
    <div class="card-body p-4">
        <div class="row">
        <div class="col-md-6">
            @if($errors->any())
            @foreach($errors->all() as $err)
            <p class="alert alert-danger">{{ $err }}</p>
            @endforeach
            @endif
            <form action="{{ url('jenis-soal/store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Jenis Soal <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="jenis_soal" value="{{ old('jenis_soal') }}" />
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