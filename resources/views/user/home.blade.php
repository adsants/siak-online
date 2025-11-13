@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Selamat datang {{ $user->name }}, berikut adalah daftar Ujian yang dapat anda kerjakan.</div> 
                <div class="card-body">
                    <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Jenis Soal</th>
                                <th>Jumlah Soal</th>
                                <th>Waktu Pengerjaan</th>
                                <th></th>
                            </tr>
                        </thead>
                        {!!$tableHtml!!}

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalToken" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="nameTitle"></h5>
      </div>
      <div class="modal-body">

        <input id="token" type="hidden">
        <input id="idUjianDetails" type="hidden">
        <input id="idUjianUser" type="hidden">

        <p>
        <input class="form-control" type="number" id="tokenInput" placeholder="Masukkan Token Ujian">
               
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitToken()">Lanjut</button>
      </div>
    </div>
  </div>
</div>

<script>
    function popUpToken(name,token,idUjianDetails,idUjianUser){
        $('#nameTitle').html(name);
        $('#token').val(token);
        $('#idUjianDetails').val(idUjianDetails);
        $('#idUjianUser').val(idUjianUser);
        $('#modalToken').modal('show');
        $('#tokenInput').focus();
    }

    function submitToken(){
        if( $('#token').val() !=  $('#tokenInput').val() ){
            alert('Pastikan Token anda Benar !');
        }
        else{
            window.location.href = "{{URL::to('ujian-info')}}"+"/"+$('#idUjianDetails').val()+"/"+$('#idUjianUser').val();

        }
    }

</script>

@endsection