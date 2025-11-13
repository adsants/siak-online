@extends('../../layouts.app')

@section('content')
<div class="card card-default">
    <div class="card-header">
        Tambah Data Ujian
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{url('ujian/store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jenis Soal :</label>
                        <div class="row">
                            <div class="col-sm-12">
                            <select class="form-control " name="id_jenis_soal" onchange="pilihJenisSoal(this)">
                            <option value="" selected>Pilih Jenis Soal</option>
                            @foreach($jenisSoal as $row)
                            <option value="{{$row->id}}" 
                          
                            >{{$row->jenis_soal}}</option>
                            @endforeach

                        </select>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Ujian <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name" required value="{{ old('name') }}" />
                    </div>
                   
                    <div class="form-group">
                        <label>Tgl Ujian <span class="text-danger">*</span></label>
                        <input class="form-control  w-25" type="datetime-local" name="tgl_ujian" value="{{ old('tgl_ujian') }}" required />
                    </div>
                    <div class="form-group">
                        <label>Jumlah Soal <span class="text-danger">*</span></label>
                        <input class="form-control  w-25" type="number" name="jumlah_soal" value="{{ old('jumlah_soal') }}" required />
                    </div>
                    
                    <div class="form-group">
                        <label>Nilai Maksimal <span class="text-danger">*</span></label>
                        <input class="form-control  w-25" max="100" type="number" name="nilai_max" required />
                    </div>
                    <div class="form-group">
                        <label>Waktu Pengerjaan (Menit) <span class="text-danger">*</span></label>
                        <input class="form-control w-25" type="number" name="waktu_pengerjaan" value="{{ old('waktu_pengerjaan') }}" required />
                    </div>
                    <div class="form-group">
                        <label>status <span class="text-danger">*</span></label>
                        <select class="form-control w-25" name="status" required />
                        @foreach($status as $key => $val)
                            @if($key==old('status'))
                                <option value="{{ $key }}">{{ $val }}</option>
                            @else
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                
                    <div class="form-group">
                        <button class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('ujian') }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection