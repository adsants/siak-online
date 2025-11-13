@extends('../../layouts.app')

@section('content')
<div class="card card-default">
    <div class="card-header">
        Ubah Data Soal Non Gambar
    </div>
    <div class="card-body p-4">

        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{ url('soal/update', $row->id ) }}" method="POST">
                    
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jenis Soal :</label>
                        <div class="row">
                            <div class="col-sm-12">
                            <select class="form-control " name="id_jenis_soal" onchange="pilihJenisSoal(this)">
                            <option value="" selected>Pilih Jenis Soal</option>
                            @foreach($jenisSoal as $row22)
                            <option value="{{$row22->id}}" 
                            @if($row->id_jenis_soal == $row22->id)         
                                {{"selected"}} 
                            @else
                                {{""}}
                            @endif
                            >{{$row22->jenis_soal}}</option>
                            @endforeach

                        </select>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jadikan Contoh Soal :</label>
                        <div class="row">
                            <div class="col-sm-12">
                            <select class="form-control " name="is_contoh">
                            <option value="" selected>Silahkan Pilih</option>
                            <option value="1" 
                            @if($row->is_contoh == '1')         
                                {{"selected"}} 
                            @else
                                {{""}}
                            @endif
                            >Ya</option>
                            <option value="0" 
                            @if($row->is_contoh == '0')         
                                {{"selected"}} 
                            @else
                                {{""}}
                            @endif
                            >Tidak</option>

                        </select>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Soal :</label>
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea class="ckeditor" required name="soal">{{ old('soal', $row->soal_choice) }}</textarea>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label>status <span class="text-danger">*</span></label>
                        <select class="form-control w-25" name="status_active">
                        @foreach($status_active as $key => $val)
                            @if($key==old('status',$row->status_active))
                                <option value="{{ $key }}" selected>{{ $val }}</option>
                            @else
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jawaban 1 :</label>
                        <div class="row">
                            <div class="col-sm-10">
                                <textarea class="ckeditor" required name="jawaban_1">{{ old('jawaban_1', $jawaban1) }}</textarea>
                            </div>
                            
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status_jawaban" @if ($jawaban1Benar == '1') checked @endif  value="1"> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jawaban 2 :</label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <textarea class="ckeditor" required name="jawaban_2">{{ old('jawaban_2', $jawaban2) }}</textarea>
                                </div>
                                <div class="col-sm-2">
                                    <label class="mt-radio mt-radio-outline">
                                        <input type="radio" name="status_jawaban" @if ($jawaban2Benar == '1') checked @endif value="2"> 
                                        Jawaban Benar
                                        <span></span>
                                    </label>	
                                </div>
                            </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jawaban 3 :</label>
                        <div class="row">
                            <div class="col-sm-10">
                            <textarea class="ckeditor" name="jawaban_3">{{ old('jawaban_3', $jawaban3) }}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status_jawaban"  value="3" @if ($jawaban3Benar == '1') checked @endif> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jawaban 4 :</label>
                        <div class="row">
                            <div class="col-sm-10">
                            <textarea class="ckeditor"  name="jawaban_4">{{ old('jawaban_4', $jawaban4) }}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status_jawaban" @if ($jawaban4Benar == '1') checked @endif value="4"> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jawaban 5 : </label>
                        <div class="row">
                            <div class="col-sm-10">
                            <textarea class="ckeditor"  name="jawaban_5">{{  old('jawaban_5', $jawaban5) }}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status_jawaban"  @if ($jawaban5Benar == '1') checked @endif  value="5"> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                               
                            </div>
                            </div>
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