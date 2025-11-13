@extends('../../layouts.app')

@section('content')

<div class="card card-default">
    <div class="card-header">
        Tambah Data Pelatihan
    </div>
    <div class="card-body p-4">
        <div class="row">
        <div class="col-md-6">
            @if($errors->any())
            @foreach($errors->all() as $err)
            <p class="alert alert-danger">{{ $err }}</p>
            @endforeach
            @endif
            <form action="{{ url('pelatihan/store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Pelatihan <span class="text-danger">*</span></label>
                    <input class="form-control" required type="text" name="name" value="{{ old('name') }}" />
                </div>
                <div class="form-group">
                    <label>Deskripsi </label>
                    <textarea class="form-control" type="text" name="deskripsi" >{{ old('deskripsi') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label>Tgl Mulai <span class="text-danger">*</span></label>
                    <input class="form-control  w-25" type="datetime-local" name="start_date" value="{{ old('start_date') }}" required />
                </div>
                <div class="form-group">
                    <label>Tgl Selesai <span class="text-danger">*</span></label>
                    <input class="form-control  w-25" type="datetime-local" name="end_date" value="{{ old('end_date') }}" required />
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Simpan</button>
                    <a class="btn btn-danger" href="{{ url('pelatihan') }}">Kembali</a>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

@endsection