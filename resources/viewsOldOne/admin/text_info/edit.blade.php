@extends('../../layouts.app')

@section('content')
<div class="card card-default">
    <div class="card-header">
        Ubah Data Text Info Simulasi
    </div>
    <div class="card-body p-4">

        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                
                @if(session('success'))
                <p class="alert alert-success">{{ session('success') }}</p>
                @endif
                <form action="{{ url('text-info/update') }}" method="POST">
                    
                    @csrf
                    @method('POST')
                  
                    <div class="form-group">
                        <label class="control-label col-sm-12" >info awal simulasi :</label>
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea class="ckeditor" required name="info_awal_simulasi">{{ old('info_awal_simulasi', $row->info_awal_simulasi) }}</textarea>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" >info akhir simulasi :</label>
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea class="ckeditor" required name="info_akhir_simulasi">{{ old('info_akhir_simulasi', $row->info_akhir_simulasi) }}</textarea>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" >info jawaban salah :</label>
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea class="ckeditor" required name="info_jawaban_salah">{{ old('info_jawaban_salah', $row->info_jawaban_salah) }}</textarea>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" >info jawaban benar :</label>
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea class="ckeditor" required name="info_jawbaan_benar">{{ old('info_jawbaan_benar', $row->info_jawbaan_benar) }}</textarea>
                            </div>
                            
                        </div>
                    </div>
                    
                    

                    <div class="form-group">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection