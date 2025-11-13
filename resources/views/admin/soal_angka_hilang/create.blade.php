@extends('../../layouts.app')

@section('content')
<div class="card card-default">
    <div class="card-header">
        Tambah Data Soal Non Gambar
    </div>
    <div class="card-body p-4">

        <div class="row">
            <div class="col-md-6">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{url('soal/store') }}" method="POST">
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
                    
                    <div class="form-group">
                        <label>Soal Satu <span class="text-danger">*</span></label>
                        <input class="form-control"  maxlength="1" max="9" name="angka_satu" value="{{ old('angka_satu') }}" />
                    </div>
                    <div class="form-group">
                        <label>Soal Dua <span class="text-danger">*</span></label>
                        <input class="form-control"  maxlength="1" max="9" name="angka_dua" value="{{ old('angka_dua') }}" />
                    </div>
                    <div class="form-group">
                        <label>Soal Tiga <span class="text-danger">*</span></label>
                        <input class="form-control"  maxlength="1" max="9" name="angka_tiga" value="{{ old('angka_tiga') }}" />
                    </div>
                    <div class="form-group">
                        <label>Soal Empat <span class="text-danger">*</span></label>
                        <input class="form-control"  maxlength="1" max="9" name="angka_empat" value="{{ old('angka_empat') }}" />
                    </div>
                    <div class="form-group">
                        <label>Soal Benar <span class="text-danger">*</span></label>
                        <input class="form-control" maxlength="1" max="9" name="angka_benar" value="{{ old('angka_benar') }}" />
                    </div>
                
                    <div class="form-group">
                        <button class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('soal') }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection