@extends('../../layouts.app')

@section('content')

<div class="card card-default">
    <div class="card-header">
        Import Data User
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-6">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{ route('import-soal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="form-group">
                        <label>Jenis Soal <span class="text-danger">*</span></label>
                        <select class="form-control" required name="jenis_soal" >
                            <option value="">Silahkan Pilih</option>
                            <option value="Angka">Angka</option>
                            <option value="Huruf">Huruf</option>
                            <option value="Simbol">Simbol</option>
                            <option value="Kombinasi">Kombinasi</option>
                        </select>
                    </div>

                    <input type="file" name="file" class="form-control">
                        <p class="form-text">Angka hanya satu digit (antara 1 - 9)</p>
                
                    <a target="_BLANK" href="{{ asset('documents/soal-example.xlsx') }}">contoh file excel</a>
                    <br>
                    <br>
                    <button class="btn btn-success">Import Soal Data</button>
                    <a class="btn btn-danger" href="{{ url('soal') }}">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection