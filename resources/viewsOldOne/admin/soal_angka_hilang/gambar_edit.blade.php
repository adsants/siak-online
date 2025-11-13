@extends('../../layouts.app')

@section('content')
<div class="card card-default">
    <div class="card-header">
        Ubah Data Soal Kecermatan
    </div>
    <div class="card-body p-4">

        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{ url('soal/update-kecermatan',$idSoal ) }}" method="POST">
                    
                    @csrf
                    @method('POST')
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
                    <?php $no = 1 ?>
                    @foreach($rows as $row)
                    
                    <div class="form-group">
                        <label class="control-label col-sm-12" >Soal {{$no}} :</label>
                        <div class="row">
                            <div class="col-sm-10">
                                <textarea class="ckeditor" required name="soal_{{ $row->id }}">{{ $row->soal}}</textarea>
                            </div>
                            
                            <div class="col-sm-2">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="status" <?php if($row->status == 'B'){echo "checked";} ?>  value="{{ $row->id }}"> 
                                    Jawaban Benar
                                    <span></span>
                                </label>	
                            </div>
                        </div>
                    </div>
                    <?php $no++;?>
                    @endforeach
                    
                    

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('soal/kecermatan') }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection