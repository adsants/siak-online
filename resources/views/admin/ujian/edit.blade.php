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
                <form action="{{ url('ujian/update', $row->id ) }}" method="POST">
                    
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Nama Ujian <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name" value="{{ old('name', $row->name) }}" />
                    </div>
                  
                    <div class="form-group">
                        <label>Tgl Ujian <span class="text-danger">*</span></label>
                        <input class="form-control  w-25" type="datetime-local" name="tgl_ujian" value="{{ date('Y-m-d\TH:i', strtotime($row->tgl_ujian)) }}" />
                    </div>
                  
                    <div class="form-group">
                        <label>status <span class="text-danger">*</span></label>
                        <select class="form-control w-25" name="status">
                        @foreach($status as $key => $val)
                            @if($key==old('status',$row->status))
                                <option value="{{ $key }}" selected>{{ $val }}</option>
                            @else
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                    
                    

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('ujian') }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection