@extends('../../layouts.app')

@section('content')


@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif


<div class="card card-default">
    <div class="card-header">
        Soal Ujian : {{$data_ujian->name}}
    </div>
    
    <div class="card-body">
    @if($errors->any())
            @foreach($errors->all() as $err)
            <p class="alert alert-danger">{{ $err }}</p>
            @endforeach
            @endif
        <form action="{{url('ujian-soal/store').'/'.$data_ujian->id }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="control-label col-sm-12" >Jenis Soal :</label>
                <div class="row">
                    <div class="col-sm-12">
                    <select class="form-control " required name="id_jenis_soal" onchange="pilihJenisSoal(this)">
                    <option value="" selected>Pilih Jenis Soal</option>
                    @foreach($jenisSoal as $row)
                    <option value="{{$row->id}}" 
                    
                    >{{$row->jenis_soal}}</option>
                    @endforeach

                </select>
                    </div>
                    
                </div>
            </div>
            <!--
            <div class="form-group">
                <label>Tgl Ujian <span class="text-danger">*</span></label>
                <input class="form-control  w-25" type="datetime-local" name="tgl_ujian" value="{{ old('tgl_ujian') }}" required />
            </div>
            -->
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
                <button class="btn btn-primary">Simpan</button>
                <a class="btn btn-danger" href="{{ url('ujian') }}">Kembali</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Soal</th>
                    <th>Jumlah Soal</th>
                    <th>Waktu Pengerjaan</th>
                    <th>Nilai Max</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php $no = 1 ?>
            @foreach($rows as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->jenis_soal }}</td>
                <td>{{ $row->jumlah_soal }}</td>
                <td>{{ $row->waktu_pengerjaan }} Menit</td>
                <td>{{ $row->nilai_max }}</td>
                <td>   <form method="POST" action="{{ url('ujian-soal/delete', $row->id) }}" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus Data?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
        <br>
        <br>
        <div class="d-flex justify-content-center">
    </div>
    </div>
</div>
<input id="myInput" style="display:none">
<script>
    function copyText(ujianName,tglUjian,token){
        var textCopy = "Silahkan Akses https://ujian.smartpsisurabaya.com/token untuk Ujian *"+ujianName+"*. Ujian akan dimulai pada Tanggal *"+tglUjian+"*. Token anda adalah *"+token+"*";
        $('#myInput').val(textCopy);

        var copyText = document.getElementById("myInput");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.value);

        /* Alert the copied text */
        alert("Berhasil copt Text");
    }
    
</script>
@endsection