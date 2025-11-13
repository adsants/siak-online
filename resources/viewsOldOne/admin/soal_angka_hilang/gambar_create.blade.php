@extends('../../layouts.app')

@section('content')
<div class="card card-default">
    <div class="card-header">
        Tambah Data Soal Kecermatan
    </div>
    <div class="card-body p-4">

        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{url('soal/store-kecermatan') }}" method="POST" class="form-horizontal" id="form_zstandard">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jadikan Contoh Soal :</label>
                        <div class="row">
                            <div class="col-sm-12">
                            <select class="form-control " name="is_contoh">
                            <option value="0" >Tidak</option>
                            <option value="1" >Ya</option>

                        </select>
                            </div>
                            
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-12" >Soal 1 :</label>
                        <div class="row">
                            <div class="col-sm-10">
                                <textarea class="ckeditor" required name="jawaban_1">{{ old('jawaban_1') }}</textarea>
                            </div>
                            
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status"  value="1"> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-12" >Soal 2 :</label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <textarea class="ckeditor" required name="jawaban_2">{{ old('jawaban_2') }}</textarea>
                                </div>
                                <div class="col-sm-2">
                                    <label class="mt-radio mt-radio-outline">
                                        <input type="radio" name="status"  value="2"> 
                                        Jawaban Benar
                                        <span></span>
                                    </label>	
                                </div>
                            </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Soal 3 :</label>
                        <div class="row">
                            <div class="col-sm-10">
                            <textarea class="ckeditor" required name="jawaban_3">{{ old('jawaban_3') }}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status"  value="3"> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Soal 4 :</label>
                        <div class="row">
                            <div class="col-sm-10">
                            <textarea class="ckeditor" required name="jawaban_4">{{ old('jawaban_4') }}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status"  value="4"> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Soal 5 :</label>
                        <div class="row">
                            <div class="col-sm-10">
                            <textarea class="ckeditor" required name="jawaban_5">{{ old('jawaban_5') }}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status"  value="5"> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                            </div>
                            </div>
                    </div>
                    

                    <div class="form-group">
                        <button class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('soal/kecermatan') }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection