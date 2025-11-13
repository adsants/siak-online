@extends('../../layouts.app')

@section('content')

<div class="card card-default">
    <div class="card-header">
        Import Data Soal
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-8">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{ url('soal/import-process/'.$idJenisSoal) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Jenis Soal :</label>
                        <div class="row">
                            <div class="col-sm-12">
                            <select class="form-control " name="id_jenis_soal" >
                            <option value="" selected>Pilih Jenis Soal</option>
                            @foreach($jenisSoal as $row22)
                            <option value="{{$row22->id}}" 
                            @if($idJenisSoal == $row22->id)         
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
                    <input type="file" name="file" class="form-control">
                
                    <br>
                    <button class="btn btn-success">Import Soal Data</button>
                    <a class="btn btn-danger" href="{{ url('soal') }}">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection