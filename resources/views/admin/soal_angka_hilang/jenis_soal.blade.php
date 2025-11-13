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
                <form >
                    <div class="form-group">
                        <label>Type Soal <span class="text-danger">*</span></label>
                        <select class="form-control" required name="type_soal" id="type_soal" onchange="gantiSoal()">
                            <option value="">Silahkan Pilih</option>
                            <option value="noGambar">Soal Non gambar</option>
                            <option value="gambar">Soal Bergambar</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function gantiSoal(){
        
        if($('#type_soal').val() == 'gambar'){
            location.href="{{URL::to('soal/gambar')}}";
        }
        else if($('#type_soal').val() == 'noGambar'){            
            location.href="{{URL::to('soal/nogambar')}}";
        }
        else{

        }
    }
</script>
@endsection