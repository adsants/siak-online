@extends('../../layouts.app')

@section('content')
<div class="card card-default">
    <div class="card-header">
        Ubah Data Soal Non Gambar
    </div>
    <div class="card-body p-4">

        <div class="row">
            <div class="col-md-6">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{ url('soal/update', $row->id ) }}" method="POST">
                    
                    @csrf
                    @method('POST')
                    
                    <div class="form-group">
                        <label>Jenis Soal <span class="text-danger">*</span></label>
                        <select class="form-control" required name="jenis_soal" >
                            <option value="">Silahkan Pilih</option>
                            <option <?php if($row->jenis_Soal == 'Angka') echo "selected"; ?> value="Angka">Angka</option>
                            <option <?php if($row->jenis_Soal == 'Huruf') echo "selected"; ?> value="Huruf">Huruf</option>
                            <option <?php if($row->jenis_Soal == 'Simbol') echo "selected"; ?> value="Simbol">Simbol</option>
                            <option <?php if($row->jenis_Soal == 'Kombinasi') echo "selected"; ?> value="Kombinasi">Kombinasi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Angka Satu <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="angka_satu" value="{{ old('angka_satu', $soal_satu) }}" />
                    </div>
                    <div class="form-group">
                        <label>Angka Dua <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="angka_dua" value="{{ old('angka_dua', $soal_dua) }}" />
                    </div>
                    <div class="form-group">
                        <label>Angka Tiga <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="angka_tiga" value="{{ old('angka_tiga', $soal_tiga) }}" />
                    </div>
                    <div class="form-group">
                        <label>Angka Empat <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="angka_empat" value="{{ old('angka_empat', $soal_empat) }}" />
                    </div>
                    <div class="form-group">
                        <label>Angka Benar <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="angka_benar" value="{{ old('angka_benar', $soal_benar) }}" />
                    </div>
                    
                    

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('soal') }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection