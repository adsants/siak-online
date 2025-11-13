@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    
                
                <div>
                Waktu mengerjakan tersisa 
                <b><span id='minutes' ></span> Menit : <span id='seconds' ></span> Detik</b>
            </div>

                </div>

                <div class="card-body">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif

                    <form action="{{ url('submit-ujian-kecermatan',  [ $row->idUjianDetails,  $row->idUjianUser] ) }}" id="formSoal" method="POST">
            
                        @csrf
                        @method('POST')

                            {!! $tampilSoals !!}
                    </form>

                    
                </div>
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

        function klikJawaban(idPlusPlus,id_soal,angka_jawaban,huruf){
            $('#soal_'+id_soal).val(angka_jawaban);

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
    </script>
@endsection