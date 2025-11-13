@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    
                    <div class="d-flex">
                        <div>
                            Ujian Simulasi
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    
                    @if($errors->any())
                    @foreach($errors->all() as $err)
                    <p class="alert alert-danger">{{ $err }}</p>
                    @endforeach
                    @endif

                    <form action="{{ url('ujian-mulai',[ $row->idUjianDetails,  $row->idUjianUser]  ) }}" id="formSoal" method="get">
                        {!! $tampilSoals !!}
                    </form>

 
                </div>
                
                <div class="card-footer">
                    <div class="d-flex">
                        <div>
                            
                            <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#" onclick="sebelumnya()">Sebelumnya</a></li>
                            </ul>
                        </div>
                        <div class="ml-auto">
                            
                            <ul class="pagination">
                            <li class="page-item "  id="btnNext" style="display:"><a class="page-link"href="#" onclick="selanjutnya()">Selanjutnya</a></li>
                            <li class="page-item" id="btnSend"  style="display:none"><a class="btn btn-success"  href="#" onclick="showModalSend()">Selesai Ujian Simulasi </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body d-flex justify-content-center">
                    <ul class="pagination">
                    {!!$tampilPaging!!}
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalSelesai" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pesan Konfirmasi</h5>
      </div>
      <div class="modal-body">
        {!!$textInfo->info_akhir_simulasi!!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="selesai()">Ya</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modalBenar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pesan Informasi</h5>
      </div>
      <div class="modal-body">
        {!!$textInfo->info_jawbaan_benar!!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modalSalah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pesan Informasi</h5>
      </div>
      <div class="modal-body">
        {!!$textInfo->info_jawaban_salah!!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
        //set minutes
        var mins = {{$row->kurang_waktu_pengerjaan}};
  
        //calculate the seconds
        var secs = mins * 60;
  
        //countdown function is evoked when page is loaded
        function countdown() {
            setTimeout('Decrement()', 60);
        }
  
        //Decrement function decrement the value.
        function Decrement() {
            if (document.getElementById) {
                minutes = document.getElementById("minutes");
                seconds = document.getElementById("seconds");
  
                //if less than a minute remaining
                //Display only seconds value.
                if (seconds < 59) {
                    seconds.value = secs;
                    $('#seconds').html(secs);

                }
  
                //Display both minutes and seconds
                //getminutes and getseconds is used to
                //get minutes and seconds
                else {
                    $('#minutes').html(getminutes());
                    
                    $('#seconds').html(getseconds());
                }
                //when less than a minute remaining
                //colour of the minutes and seconds
                //changes to red
                if (mins < 1) {
                    minutes.style.color = "red";
                    seconds.style.color = "red";
                }
                //if seconds becomes zero,
                //then page alert time up
                if (mins < 0) {
                    $('form#formSoal').submit();

                    minutes.value = 0;
                    seconds.value = 0;

                    $('#seconds').html('0');                    
                    $('#minutes').html('0');
                    
                }
                //if seconds > 0 then seconds is decremented
                else {
                    secs--;
                    setTimeout('Decrement()', 1000);
                }
            }
        }
  
        function getminutes() {
            //minutes is seconds divided by 60, rounded down
            mins = Math.floor(secs / 60);
            return mins;
        }
  
        function getseconds() {
            //take minutes remaining (as seconds) away 
            //from total seconds remaining
            return secs - Math.round(mins * 60);
        }
        //countdown();

        function klikJawaban(idPlusPlus,id_soal,angka_jawaban,huruf){            

            $('#spanId'+idPlusPlus).css('background-color', 'green');
            $('#spanId'+idPlusPlus).css('color', '#EEEBE9');
            $('#spanJawabanHurufId'+idPlusPlus).html(huruf);


            if(parseInt(idPlusPlus) == parseInt({{$row->jumlah_soal}})){
                $('form#formSoal').submit();
            }           



            nextId = parseInt(idPlusPlus) + 1;
            $('#tampil_soal_'+idPlusPlus).hide();
            $('#tampil_soal_'+nextId).show();

            

        }

        function sebelumnya(){
            hiddenAllSoal();
            var nomorSekarang   =   parseInt($('#inputSoalNomor').val());
            var tampil          =   parseInt(nomorSekarang) - 1;
            
            if(tampil < 1){
                var tampilDiv = 1;
            }else{
                var tampilDiv = tampil;
            }

            
            document.getElementById("soalNomor_"+tampilDiv).style.display = "";
        
            $('#inputSoalNomor').val(tampilDiv);
            spanSoalNomor(tampilDiv);
        }
        function selanjutnya(){
            hiddenAllSoal();
            var nomorSekarang   =   parseInt($('#inputSoalNomor').val());
            var tampil          =   parseInt(nomorSekarang) + 1;
            
            if(tampil > {{$jumlahSoalSimulasi }}){
                var tampilDiv = {{$jumlahSoalSimulasi }};
            }else{
                var tampilDiv = tampil;
            }
            
            document.getElementById("soalNomor_"+tampilDiv).style.display = "";        
            $('#inputSoalNomor').val(tampilDiv); 
            spanSoalNomor(tampilDiv);
        }

        function tampilSoalFromPaging(nomor){
            hiddenAllSoal();
            document.getElementById("soalNomor_"+nomor).style.display = "";    
            spanSoalNomor(nomor);
        }

        function spanSoalNomor(nomor){
            $('#spanSoalNomor').html(nomor);

            if(nomor == {{$jumlahSoalSimulasi }}){
                document.getElementById("btnSend").style.display = "";
                document.getElementById("btnNext").style.display = "none";
            }
            else{

                document.getElementById("btnSend").style.display = "none";
                document.getElementById("btnNext").style.display = "";
            }
        }

        function hiddenAllSoal(){
            <?php
            $JumlahSoalPlus1 =$jumlahSoalSimulasi +1;
            ?>
            for (let i = 1; i < {{$JumlahSoalPlus1 }}; i++) {
                document.getElementById("soalNomor_"+i).style.display = "none";
            }
        }
        function jawabSoalNomor(nomor,statusJawaban,id_jawaban){
            

            $('#nomorSoalBawah_'+nomor).addClass("active");   

            if(statusJawaban == 1){
                $('#modalBenar').modal('show');
            }
            else{
                $('#modalSalah').modal('show');
            }

        }

        function showModalSend(){
            $('#modalSelesai').modal('show');
        }

        function selesai(){
            $('form#formSoal').submit();
        }
        
    </script>
@endsection