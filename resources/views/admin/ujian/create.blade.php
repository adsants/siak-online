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
                        <label>Nama Ujian <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name" required value="{{ old('name') }}" />
                    </div>

                    <div class="form-group">
                        <label>Tgl Ujian <span class="text-danger">*</span></label>
                        <input class="form-control  w-25" type="datetime-local" name="tgl_ujian" value="{{ old('tgl_ujian') }}" required />
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
                        <label>Peserta Ujian <span class="text-danger">*</span></label>
                        <select class="form-control w-25" name="jenis_peserta" id="jenis_peserta" onchange="changePeserta(event)" required />
                                <option value="">Silahkan Pilih</option>
                                <option value="All">Semua Peserta</option>
                                <option value="Partial">Sebagian Peserta</option>
                        </select>
                    </div>
                    <div class="form-group" id='listPeserta' style='display:none'>
                        <label>Pilih Peserta Ujian <span class="text-danger">*</span></label>
                        <select class="tokenize-demo" name="list_peserta" multiple>
                            @foreach($listPeserta as $list)
                                <option value="{{ $list->id }}">{{  $list->name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group" id="inputArrayDiv">
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

<link href="{{ asset('/dist/tokenize2.min.css') }}" rel="stylesheet">

<script src="{{ asset('/dist/tokenize2.min.js') }}"></script>

<script>$('.tokenize-demo').tokenize2();</script>
<script>
function changePeserta(event){
    var selectElement = event.target;
    var jenisPeserta = selectElement.value;

    if(jenisPeserta == 'All'){
        $('#listPeserta').hide();
    }
    else{
        $('#listPeserta').show();

    }

}

$('.tokenize-demo').on("tokenize:tokens:add", function (event, value, text){
    // console.log("Value is "+value); // To get value
    // console.log("Text is "+ text); // To get text

     $('#inputArrayDiv').append("<input name='id_user[]' value='"+value+"' id='id_user_"+value+"' style='display:none'> ");
});
$('.tokenize-demo').on("tokenize:tokens:remove", function (event, value, text){
    $("#id_user_"+value+"").remove();

});

</script>
@endsection
