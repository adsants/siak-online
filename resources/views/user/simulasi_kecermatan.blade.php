@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    
                
           Simulasi Ujian Kecermatan
                <div>
    </div>

                </div>

                <div class="card-body">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif


                <form action="{{ url('ujian-mulai-kecermatan',[ $row->idUjianDetails,  $row->idUjianUser]  ) }}" id="formSoal" method="get">

                </form>
                <form action="{{ url('ujian-mulai-kecermatan',[ $row->idUjianDetails,  $row->idUjianUser]  ) }}" id="fomSoal" method="get">

                        {!! $tampilSoals !!}
                </form>

                    
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="modalSelesai" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pesan Konfirmasi</h5>
      </div>
      <div class="modal-body">
        {!!$textInfo->info_akhir_simulasi!!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="selesai()">Ya</button>
      </div>
    </div>
  </div>
</div>

<script>
        //set minutes
        var mins = {{$row->waktu_pengerjaan}};
  
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
        countdown();
        function selesai(){
            $('form#formSoal').submit();
        }
        function klikJawaban(idPlusPlus,id_soal,angka_jawaban,huruf){
            $('#soal_'+id_soal).val(angka_jawaban);

            $('#spanId'+idPlusPlus).css('background-color', 'green');
            $('#spanId'+idPlusPlus).css('color', '#EEEBE9');
            $('#spanJawabanHurufId'+idPlusPlus).html(huruf);


            if(parseInt(idPlusPlus) == parseInt({{$totalSoalSimulasi}})){
                $('#modalSelesai').modal('show');
            }           



            nextId = parseInt(idPlusPlus) + 1;
            $('#tampil_soal_'+idPlusPlus).hide();
            $('#tampil_soal_'+nextId).show();

            

        }
    </script>
@endsection